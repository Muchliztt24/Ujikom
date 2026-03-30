@extends('layouts.user')

@section('content')
    <div class="page-header">
        <h1 class="page-title">FAQ</h1>
        <p class="page-subtitle">Jawaban singkat untuk pertanyaan yang paling sering muncul.</p>
    </div>

    <div style="display: grid; gap: 16px;">
        @foreach ($items as $item)
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 22px;">
                <h3 style="margin-bottom: 8px; font-size: 20px;">{{ $item['question'] }}</h3>
                <p style="color: var(--text-secondary); line-height: 1.8;">{{ $item['answer'] }}</p>
            </div>
        @endforeach
    </div>
@endsection
