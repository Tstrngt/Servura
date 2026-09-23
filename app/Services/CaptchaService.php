<?php

namespace App\Services;

use App\Models\BillingSetting;
use Illuminate\Support\Facades\Http;

class CaptchaService
{
    public const PROVIDERS = [
        'recaptcha_v2' => 'Google reCAPTCHA v2',
        'turnstile' => 'Cloudflare Turnstile',
    ];

    public function enabled(): bool
    {
        return BillingSetting::boolean('captcha_enabled')
            && $this->siteKey() !== ''
            && $this->secretKey() !== '';
    }

    public function provider(): string
    {
        $provider = BillingSetting::valueFor('captcha_provider', 'recaptcha_v2');

        return array_key_exists($provider, self::PROVIDERS) ? $provider : 'recaptcha_v2';
    }

    public function siteKey(): string
    {
        return BillingSetting::valueFor('captcha_site_key', '');
    }

    public function secretKey(): string
    {
        return BillingSetting::encryptedValueFor('captcha_secret_key', '');
    }

    /**
     * Verify a captcha token from a submitted form.
     */
    public function verify(?string $token, ?string $ip = null): bool
    {
        if (! $this->enabled()) {
            return true;
        }

        if (! $token) {
            return false;
        }

        $endpoint = $this->provider() === 'turnstile'
            ? 'https://challenges.cloudflare.com/turnstile/v0/siteverify'
            : 'https://www.google.com/recaptcha/api/siteverify';

        try {
            $response = Http::asForm()->timeout(10)->post($endpoint, array_filter([
                'secret' => $this->secretKey(),
                'response' => $token,
                'remoteip' => $ip,
            ]));

            return (bool) ($response->json('success') ?? false);
        } catch (\Throwable $e) {
            report($e);

            // Bij een onbereikbare captcha-dienst het formulier niet blokkeren.
            return true;
        }
    }

    /**
     * Token field name sent by the widget.
     */
    public function tokenField(): string
    {
        return $this->provider() === 'turnstile' ? 'cf-turnstile-response' : 'g-recaptcha-response';
    }
}
