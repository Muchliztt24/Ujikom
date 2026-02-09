<?php

if (!function_exists('genre_icon')) {
    /**
     * Mengembalikan emoji/icon yang sesuai untuk nama genre
     *
     * @param string $genreName Nama genre dari database (case-sensitive)
     * @param string $default Icon default kalau tidak ditemukan
     * @return string
     */
    function genre_icon(string $genreName, string $default = '📖'): string
    {
        $icons = [
            'Action'          => '⚔️',
            'Adventure'       => '🗺️',
            'Fantasy'         => '🔮',
            'Romance'         => '💕',
            'Drama'           => '🎭',
            'Comedy'          => '😂',
            'Horror'          => '👻',
            'Mystery'         => '🔍',
            'Slice of Life'   => '🌸',
            'Sci-Fi'          => '🚀',
            'Supernatural'    => '🧙‍♂️',
            'Psychological'   => '🧠',
            'Thriller'        => '😱',
            'Historical'      => '🏯',
            'Isekai'          => '🌌',
            'Dark Fantasy'    => '🖤',
            'Post-Apocalyptic'=> '☢️',
            'Cyberpunk'       => '💾',
            'Steampunk'       => '⚙️',
            'Martial Arts'    => '🥋',
            'Wuxia'           => '🗡️',
            'Xianxia'         => '🪶',
            'Mecha'           => '🤖',
            'School Life'     => '🏫',
            'Reverse Harem'   => '💐',
            // Tambah genre baru di sini kalau nanti ada
        ];

        return $icons[$genreName] ?? $default;
    }
}