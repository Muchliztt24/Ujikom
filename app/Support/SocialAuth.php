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
            default => throw new \InvalidArgumentException("Unsupported provider [{$provider}]."),
        };
    }
}
