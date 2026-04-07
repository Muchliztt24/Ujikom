<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\ReadingHistory;
use App\Models\User;
use App\Models\Work;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role?->name;

        if ($role === 'admin') {
            $statusCounts = [
                'approved' => Work::where('status', 'approved')->count(),
                'pending' => Work::where('status', 'pending')->count(),
                'draft' => Work::where('status', 'draft')->count(),
            ];

            $typeCounts = [
                'comic' => Work::where('type', 'comic')->count(),
                'novel' => Work::where('type', 'novel')->count(),
            ];

            $topGenres = Genre::withCount('works')
                ->orderByDesc('works_count')
                ->take(6)
                ->get();

            $topWorks = Work::with(['user'])
                ->withCount(['chapters', 'bookmarks'])
                ->withCount([
                    'readingHistories as readers_count' => fn ($query) => $query->selectRaw('count(distinct user_id)'),
                ])
                ->where('status', 'approved')
                ->orderByDesc('readers_count')
                ->take(6)
                ->get()
                ->map(function (Work $work) {
                    $commentCount = Comment::whereHas('chapter', fn ($query) => $query->where('work_id', $work->id))->count();

                    return [
                        'title' => $work->title,
                        'author' => $work->display_author,
                        'uploader' => $work->user?->name,
                        'bookmarks' => $work->bookmarks_count,
                        'chapters' => $work->chapters_count,
                        'readers' => $work->readers_count,
                        'comments' => $commentCount,
                    ];
                });

            $data = [
                'summary' => [
                    'works' => Work::count(),
                    'users' => User::count(),
                    'genres' => Genre::count(),
                    'chapters' => Chapter::count(),
                    'images' => ChapterImage::count(),
                    'comments' => Comment::count(),
                    'bookmarks' => Bookmark::count(),
                    'readers' => ReadingHistory::distinct('user_id')->count('user_id'),
                ],
                'statusCounts' => $statusCounts,
                'typeCounts' => $typeCounts,
                'topGenres' => $topGenres,
                'topWorks' => $topWorks,
            ];
        }

        if ($role === 'uploader') {
            $myWorks = $user->works()
                ->with(['genres'])
                ->withCount(['chapters', 'bookmarks'])
                ->latest()
                ->get();

            $workIds = $myWorks->pluck('id');
            $chapterIds = Chapter::whereIn('work_id', $workIds)->pluck('id');

            $commentsByWork = Comment::query()
                ->selectRaw('chapters.work_id, count(comments.id) as total_comments')
                ->join('chapters', 'chapters.id', '=', 'comments.chapter_id')
                ->whereIn('chapters.work_id', $workIds)
                ->groupBy('chapters.work_id')
                ->pluck('total_comments', 'chapters.work_id');

            $readersByWork = ReadingHistory::query()
                ->selectRaw('work_id, count(distinct user_id) as total_readers')
                ->whereIn('work_id', $workIds)
                ->groupBy('work_id')
                ->pluck('total_readers', 'work_id');

            $topWorks = $myWorks->map(function (Work $work) use ($commentsByWork, $readersByWork) {
                return [
                    'title' => $work->title,
                    'author' => $work->display_author,
                    'status' => $work->status,
                    'type' => $work->type,
                    'chapters' => $work->chapters_count,
                    'bookmarks' => $work->bookmarks_count,
                    'comments' => (int) ($commentsByWork[$work->id] ?? 0),
                    'readers' => (int) ($readersByWork[$work->id] ?? 0),
                ];
            })->sortByDesc('readers')->take(6)->values();

            $data = [
                'myWorks' => $myWorks,
                'summary' => [
                    'works' => $myWorks->count(),
                    'chapters' => Chapter::whereIn('work_id', $workIds)->count(),
                    'images' => ChapterImage::whereIn('chapter_id', $chapterIds)->count(),
                    'bookmarks' => Bookmark::whereIn('work_id', $workIds)->count(),
                    'comments' => Comment::whereIn('chapter_id', $chapterIds)->count(),
                    'readers' => ReadingHistory::whereIn('work_id', $workIds)->distinct('user_id')->count('user_id'),
                ],
                'statusCounts' => [
                    'approved' => $myWorks->where('status', 'approved')->count(),
                    'pending' => $myWorks->where('status', 'pending')->count(),
                    'draft' => $myWorks->where('status', 'draft')->count(),
                ],
                'typeCounts' => [
                    'comic' => $myWorks->where('type', 'comic')->count(),
                    'novel' => $myWorks->where('type', 'novel')->count(),
                ],
                'topWorks' => $topWorks,
            ];
        }

        return view('dashboard', compact('role', 'data'));
    }
}
