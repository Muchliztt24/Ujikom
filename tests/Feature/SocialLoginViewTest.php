<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialLoginViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_hides_social_button_when_provider_is_not_configured(): void
    {
        config([
            'services.google.client_id' => null,
            'services.google.client_secret' => null,
            'services.google.redirect' => null,
        ]);

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertDontSee('auth/google/redirect', false);
    }

    public function test_login_page_shows_social_button_when_provider_is_configured(): void
    {
        config([
            'services.google.client_id' => 'google-client-id',
            'services.google.client_secret' => 'google-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee(route('auth.social.redirect', 'google'), false);
    }
}
