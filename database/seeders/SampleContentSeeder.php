<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\ReadingHistory;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SampleContentSeeder extends Seeder
{
    private array $genreIds = [];

    private array $catalog = [
        [
            'title' => 'Bumi',
            'original_author' => 'Tere Liye',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'asep',
            'genres' => ['Fantasy', 'Adventure', 'Sci-Fi'],
            'description' => 'Novel fantasi remaja Indonesia yang mengikuti Raib, Seli, dan Ali saat membuka rahasia dunia paralel dan klan-klan yang saling terhubung.',
            'chapters' => [
                ['title' => 'Tanda di Rumah', 'text' => 'Raib mulai curiga bahwa bakat aneh yang selama ini ia sembunyikan ternyata berkaitan dengan dunia yang jauh lebih besar dari kehidupan sekolahnya.'],
                ['title' => 'Gerbang Pertama', 'text' => 'Pertemuan dengan Seli dan Ali membuka jalan menuju konflik antar klan, sekaligus memperlihatkan bahwa perjalanan mereka baru saja dimulai.'],
                ['title' => 'Rahasia Keluarga', 'text' => 'Potongan masa lalu dan identitas Raib mulai terhubung, memberi arah baru pada petualangan yang selama ini terasa kabur.'],
            ],
        ],
        [
            'title' => 'Bulan',
            'original_author' => 'Tere Liye',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'nayla',
            'genres' => ['Fantasy', 'Adventure', 'Sci-Fi'],
            'description' => 'Kelanjutan petualangan dunia paralel yang membawa trio utama ke wilayah baru dengan aturan, tantangan, dan musuh yang lebih rumit.',
            'chapters' => [
                ['title' => 'Wilayah Asing', 'text' => 'Dunia tujuan berikutnya menghadirkan suasana yang lebih asing, dengan ancaman yang tidak lagi mudah dipahami hanya dari insting.'],
                ['title' => 'Aliansi Rapuh', 'text' => 'Teman dan lawan kadang berdiri di tempat yang sama, membuat keputusan kecil terasa jauh lebih berbahaya.'],
                ['title' => 'Langit yang Retak', 'text' => 'Petunjuk besar tentang konflik utama muncul, memaksa semua tokoh melihat kembali alasan mereka terus maju.'],
            ],
        ],
        [
            'title' => 'Matahari',
            'original_author' => 'Tere Liye',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'raka',
            'genres' => ['Fantasy', 'Adventure', 'Sci-Fi'],
            'description' => 'Salah satu entry penting serial Dunia Paralel yang memperluas skala konflik, memperdalam relasi tokoh, dan memperlihatkan ancaman yang lebih terang-terangan.',
            'chapters' => [
                ['title' => 'Jejak yang Hilang', 'text' => 'Perjalanan menuju pusat konflik dimulai dengan teka-teki baru yang membuat arah pencarian menjadi semakin mendesak.'],
                ['title' => 'Batas Kepercayaan', 'text' => 'Setiap tokoh diuji oleh keputusan yang memisahkan logika, perasaan, dan loyalitas mereka terhadap satu sama lain.'],
                ['title' => 'Cahaya Terakhir', 'text' => 'Bab ini menutup satu fase perjalanan dengan suasana intens, sambil menyiapkan skala konflik yang lebih luas untuk tahap berikutnya.'],
            ],
        ],
        [
            'title' => 'Harry Potter and the Philosopher\'s Stone',
            'original_author' => 'J.K. Rowling',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'asep',
            'genres' => ['Fantasy', 'Adventure', 'Mystery'],
            'description' => 'Awal kisah Harry Potter saat ia mengetahui dirinya seorang penyihir dan memasuki Hogwarts, sekolah sihir yang menyimpan banyak rahasia.',
            'chapters' => [
                ['title' => 'The Letter', 'text' => 'Kehidupan Harry berubah ketika surat-surat misterius datang dan perlahan membuka kenyataan tentang masa lalunya.'],
                ['title' => 'Hogwarts', 'text' => 'Dunia sihir memperlihatkan keajaiban, persahabatan, dan aturan-aturan baru yang sama menariknya dengan ancaman tersembunyi di baliknya.'],
                ['title' => 'The Stone', 'text' => 'Misteri tentang batu legendaris menjadi pusat petualangan yang menguji keberanian dan rasa ingin tahu para tokoh muda.'],
            ],
        ],
        [
            'title' => 'The Hobbit',
            'original_author' => 'J.R.R. Tolkien',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'nayla',
            'genres' => ['Fantasy', 'Adventure'],
            'description' => 'Petualangan klasik Bilbo Baggins yang terseret dalam pencarian harta, pertemuan dengan naga, dan perjalanan besar di Middle-earth.',
            'chapters' => [
                ['title' => 'Unexpected Journey', 'text' => 'Bilbo yang terbiasa tenang dipaksa meninggalkan rutinitas untuk memasuki petualangan yang sama asingnya dengan menegangkan.'],
                ['title' => 'In the Wild', 'text' => 'Perjalanan bersama rombongan kurcaci mengubah Bilbo sedikit demi sedikit, terutama saat bahaya mulai terasa nyata.'],
                ['title' => 'Under the Mountain', 'text' => 'Mendekati tujuan akhir, kisah ini menegaskan bahwa keberanian kadang tumbuh justru dari sosok yang paling tidak terduga.'],
            ],
        ],
        [
            'title' => 'The King in Yellow',
            'original_author' => 'Robert W. Chambers',
            'type' => 'novel',
            'status' => 'pending',
            'uploader' => 'raka',
            'genres' => ['Horror', 'Mystery', 'Psychological'],
            'description' => 'Kumpulan cerita klasik weird fiction yang dikenal lewat nuansa kosmik, kegilaan, simbolisme, dan drama psikologis di sekitar naskah terlarang.',
            'chapters' => [
                ['title' => 'The Forbidden Play', 'text' => 'Cerita dibuka lewat aura naskah misterius yang memengaruhi cara tokoh-tokohnya memandang kenyataan.'],
                ['title' => 'Whispers of Carcosa', 'text' => 'Simbol, bisikan, dan rasa takut yang tidak jelas asalnya membuat ketegangan tumbuh tanpa perlu ledakan besar.'],
                ['title' => 'A Fractured Mind', 'text' => 'Nada psikologis semakin pekat ketika batas antara imajinasi, obsesi, dan kenyataan perlahan mengabur.'],
            ],
        ],
        [
            'title' => 'Percy Jackson & The Olympians: The Lightning Thief',
            'original_author' => 'Rick Riordan',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'asep',
            'genres' => ['Adventure', 'Fantasy'],
            'description' => 'Novel fantasi petualangan yang memperkenalkan Percy Jackson ke dunia para dewa Yunani, monster, dan ramalan kuno.',
            'chapters' => [
                ['title' => 'The Yancy Incident', 'text' => 'Percy mulai melihat bahwa masalah hidupnya tidak sesederhana sekolah dan keluarga, melainkan berkaitan dengan dunia mitologi yang hidup di balik kenyataan.'],
                ['title' => 'Camp Half-Blood', 'text' => 'Tempat aman baru justru membuka lebih banyak pertanyaan tentang identitas, warisan, dan perang besar yang perlahan mendekat.'],
                ['title' => 'A Dangerous Quest', 'text' => 'Petualangan resmi dimulai dengan misi yang menuntut keberanian, kecerdikan, dan kepercayaan kepada rekan seperjalanan.'],
            ],
        ],
        [
            'title' => 'The Hunger Games',
            'original_author' => 'Suzanne Collins',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'nayla',
            'genres' => ['Drama', 'Thriller', 'Adventure'],
            'description' => 'Novel dystopian yang mengikuti Katniss Everdeen saat ia dipaksa masuk arena mematikan dan menjadi simbol perlawanan.',
            'chapters' => [
                ['title' => 'Reaping Day', 'text' => 'Keputusan sukarela Katniss mengubah hidupnya seketika, sekaligus mengikat takdir keluarganya dengan panggung kekejaman negara.'],
                ['title' => 'Capitol', 'text' => 'Gemerlap kemewahan hanya membuat kontras arena menjadi lebih menakutkan, karena setiap detail dirancang untuk tontonan.'],
                ['title' => 'Into the Arena', 'text' => 'Begitu permainan dimulai, insting bertahan hidup, strategi, dan emosi pribadi bertabrakan dalam waktu yang sangat singkat.'],
            ],
        ],
        [
            'title' => 'Laskar Pelangi',
            'original_author' => 'Andrea Hirata',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'raka',
            'genres' => ['Drama', 'Adventure', 'Comedy'],
            'description' => 'Novel Indonesia yang mengangkat persahabatan, pendidikan, dan daya tahan mimpi anak-anak di Belitung.',
            'chapters' => [
                ['title' => 'Sekolah Kecil', 'text' => 'Dunia sekolah sederhana menjadi ruang tumbuh bagi imajinasi, harapan, dan solidaritas yang kuat di antara anak-anaknya.'],
                ['title' => 'Mimpi yang Besar', 'text' => 'Setiap tokoh memperlihatkan bentuk perjuangan yang berbeda, tetapi semuanya bertemu pada satu hal: keberanian untuk tetap bermimpi.'],
                ['title' => 'Persahabatan', 'text' => 'Kisah ini terus hidup lewat ikatan antarteman yang membuat keterbatasan terasa tidak cukup kuat untuk mematahkan semangat.'],
            ],
        ],
        [
            'title' => 'A Study in Scarlet',
            'original_author' => 'Arthur Conan Doyle',
            'type' => 'novel',
            'status' => 'approved',
            'uploader' => 'asep',
            'genres' => ['Mystery', 'Thriller', 'Adventure'],
            'description' => 'Novel detektif pertama Sherlock Holmes yang memperkenalkan metode observasi tajam dan deduksi ikoniknya.',
            'chapters' => [
                ['title' => 'Meeting Holmes', 'text' => 'Watson bertemu Holmes dan segera menyadari bahwa sosok eksentrik itu memiliki cara membaca dunia yang jauh melampaui kebanyakan orang.'],
                ['title' => 'A Strange Case', 'text' => 'Kasus pembunuhan yang tampak tak masuk akal justru menjadi panggung ideal bagi kemampuan deduksi yang teliti dan dingin.'],
                ['title' => 'The Red Thread', 'text' => 'Penyelidikan ini memperlihatkan bagaimana satu petunjuk kecil dapat membuka hubungan besar di balik kejadian yang terlihat acak.'],
            ],
        ],
        [
            'title' => 'Chainsaw Man',
            'original_author' => 'Tatsuki Fujimoto',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'asep',
            'genres' => ['Action', 'Dark Fantasy', 'Horror'],
            'description' => 'Manga aksi gelap tentang Denji, pemuda yang kehidupannya berubah drastis setelah bergabung dengan iblis gergaji dan masuk ke dunia pemburu iblis.',
            'chapters' => [
                ['title' => 'Denji', 'pages' => 4],
                ['title' => 'Public Safety', 'pages' => 4],
                ['title' => 'Bat Devil', 'pages' => 4],
            ],
        ],
        [
            'title' => 'One Piece',
            'original_author' => 'Eiichiro Oda',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'nayla',
            'genres' => ['Adventure', 'Comedy', 'Fantasy'],
            'description' => 'Petualangan bajak laut karya Eiichiro Oda yang mengikuti Monkey D. Luffy dalam perjalanan mencari harta legendaris dan membangun kru impian.',
            'chapters' => [
                ['title' => 'Romance Dawn', 'pages' => 4],
                ['title' => 'A New Crew', 'pages' => 4],
                ['title' => 'Heading to Grand Line', 'pages' => 4],
            ],
        ],
        [
            'title' => 'Naruto',
            'original_author' => 'Masashi Kishimoto',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'raka',
            'genres' => ['Action', 'Adventure', 'Martial Arts'],
            'description' => 'Manga shounen populer tentang Naruto Uzumaki, ninja muda yang berjuang meraih pengakuan dan mengejar impian menjadi Hokage.',
            'chapters' => [
                ['title' => 'The Outcast', 'pages' => 4],
                ['title' => 'Team Seven', 'pages' => 4],
                ['title' => 'Wave Mission', 'pages' => 4],
            ],
        ],
        [
            'title' => 'Jujutsu Kaisen',
            'original_author' => 'Gege Akutami',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'asep',
            'genres' => ['Action', 'Supernatural', 'Dark Fantasy'],
            'description' => 'Manga aksi supernatural tentang Itadori Yuji yang terseret ke dunia kutukan, penyihir jujutsu, dan pertarungan berisiko tinggi.',
            'chapters' => [
                ['title' => 'The Curse', 'pages' => 4],
                ['title' => 'Tokyo Jujutsu High', 'pages' => 4],
                ['title' => 'First Mission', 'pages' => 4],
            ],
        ],
        [
            'title' => 'Attack on Titan',
            'original_author' => 'Hajime Isayama',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'nayla',
            'genres' => ['Action', 'Thriller', 'Dark Fantasy'],
            'description' => 'Manga dystopian karya Hajime Isayama tentang umat manusia yang bertahan di balik tembok sambil menghadapi ancaman Titan.',
            'chapters' => [
                ['title' => 'The Fall', 'pages' => 4],
                ['title' => 'Cadet Corps', 'pages' => 4],
                ['title' => 'Counterattack', 'pages' => 4],
            ],
        ],
        [
            'title' => 'Death Note',
            'original_author' => 'Tsugumi Ohba & Takeshi Obata',
            'type' => 'comic',
            'status' => 'pending',
            'uploader' => 'raka',
            'genres' => ['Mystery', 'Psychological', 'Thriller'],
            'description' => 'Thriller psikologis tentang notebook mematikan yang jatuh ke tangan Light Yagami dan memicu duel intelektual melawan L.',
            'chapters' => [
                ['title' => 'The Notebook', 'pages' => 4],
                ['title' => 'Kira', 'pages' => 4],
                ['title' => 'The Detective', 'pages' => 4],
            ],
        ],
        [
            'title' => 'Solo Leveling',
            'original_author' => 'Chugong',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'nayla',
            'genres' => ['Action', 'Fantasy', 'Thriller'],
            'description' => 'Web novel Korea yang sangat populer dan dikenal luas lewat adaptasi manhwa-nya, mengikuti Sung Jin-Woo yang bangkit dari hunter terlemah menjadi kekuatan besar.',
            'chapters' => [
                ['title' => 'The Weakest Hunter', 'pages' => 4],
                ['title' => 'Double Dungeon', 'pages' => 4],
                ['title' => 'Player System', 'pages' => 4],
            ],
        ],
        [
            'title' => 'Tower of God',
            'original_author' => 'SIU',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'raka',
            'genres' => ['Adventure', 'Fantasy', 'Mystery'],
            'description' => 'Manhwa/webtoon fantasi tentang menara misterius yang menjanjikan apapun bagi siapa pun yang mampu mencapai puncaknya.',
            'chapters' => [
                ['title' => 'The Door', 'pages' => 4],
                ['title' => 'Test Floor', 'pages' => 4],
                ['title' => 'The Climb Begins', 'pages' => 4],
            ],
        ],
        [
            'title' => 'Omniscient Reader\'s Viewpoint',
            'original_author' => 'Sing Shong',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'asep',
            'genres' => ['Action', 'Fantasy', 'Psychological'],
            'description' => 'Cerita populer Korea tentang pembaca novel web yang tiba-tiba hidup di dunia cerita yang selama ini hanya ia baca seorang diri.',
            'chapters' => [
                ['title' => 'Only Reader', 'pages' => 4],
                ['title' => 'Scenario Start', 'pages' => 4],
                ['title' => 'Breaking the Plot', 'pages' => 4],
            ],
        ],
        [
            'title' => 'The Beginning After the End',
            'original_author' => 'TurtleMe',
            'type' => 'comic',
            'status' => 'approved',
            'uploader' => 'raka',
            'genres' => ['Adventure', 'Fantasy', 'Drama'],
            'description' => 'Serial fantasi populer tentang raja besar yang bereinkarnasi ke dunia baru dan harus membangun hidupnya kembali dari awal.',
            'chapters' => [
                ['title' => 'A Second Life', 'pages' => 4],
                ['title' => 'Mana Awakening', 'pages' => 4],
                ['title' => 'A New Path', 'pages' => 4],
            ],
        ],
    ];

    public function run(): void
    {
        $this->genreIds = Genre::query()->pluck('id', 'name')->all();
        Storage::disk('public')->deleteDirectory('generated/real-catalog');

        $uploaders = [
            'asep' => User::query()->where('email', 'asep@nokomi.test')->firstOrFail(),
            'nayla' => User::query()->where('email', 'nayla@nokomi.test')->firstOrFail(),
            'raka' => User::query()->where('email', 'raka@nokomi.test')->firstOrFail(),
        ];

        $readers = User::query()
            ->whereIn('email', ['allay@nokomi.test', 'rani@nokomi.test', 'bagas@nokomi.test'])
            ->get()
            ->keyBy('email');

        foreach ($this->catalog as $entry) {
            $work = Work::query()->create([
                'title' => $entry['title'],
                'original_author' => $entry['original_author'],
                'description' => $entry['description'],
                'cover' => $this->generateCover($entry['title'], $entry['original_author'], $entry['type']),
                'type' => $entry['type'],
                'user_id' => $uploaders[$entry['uploader']]->id,
                'status' => $entry['status'],
            ]);

            $work->genres()->sync(
                collect($entry['genres'])
                    ->map(fn (string $genre) => $this->genreIds[$genre] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );

            $chapters = collect($entry['chapters'])->map(function (array $chapterData, int $index) use ($work) {
                $chapter = Chapter::query()->create([
                    'work_id' => $work->id,
                    'chapter_number' => $index + 1,
                    'title' => $chapterData['title'],
                    'text_content' => $work->type === 'novel'
                        ? $this->buildNovelPreview($work, $chapterData['title'], $chapterData['text'])
                        : null,
                ]);

                if ($work->type === 'comic') {
                    $this->createComicPages($work, $chapter, (int) ($chapterData['pages'] ?? 4));
                }

                return $chapter;
            })->values();

            if ($work->status === 'approved') {
                $this->seedReaderActivity($work, $chapters, $readers->all());
            }
        }
    }

    private function seedReaderActivity(Work $work, $chapters, array $readers): void
    {
        $readerPool = array_values($readers);
        if ($readerPool === []) {
            return;
        }

        foreach (array_slice($readerPool, 0, 2) as $offset => $reader) {
            $lastChapter = $chapters[min($offset + 1, $chapters->count() - 1)];

            Bookmark::query()->updateOrCreate(
                [
                    'user_id' => $reader->id,
                    'work_id' => $work->id,
                ],
                [
                    'last_chapter_read' => $lastChapter->chapter_number,
                ]
            );

            ReadingHistory::query()->updateOrCreate(
                [
                    'user_id' => $reader->id,
                    'work_id' => $work->id,
                ],
                [
                    'chapter_id' => $lastChapter->id,
                    'last_read_at' => now()->subDays($offset + 1),
                ]
            );

            Comment::query()->create([
                'user_id' => $reader->id,
                'chapter_id' => $lastChapter->id,
                'content' => $this->commentFor($work, $lastChapter->chapter_number, $offset),
                'created_at' => now()->subHours(12 + $offset),
                'updated_at' => now()->subHours(12 + $offset),
            ]);
        }
    }

    private function commentFor(Work $work, int $chapterNumber, int $offset): string
    {
        $templates = [
            "Chapter {$chapterNumber} dari {$work->title} enak diikuti. Vibenya dapet dan pengen lanjut ke bagian berikutnya.",
            "Author aslinya kerasa kuat di judul ini. Adaptasi katalog di Nokomi buat {$work->title} juga rapi banget.",
            "Progress chapter {$chapterNumber} bikin penasaran. Cocok buat lanjut baca maraton malam ini.",
        ];

        return $templates[$offset] ?? $templates[0];
    }

    private function buildNovelPreview(Work $work, string $chapterTitle, string $summary): string
    {
        return $work->title." - ".$chapterTitle."\n\n"
            ."Author asli: ".$work->original_author."\n\n"
            .$summary."\n\n"
            ."Halaman ini menampilkan ringkasan baca demo yang disusun untuk katalog Nokomi. "
            ."Karya aslinya tetap dimiliki oleh penulis dan pemegang hak terkait. "
            ."Tujuannya agar tampilan reader, bookmark, komentar, dan progress tetap terisi dengan data yang rapi.";
    }

    private function createComicPages(Work $work, Chapter $chapter, int $totalPages): void
    {
        for ($page = 1; $page <= $totalPages; $page++) {
            ChapterImage::query()->create([
                'chapter_id' => $chapter->id,
                'image_url' => $this->generateComicPage($work, $chapter, $page),
                'page_number' => $page,
            ]);
        }
    }

    private function generateCover(string $title, string $author, string $type): string
    {
        $slug = Str::slug($title);
        $path = "generated/real-catalog/covers/{$slug}.svg";
        $palette = $this->paletteFor($title, $type);
        $lines = $this->wrapTitle($title, 18, 3);
        $titleSvg = '';

        foreach ($lines as $index => $line) {
            $titleSvg .= '<text x="82" y="'.(360 + ($index * 84)).'" font-family="Georgia, serif" font-size="60" font-weight="700" fill="#f8fafc">'.$this->escape($line).'</text>';
        }

        $typeLabel = strtoupper($type);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="1200" viewBox="0 0 900 1200">
    <defs>
        <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="{$palette['bg1']}" />
            <stop offset="100%" stop-color="{$palette['bg2']}" />
        </linearGradient>
        <linearGradient id="accent" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="{$palette['accent']}" />
            <stop offset="100%" stop-color="{$palette['accent2']}" />
        </linearGradient>
    </defs>
    <rect width="900" height="1200" fill="url(#bg)" rx="32" />
    <rect x="48" y="48" width="804" height="1104" rx="28" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.10)" />
    <circle cx="720" cy="268" r="120" fill="rgba(255,255,255,0.08)" />
    <circle cx="676" cy="318" r="96" fill="url(#accent)" opacity="0.48" />
    <rect x="82" y="92" width="198" height="44" rx="22" fill="rgba(255,255,255,0.12)" />
    <text x="112" y="121" font-family="Arial, sans-serif" font-size="24" font-weight="700" fill="#f8fafc">{$typeLabel}</text>
    <text x="82" y="188" font-family="Arial, sans-serif" font-size="22" font-weight="700" fill="{$palette['soft']}">NOKOMI LIBRARY</text>
    {$titleSvg}
    <rect x="82" y="830" width="736" height="2" fill="rgba(255,255,255,0.14)" />
    <text x="82" y="904" font-family="Arial, sans-serif" font-size="24" font-weight="700" fill="#f8fafc">{$this->escape($author)}</text>
    <text x="82" y="948" font-family="Arial, sans-serif" font-size="18" fill="{$palette['soft']}">Author Asli</text>
    <rect x="82" y="1000" width="736" height="110" rx="22" fill="rgba(255,255,255,0.05)" />
    <text x="118" y="1060" font-family="Arial, sans-serif" font-size="20" font-weight="700" fill="{$palette['accent2']}">CATALOG EDITION</text>
    <text x="118" y="1094" font-family="Arial, sans-serif" font-size="17" fill="#e2e8f0">{$this->escape($title)} diarsipkan ulang untuk katalog visual Nokomi.</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    private function generateComicPage(Work $work, Chapter $chapter, int $page): string
    {
        $slug = Str::slug($work->title);
        $path = "generated/real-catalog/comic-pages/{$slug}/chapter-{$chapter->chapter_number}-page-{$page}.svg";
        $palette = $this->paletteFor($work->title.'-'.$page, 'comic');
        $headline = strtoupper(Str::limit($work->title, 18, ''));
        $chapterTitle = strtoupper($chapter->title ?: 'CHAPTER '.$chapter->chapter_number);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1080" height="1600" viewBox="0 0 1080 1600">
    <defs>
        <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="{$palette['bg1']}" />
            <stop offset="100%" stop-color="{$palette['bg2']}" />
        </linearGradient>
        <linearGradient id="panel" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="{$palette['accent']}" />
            <stop offset="100%" stop-color="{$palette['accent2']}" />
        </linearGradient>
    </defs>
    <rect width="1080" height="1600" fill="url(#bg)" />
    <rect x="34" y="34" width="1012" height="1532" rx="28" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.10)" />
    <text x="76" y="110" font-family="Arial, sans-serif" font-size="28" font-weight="700" fill="#f8fafc">{$this->escape($headline)}</text>
    <text x="76" y="148" font-family="Arial, sans-serif" font-size="18" fill="{$palette['soft']}">{$this->escape($work->original_author)} • {$this->escape($chapterTitle)}</text>
    <rect x="76" y="190" width="928" height="310" rx="24" fill="url(#panel)" opacity="0.26" />
    <rect x="76" y="534" width="450" height="408" rx="24" fill="rgba(255,255,255,0.05)" />
    <rect x="554" y="534" width="450" height="408" rx="24" fill="rgba(255,255,255,0.05)" />
    <rect x="76" y="974" width="928" height="508" rx="24" fill="rgba(255,255,255,0.05)" />
    <path d="M118 468 C248 336, 356 304, 500 248" stroke="{$palette['accent2']}" stroke-width="18" fill="none" stroke-linecap="round" />
    <path d="M584 898 C694 742, 808 660, 944 566" stroke="{$palette['accent']}" stroke-width="18" fill="none" stroke-linecap="round" />
    <path d="M154 1398 C286 1228, 506 1120, 906 1014" stroke="{$palette['accent2']}" stroke-width="24" fill="none" stroke-linecap="round" />
    <rect x="112" y="226" width="226" height="42" rx="21" fill="rgba(8,12,18,0.45)" />
    <rect x="112" y="560" width="224" height="42" rx="21" fill="rgba(8,12,18,0.45)" />
    <rect x="590" y="560" width="224" height="42" rx="21" fill="rgba(8,12,18,0.45)" />
    <text x="142" y="255" font-family="Arial, sans-serif" font-size="22" font-weight="700" fill="#ffffff">PAGE {$page}</text>
    <text x="142" y="589" font-family="Arial, sans-serif" font-size="20" font-weight="700" fill="#ffffff">VISUAL PANEL A</text>
    <text x="620" y="589" font-family="Arial, sans-serif" font-size="20" font-weight="700" fill="#ffffff">VISUAL PANEL B</text>
    <text x="78" y="1524" font-family="Arial, sans-serif" font-size="20" fill="{$palette['soft']}">Generated reader page for Nokomi catalog</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    private function paletteFor(string $title, string $type): array
    {
        $palettes = [
            ['bg1' => '#09131d', 'bg2' => '#1d3557', 'accent' => '#60a5fa', 'accent2' => '#38bdf8', 'soft' => '#bfdbfe'],
            ['bg1' => '#190d17', 'bg2' => '#4c1d3d', 'accent' => '#fb7185', 'accent2' => '#f59e0b', 'soft' => '#fecdd3'],
            ['bg1' => '#102218', 'bg2' => '#1f5b43', 'accent' => '#34d399', 'accent2' => '#facc15', 'soft' => '#bbf7d0'],
            ['bg1' => '#1d1232', 'bg2' => '#312e81', 'accent' => '#818cf8', 'accent2' => '#c084fc', 'soft' => '#ddd6fe'],
            ['bg1' => '#23150f', 'bg2' => '#6b3b1f', 'accent' => '#f59e0b', 'accent2' => '#fb7185', 'soft' => '#fde68a'],
        ];

        return $palettes[abs(crc32(Str::lower($title).'-'.$type)) % count($palettes)];
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

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
