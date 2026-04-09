<?php

namespace App\Support;

class SocialAuth
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function providers(): array
    {
        return [
            [
                'name' => 'google',
                'label' => 'Google',
                'icon' => 'bi-google',
                'class' => 'google',
            ],
            [
                'name' => 'facebook',
                'label' => 'Facebook',
                'icon' => 'bi-facebook',
                'class' => 'facebook',
            ],
            [
                'name' => 'x',
                'label' => 'X',
                'icon' => 'bi-twitter-x',
                'class' => 'x',
            ],
            [
                'name' => 'discord',
                'label' => 'Discord',
                'icon' => 'bi-discord',
                'class' => 'discord',
            ],
            [
                'name' => 'github',
                'label' => 'GitHub',
                'icon' => 'bi-github',
                'class' => 'github',
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_column(static::providers(), 'name');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function providersForView(string $routeName): array
    {
        return array_map(function (array $provider) use ($routeName) {
            $provider['enabled'] = static::isConfigured($provider['name']);
            $provider['url'] = $provider['enabled']
                ? route($routeName, $provider['name'])
                : null;

            return $provider;
        }, static::providers());
    }

    public static function isConfigured(string $provider): bool
    {
        return filled(config("services.{$provider}.client_id"))
            && filled(config("services.{$provider}.client_secret"))
            && filled(config("services.{$provider}.redirect"));
    }

    public static function providerColumn(string $provider): string
    {
        return match ($provider) {
            'google' => 'google_id',
            'facebook' => 'facebook_id',
            'x' => 'x_id',
            'discord' => 'discord_id',
            'github' => 'github_id',
            default => throw new \InvalidArgumentException("Unsupported provider [{$provider}]."),
        };
    }
}
