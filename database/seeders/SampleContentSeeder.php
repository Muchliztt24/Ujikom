<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Genre;
use App\Models\Work;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SampleContentSeeder extends Seeder
{
    private array $genreIds = [];

    public function run(): void
    {
        $this->genreIds = Genre::query()->pluck('id', 'name')->all();

        Work::query()
            ->with(['genres', 'chapters.images', 'user'])
            ->get()
            ->each(function (Work $work) {
                if ($work->chapters->isEmpty()) {
                    $this->createChaptersForWork($work);
                    $work->load('chapters.images');
                }

                $this->syncGenresForWork($work);

                $work->update([
                    'cover' => $this->generateCoverForWork($work),
                ]);

                if ($work->type === 'comic') {
                    $this->generateComicPagesForWork($work);
                }
            });
    }

    private function syncGenresForWork(Work $work): void
    {
        $existing = $work->genres->pluck('id')->all();
        $guessed = $this->guessGenreIds($work);

        $genreIds = collect(array_merge($existing, $guessed))
            ->unique()
            ->take(4)
            ->values()
            ->all();

        if ($genreIds !== []) {
            $work->genres()->sync($genreIds);
            $work->load('genres');
        }
    }

    private function guessGenreIds(Work $work): array
    {
        $title = Str::lower($work->title);
        $genres = $work->type === 'comic'
            ? ['Action', 'Adventure']
            : ['Drama', 'Fantasy'];

        $keywordMap = [
            'Cyberpunk' => ['cyber', 'chrome', 'neon', 'static', 'byte', 'ai'],
            'Sci-Fi' => ['mecha', 'signal', 'rift', 'void', 'metro', 'null'],
            'Supernatural' => ['ghost', 'yokai', 'exorcist', 'hymn'],
            'Mystery' => ['death', 'note', 'files', 'veil', 'archivist', 'meridian'],
            'Thriller' => ['afterfall', 'paradox', 'silence', 'final', 'ashes'],
            'Dark Fantasy' => ['shadow', 'black', 'hollow', 'ashfall'],
            'Fantasy' => ['dragon', 'lotus', 'aether', 'celestial', 'crown'],
            'Historical' => ['empire', 'dynasty', 'emperor', 'babylon', 'pagoda'],
            'Romance' => ['love', 'winter', 'letters', 'saffron'],
            'Slice of Life' => ['bumi', 'bulan', 'matahari', 'laskar', 'menara'],
            'Drama' => ['broken', 'silent', 'western', 'eastern', 'pacific'],
            'Adventure' => ['runner', 'harbor', 'beyond', 'beginning'],
            'Detective' => ['files', 'note', 'calligraphy'],
            'Psychological' => ['death', 'hollow', 'silence', 'void'],
            'Martial Arts' => ['katana', 'fist', 'ronin', 'steel'],
            'Mythology' => ['garuda', 'wayang', 'borobudur', 'yokai'],
            'Post-Apocalyptic' => ['afterfall', 'afterlight', 'ashes'],
        ];

        foreach ($keywordMap as $genre => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($title, $keyword)) {
                    $genres[] = $genre;
                    break;
                }
            }
        }

        if (str_contains($title, 'seoul') || str_contains($title, 'tokyo') || str_contains($title, 'jakarta') || str_contains($title, 'busan') || str_contains($title, 'osaka')) {
            $genres[] = 'Action';
        }

        return collect($genres)
            ->filter(fn (string $genre) => isset($this->genreIds[$genre]))
            ->map(fn (string $genre) => $this->genreIds[$genre])
            ->unique()
            ->take(4)
            ->values()
            ->all();
    }

    private function createChaptersForWork(Work $work): void
    {
        $chapterTitles = [
            1 => 'Permulaan',
            2 => 'Bayangan Pertama',
            3 => 'Langkah Penentu',
        ];

        foreach ($chapterTitles as $number => $title) {
            Chapter::query()->create([
                'work_id' => $work->id,
                'chapter_number' => $number,
                'title' => $title,
                'text_content' => $work->type === 'novel'
                    ? $this->buildNovelContent($work->title, $number, $title)
                    : null,
            ]);
        }
    }

    private function generateCoverForWork(Work $work): string
    {
        $slug = Str::slug($work->title);
        $path = "generated/covers/{$work->id}-{$slug}.svg";
        $palette = $this->paletteFor($work->title, $work->type);
        $titleLines = $this->wrapTitle($work->title, 18, 3);
        $genres = $work->genres->pluck('name')->take(3)->implode(' / ');
        $typeLabel = strtoupper($work->type);
        $author = $work->user?->name ?? 'Nokomi';

        $titleSvg = '';
        foreach ($titleLines as $index => $line) {
            $y = 360 + ($index * 86);
            $titleSvg .= '<text x="84" y="'.$y.'" font-family="Georgia, serif" font-size="64" font-weight="700" fill="#f8fafc">'.$this->escape($line).'</text>';
        }

        $motif = $this->motifSvg($work->title, $palette);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="1200" viewBox="0 0 900 1200">
    <defs>
        <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="{$palette['bg1']}" />
            <stop offset="100%" stop-color="{$palette['bg2']}" />
        </linearGradient>
        <linearGradient id="accent" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="{$palette['accent']}" />
            <stop offset="100%" stop-color="{$palette['accent2']}" />
        </linearGradient>
    </defs>
    <rect width="900" height="1200" fill="url(#bg)" rx="32" />
    <rect x="52" y="52" width="796" height="1096" rx="28" fill="rgba(8,15,23,0.14)" stroke="rgba(255,255,255,0.12)" />
    <rect x="84" y="90" width="210" height="46" rx="23" fill="url(#accent)" />
    <text x="118" y="120" font-family="Arial, sans-serif" font-size="24" font-weight="700" fill="#081018">{$typeLabel}</text>
    <text x="84" y="170" font-family="Arial, sans-serif" font-size="22" font-weight="700" fill="{$palette['soft']}">NOKOMI COLLECTION</text>
    {$motif}
    {$titleSvg}
    <rect x="84" y="820" width="732" height="2" fill="rgba(255,255,255,0.18)" />
    <text x="84" y="892" font-family="Arial, sans-serif" font-size="28" font-weight="700" fill="#f8fafc">{$this->escape($author)}</text>
    <text x="84" y="938" font-family="Arial, sans-serif" font-size="24" fill="{$palette['soft']}">{$this->escape($genres !== '' ? $genres : 'Signature Series')}</text>
    <rect x="84" y="988" width="732" height="120" rx="24" fill="rgba(255,255,255,0.06)" stroke="rgba(255,255,255,0.08)" />
    <text x="118" y="1044" font-family="Arial, sans-serif" font-size="20" font-weight="700" fill="{$palette['accent2']}">{$this->escape($this->taglineFor($work->title, $work->type))}</text>
    <text x="118" y="1084" font-family="Arial, sans-serif" font-size="18" fill="#e2e8f0">{$this->escape($this->sublineFor($work->title))}</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    private function generateComicPagesForWork(Work $work): void
    {
        foreach ($work->chapters as $chapter) {
            ChapterImage::query()->where('chapter_id', $chapter->id)->delete();

            for ($page = 1; $page <= 4; $page++) {
                $path = $this->generateComicPage($work, $chapter, $page);

                ChapterImage::query()->create([
                    'chapter_id' => $chapter->id,
                    'image_url' => $path,
                    'page_number' => $page,
                ]);
            }
        }
    }

    private function generateComicPage(Work $work, Chapter $chapter, int $page): string
    {
        $slug = Str::slug($work->title);
        $path = "generated/comic-pages/{$slug}/chapter-{$chapter->chapter_number}-page-{$page}.svg";
        $palette = $this->paletteFor($work->title.'-'.$page, 'comic');
        $words = $this->keyWords($work->title);
        $headline = strtoupper(implode(' ', array_slice($words, 0, 2)));
        $sceneOne = strtoupper(($words[0] ?? 'SCENE').' AWAKENS');
        $sceneTwo = strtoupper(($words[1] ?? 'CITY').' RESPONDS');
        $sceneThree = strtoupper('CHAPTER '.$chapter->chapter_number.' / PAGE '.$page);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1080" height="1600" viewBox="0 0 1080 1600">
    <defs>
        <linearGradient id="pagebg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="{$palette['bg1']}" />
            <stop offset="100%" stop-color="{$palette['bg2']}" />
        </linearGradient>
        <linearGradient id="panelA" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="{$palette['accent']}" />
            <stop offset="100%" stop-color="{$palette['accent2']}" />
        </linearGradient>
    </defs>
    <rect width="1080" height="1600" fill="url(#pagebg)" />
    <rect x="38" y="36" width="1004" height="1528" rx="34" fill="rgba(8,12,18,0.22)" stroke="rgba(255,255,255,0.10)" />
    <text x="74" y="106" font-family="Arial, sans-serif" font-size="26" font-weight="700" fill="#f8fafc">{$this->escape($work->title)}</text>
    <text x="74" y="146" font-family="Arial, sans-serif" font-size="18" fill="{$palette['soft']}">{$this->escape($chapter->title ?: 'Chapter '.$chapter->chapter_number)}</text>
    <rect x="74" y="182" width="932" height="316" rx="26" fill="rgba(255,255,255,0.06)" />
    <rect x="74" y="182" width="932" height="316" rx="26" fill="url(#panelA)" opacity="0.28" />
    <text x="110" y="276" font-family="Arial, sans-serif" font-size="64" font-weight="800" fill="#ffffff">{$this->escape($headline)}</text>
    <text x="110" y="338" font-family="Arial, sans-serif" font-size="26" font-weight="700" fill="#eef2ff">{$this->escape($sceneThree)}</text>
    <circle cx="878" cy="280" r="90" fill="rgba(255,255,255,0.12)" />
    <circle cx="878" cy="280" r="46" fill="rgba(255,255,255,0.22)" />
    <rect x="74" y="534" width="446" height="420" rx="24" fill="rgba(255,255,255,0.05)" />
    <rect x="560" y="534" width="446" height="420" rx="24" fill="rgba(255,255,255,0.05)" />
    <rect x="74" y="990" width="932" height="500" rx="24" fill="rgba(255,255,255,0.05)" />
    <path d="M140 860 C260 720, 320 690, 456 614" stroke="{$palette['accent2']}" stroke-width="16" fill="none" stroke-linecap="round" />
    <path d="M610 874 C754 736, 816 704, 932 610" stroke="{$palette['accent']}" stroke-width="16" fill="none" stroke-linecap="round" />
    <path d="M162 1360 C332 1180, 544 1128, 882 1040" stroke="{$palette['accent2']}" stroke-width="24" fill="none" stroke-linecap="round" />
    <rect x="118" y="570" width="192" height="44" rx="22" fill="rgba(8,12,18,0.42)" />
    <rect x="604" y="570" width="192" height="44" rx="22" fill="rgba(8,12,18,0.42)" />
    <rect x="118" y="1026" width="220" height="44" rx="22" fill="rgba(8,12,18,0.42)" />
    <text x="146" y="600" font-family="Arial, sans-serif" font-size="22" font-weight="700" fill="#ffffff">{$this->escape($sceneOne)}</text>
    <text x="632" y="600" font-family="Arial, sans-serif" font-size="22" font-weight="700" fill="#ffffff">{$this->escape($sceneTwo)}</text>
    <text x="146" y="1056" font-family="Arial, sans-serif" font-size="22" font-weight="700" fill="#ffffff">{$this->escape('STAGE '.$page)}</text>
    <text x="120" y="1518" font-family="Arial, sans-serif" font-size="20" fill="{$palette['soft']}">Nokomi Original Page</text>
    <text x="934" y="1518" font-family="Arial, sans-serif" font-size="20" text-anchor="end" fill="{$palette['soft']}">{$page}</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    private function paletteFor(string $title, string $type): array
    {
        $palettes = [
            ['bg1' => '#07131f', 'bg2' => '#11273a', 'accent' => '#22d3ee', 'accent2' => '#8b5cf6', 'soft' => '#a5f3fc'],
            ['bg1' => '#1b1022', 'bg2' => '#4c1d3d', 'accent' => '#fb7185', 'accent2' => '#f59e0b', 'soft' => '#fecdd3'],
            ['bg1' => '#102218', 'bg2' => '#1f5b43', 'accent' => '#34d399', 'accent2' => '#facc15', 'soft' => '#bbf7d0'],
            ['bg1' => '#1b1223', 'bg2' => '#2f2f62', 'accent' => '#c084fc', 'accent2' => '#60a5fa', 'soft' => '#ddd6fe'],
            ['bg1' => '#23150f', 'bg2' => '#6b3b1f', 'accent' => '#f59e0b', 'accent2' => '#fb7185', 'soft' => '#fde68a'],
            ['bg1' => '#0d1b2a', 'bg2' => '#1d3557', 'accent' => '#7dd3fc', 'accent2' => '#f472b6', 'soft' => '#bfdbfe'],
        ];

        $index = abs(crc32(Str::lower($title).'-'.$type)) % count($palettes);

        return $palettes[$index];
    }

    private function motifSvg(string $title, array $palette): string
    {
        $lower = Str::lower($title);

        if (str_contains($lower, 'neon') || str_contains($lower, 'cyber') || str_contains($lower, 'chrome')) {
            return '
                <rect x="560" y="210" width="220" height="220" rx="28" fill="rgba(255,255,255,0.05)" stroke="'.$palette['accent'].'" stroke-width="8" />
                <rect x="608" y="258" width="124" height="124" rx="18" fill="none" stroke="'.$palette['accent2'].'" stroke-width="6" />
                <path d="M560 510 H812" stroke="rgba(255,255,255,0.18)" stroke-width="4" stroke-dasharray="12 12" />
            ';
        }

        if (str_contains($lower, 'moon') || str_contains($lower, 'night') || str_contains($lower, 'winter')) {
            return '
                <circle cx="700" cy="292" r="104" fill="rgba(255,255,255,0.12)" />
                <circle cx="746" cy="276" r="84" fill="url(#accent)" opacity="0.55" />
                <circle cx="780" cy="250" r="84" fill="url(#bg)" />
            ';
        }

        if (str_contains($lower, 'dragon') || str_contains($lower, 'lotus') || str_contains($lower, 'crown')) {
            return '
                <path d="M610 450 C680 230, 774 220, 814 360 C770 332, 722 346, 690 398 C728 402, 774 420, 810 472 C736 478, 666 474, 610 450 Z" fill="rgba(255,255,255,0.10)" stroke="'.$palette['accent2'].'" stroke-width="6" />
                <circle cx="730" cy="326" r="10" fill="'.$palette['accent'].'" />
            ';
        }

        return '
            <circle cx="710" cy="292" r="118" fill="rgba(255,255,255,0.08)" />
            <rect x="592" y="174" width="236" height="236" rx="40" fill="none" stroke="rgba(255,255,255,0.12)" stroke-width="4" />
            <path d="M612 432 C684 392, 750 368, 832 240" stroke="'.$palette['accent2'].'" stroke-width="10" fill="none" stroke-linecap="round" />
        ';
    }

    private function taglineFor(string $title, string $type): string
    {
        $lead = strtoupper($this->keyWords($title)[0] ?? 'STORY');

        return $type === 'comic'
            ? $lead.' EDITION'
            : $lead.' CHRONICLE';
    }

    private function sublineFor(string $title): string
    {
        $words = $this->keyWords($title);
        $second = strtoupper($words[1] ?? $words[0] ?? 'SERIES');

        return $second.' / SIGNATURE TITLE';
    }

    private function keyWords(string $title): array
    {
        $words = preg_split('/\s+/', Str::of($title)->replace(':', ' ')->replace('-', ' ')->toString()) ?: [];
        $filtered = collect($words)
            ->map(fn ($word) => trim($word))
            ->filter(fn ($word) => $word !== '' && ! in_array(Str::lower($word), ['the', 'of', 'and', 'is', 'after'], true))
            ->values()
            ->all();

        return $filtered !== [] ? $filtered : ['Nokomi'];
    }

    private function wrapTitle(string $title, int $maxChars, int $maxLines): array
    {
        $words = preg_split('/\s+/', trim($title)) ?: [];
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = trim($current.' '.$word);

            if ($current === '' || mb_strlen($candidate) <= $maxChars) {
                $current = $candidate;
                continue;
            }

            $lines[] = $current;
            $current = $word;
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        if (count($lines) > $maxLines) {
            $lines = array_slice($lines, 0, $maxLines);
            $last = $lines[$maxLines - 1];
            $lines[$maxLines - 1] = rtrim(mb_substr($last, 0, max(1, $maxChars - 3))).'...';
        }

        return $lines;
    }

    private function buildNovelContent(string $title, int $chapterNumber, string $chapterTitle): string
    {
        return "Chapter {$chapterNumber}: {$chapterTitle}\n\n"
            ."Kisah {$title} bergerak perlahan ke babak yang lebih dalam, menghadirkan suasana, konflik, dan pilihan yang tak mudah ditebak. "
            ."Setiap percakapan menyimpan petunjuk, dan setiap keputusan membuka jalan ke lapisan cerita berikutnya.\n\n"
            ."Tokoh utama terus melangkah, membawa harapan serta keraguan dalam porsi yang sama besar. "
            ."Di antara perubahan itu, satu hal tetap terasa jelas: dunia di sekelilingnya sedang bergeser, dan tidak semua orang siap menghadapi arahnya.\n\n"
            ."Akhir bab ini meninggalkan jeda yang tenang namun tajam, seolah mengingatkan bahwa perjalanan baru saja dimulai.";
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
