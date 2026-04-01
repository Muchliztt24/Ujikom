@extends('layouts.user')

@section('content')
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 24px; padding: 40px; text-align: center;">
        <div style="font-size: 64px; margin-bottom: 16px; color: var(--light-green);">
            <i class="{{ $icon }}"></i>
        </div>
        <h1 style="font-family: 'Crimson Pro', serif; font-size: 40px; margin-bottom: 12px;">{{ $title }}</h1>
        <p style="max-width: 700px; margin: 0 auto; color: var(--text-secondary); line-height: 1.8;">{{ $subtitle }}</p>
    </div>
@endsection
