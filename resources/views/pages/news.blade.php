@extends('layouts.user')

@section('content')
    <div class="page-header">
        <h1 class="page-title">News</h1>
        <p class="page-subtitle">Update singkat seputar fitur dan pengembangan Nokomi.</p>
    </div>

    <div style="display: grid; gap: 16px;">
        @foreach ($newsItems as $item)
            <article style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 24px;">
                <h2 style="margin-bottom: 10px; font-size: 22px;">{{ $item['title'] }}</h2>
                <p style="color: var(--text-secondary); line-height: 1.8;">{{ $item['summary'] }}</p>
            </article>
        @endforeach
    </div>
@endsection
