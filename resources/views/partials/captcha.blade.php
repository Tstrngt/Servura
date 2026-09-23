@php($captcha = app(\App\Services\CaptchaService::class))
@if($captcha->enabled())
    @once
        @if($captcha->provider() === 'turnstile')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @else
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endif
    @endonce
    <div class="my-4">
        @if($captcha->provider() === 'turnstile')
            <div class="cf-turnstile" data-sitekey="{{ $captcha->siteKey() }}"></div>
        @else
            <div class="g-recaptcha" data-sitekey="{{ $captcha->siteKey() }}"></div>
        @endif
        @error('captcha')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif
