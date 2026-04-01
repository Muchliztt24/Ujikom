<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\ReadingHistory;
use App\Models\Work;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function faq()
    {
        $items = [
            [
                'question' => 'Bagaimana cara mulai membaca?',
                'answer' => 'Buka detail karya, lalu klik tombol "Mulai Baca" atau pilih chapter yang ingin dibaca.',
            ],
            [
                'question' => 'Apa perbedaan novel dan comic?',
                'answer' => 'Novel ditampilkan sebagai teks chapter, sedangkan comic ditampilkan sebagai rangkaian gambar per chapter.',
            ],
            [
                'question' => 'Bagaimana cara upload karya?',
                'answer' => 'Masuk sebagai uploader, buka dashboard, lalu pilih menu kelola karya untuk membuat work dan chapter.',
            ],
        ];

        return view('pages.faq', compact('items'));
    }

    public function news()
    {
        $newsItems = [
            ['title' => 'Pilihan bacaan terbaru setiap hari', 'summary' => 'Temukan karya baru, chapter terbaru, dan judul populer langsung dari beranda Nokomi.'],
            ['title' => 'Ruang baca yang makin nyaman', 'summary' => 'Nikmati tampilan novel dan komik yang dirancang untuk fokus, ringan, dan nyaman di layar mana pun.'],
            ['title' => 'Akun dan library dalam satu tempat', 'summary' => 'Progress baca, bookmark, dan aktivitas akun tersusun rapi supaya lebih mudah dilanjutkan kapan saja.'],
        ];

        return view('pages.news', compact('newsItems'));
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->get('q'));

        $works = Work::with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.search', compact('keyword', 'works'));
    }

    public function notifications()
    {
        $latestReleases = Work::with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $chapterUpdates = collect();
        $creatorFeed = collect();

        if (auth()->check()) {
            $user = auth()->user();

            $bookmarkedWorkIds = Bookmark::query()
                ->where('user_id', $user->id)
                ->pluck('work_id');

            if ($bookmarkedWorkIds->isNotEmpty()) {
                $chapterUpdates = Chapter::with(['work.user'])
                    ->whereIn('work_id', $bookmarkedWorkIds)
                    ->latest()
                    ->take(8)
                    ->get();
            }

            if (in_array($user->role?->name, ['admin', 'uploader'], true)) {
                $creatorFeed = Comment::with(['user', 'chapter.work'])
                    ->whereHas('chapter.work', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->latest()
                    ->take(6)
                    ->get();
            }
        }

        return view('pages.notifications', compact('latestReleases', 'chapterUpdates', 'creatorFeed'));
    }

    public function collection()
    {
        $featuredWorks = Work::with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->latest()
            ->take(12)
            ->get();

        $highlightGenres = Genre::query()
            ->withCount('works')
            ->orderByDesc('works_count')
            ->take(6)
            ->get();

        if (! auth()->check()) {
            $novels = $featuredWorks->where('type', 'novel')->take(6)->values();
            $comics = $featuredWorks->where('type', 'comic')->take(6)->values();

            return view('pages.collection', [
                'isGuest' => true,
                'bookmarkedWorks' => collect(),
                'recentHistory' => collect(),
                'recommendedWorks' => $featuredWorks->take(6)->values(),
                'novels' => $novels,
                'comics' => $comics,
                'highlightGenres' => $highlightGenres,
            ]);
        }

        $bookmarks = Bookmark::with(['work.genres', 'work.user', 'work.chapters'])
            ->where('user_id', auth()->id())
            ->get();

        $bookmarkedWorks = $bookmarks
            ->pluck('work')
            ->filter()
            ->unique('id')
            ->values();

        $recentHistory = ReadingHistory::with(['work.genres', 'work.user', 'chapter'])
            ->where('user_id', auth()->id())
            ->orderByDesc('last_read_at')
            ->take(6)
            ->get();

        $preferredGenreIds = $bookmarkedWorks
            ->flatMap(fn ($work) => $work->genres->pluck('id'))
            ->unique()
            ->values();

        $recommendedWorks = Work::with(['user', 'genres', 'chapters'])
            ->where('status', 'approved')
            ->when($bookmarkedWorks->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $bookmarkedWorks->pluck('id')))
            ->when($preferredGenreIds->isNotEmpty(), function ($query) use ($preferredGenreIds) {
                $query->whereHas('genres', function ($genreQuery) use ($preferredGenreIds) {
                    $genreQuery->whereIn('genres.id', $preferredGenreIds);
                });
            })
            ->latest()
            ->take(8)
            ->get();

        $novels = $bookmarkedWorks->where('type', 'novel')->values();
        $comics = $bookmarkedWorks->where('type', 'comic')->values();

        return view('pages.collection', [
            'isGuest' => false,
            'bookmarkedWorks' => $bookmarkedWorks,
            'recentHistory' => $recentHistory,
            'recommendedWorks' => $recommendedWorks,
            'novels' => $novels,
            'comics' => $comics,
            'highlightGenres' => $highlightGenres,
        ]);
    }

    public function history()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $histories = ReadingHistory::with(['work.user', 'chapter'])
            ->where('user_id', auth()->id())
            ->orderByDesc('last_read_at')
            ->get();

        return view('pages.history', compact('histories'));
    }
}
