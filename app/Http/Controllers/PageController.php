<?php

namespace App\Http\Controllers;

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
            ['title' => 'Reader comic dan novel sudah aktif', 'summary' => 'Sekarang pembaca bisa langsung masuk ke chapter pertama dari halaman detail karya.'],
            ['title' => 'Profil pengguna sudah bisa diedit', 'summary' => 'Nama, email, dan password sekarang dapat diperbarui lewat menu profil.'],
            ['title' => 'Menu navigasi dirapikan', 'summary' => 'Menu yang belum tersedia sekarang sudah diberi halaman agar tidak ada tautan kosong.'],
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
        return view('pages.placeholder', [
            'title' => 'Notifikasi',
            'subtitle' => 'Pusat notifikasi akan menampilkan update karya, approval, dan aktivitas akun di sini.',
            'icon' => '??',
        ]);
    }

    public function collection()
    {
        return view('pages.placeholder', [
            'title' => 'Collection',
            'subtitle' => 'Halaman koleksi disiapkan untuk menampung daftar karya favorit dan karya yang sedang diikuti.',
            'icon' => '??',
        ]);
    }

    public function history()
    {
        if (!auth()->check()) {
            return view('pages.placeholder', [
                'title' => 'Riwayat Baca',
                'subtitle' => 'Masuk dulu untuk melihat progres dan riwayat baca Anda.',
                'icon' => '??',
            ]);
        }

        $histories = ReadingHistory::with(['work.user', 'chapter'])
            ->where('user_id', auth()->id())
            ->orderByDesc('last_read_at')
            ->get();

        return view('pages.history', compact('histories'));
    }
}
