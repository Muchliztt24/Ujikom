<?php

if (!function_exists('genre_icon')) {
    function genre_icon(string $genreName, string $default = 'bi bi-book'): string
    {
        $icons = [
            'Action' => 'bi bi-shield-fill',
            'Adventure' => 'bi bi-compass-fill',
            'Fantasy' => 'bi bi-stars',
            'Romance' => 'bi bi-heart-fill',
            'Drama' => 'bi bi-mask',
            'Comedy' => 'bi bi-emoji-laughing-fill',
            'Horror' => 'bi bi-moon-stars-fill',
            'Mystery' => 'bi bi-search',
            'Slice of Life' => 'bi bi-flower1',
            'Sci-Fi' => 'bi bi-rocket-takeoff-fill',
            'Supernatural' => 'bi bi-magic',
            'Psychological' => 'bi bi-activity',
            'Thriller' => 'bi bi-lightning-charge-fill',
            'Historical' => 'bi bi-bank',
            'Isekai' => 'bi bi-globe-central-south-asia',
            'Dark Fantasy' => 'bi bi-moon-fill',
            'Post-Apocalyptic' => 'bi bi-radioactive',
            'Cyberpunk' => 'bi bi-cpu-fill',
            'Steampunk' => 'bi bi-gear-fill',
            'Martial Arts' => 'bi bi-trophy-fill',
            'Wuxia' => 'bi bi-slash-lg',
            'Xianxia' => 'bi bi-cloud-haze2-fill',
            'Mecha' => 'bi bi-robot',
            'School Life' => 'bi bi-building',
            'Reverse Harem' => 'bi bi-gem',
        ];

        return $icons[$genreName] ?? $default;
    }
}
