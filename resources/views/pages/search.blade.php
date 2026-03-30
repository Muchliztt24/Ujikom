@extends('layouts.user')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Search</h1>
        <p class="page-subtitle">Cari judul atau deskripsi karya yang sudah dipublikasikan.</p>
    </div>

    <form action="{{ route('pages.search') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 28px;">
        <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari judul atau kata kunci..."
            style="flex: 1; min-width: 260px; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary);">
        <button type="submit"
            style="padding: 14px 22px; border: none; border-radius: 12px; background: linear-gradient(135deg, var(--primary-green), var(--light-green)); color: white; font-weight: 700; cursor: pointer;">
            Cari
        </button>
    </form>

    @if ($works->count() > 0)
        <div class="works-grid" style="margin-top: 0;">
            @foreach ($works as $work)
                <a href="{{ route('works.public.show', $work) }}" class="work-card" style="text-decoration: none;">
                    @if ($work->cover)
                        <img src="{{ asset('storage/' . $work->cover) }}" alt="{{ $work->title }}" class="work-cover">
                    @else
                        <div class="work-cover" style="display: flex; align-items: center; justify-content: center; font-size: 56px;">
                            {{ $work->type === 'novel' ? '📖' : '🎨' }}
                        </div>
                    @endif
                    <div class="work-info">
                        <div class="work-title">{{ $work->title }}</div>
                        <span class="work-type">{{ $work->type }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($works->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-meta">
                    Showing {{ $works->firstItem() }} to {{ $works->lastItem() }} of {{ $works->total() }} results
                </div>
                <div class="pagination-links">
                    <a href="{{ $works->previousPageUrl() ?: '#' }}" class="pagination-link {{ $works->onFirstPage() ? 'disabled' : '' }}">
                        &laquo; Previous
                    </a>
                    <a href="{{ $works->nextPageUrl() ?: '#' }}" class="pagination-link {{ $works->hasMorePages() ? '' : 'disabled' }}">
                        Next &raquo;
                    </a>
                </div>
            </div>
        @endif
    @else
        <div style="background: var(--bg-card); border: 1px dashed var(--border-color); border-radius: 18px; padding: 40px; text-align: center; color: var(--text-secondary);">
            Tidak ada karya yang cocok dengan pencarian Anda.
        </div>
    @endif
@endsection
