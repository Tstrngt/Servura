@php($captcha = app(\App\Services\CaptchaService::class))
@if($captcha->enabled())
    @once
        @if($captcha->provider() === 'turnstile')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @elseif($captcha->provider() === 'recaptcha_v3')
            <script src="https://www.google.com/recaptcha/api.js?render={{ $captcha->siteKey() }}" async defer></script>
        @else
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endif
    @endonce

    <div class="my-4">
        @if($captcha->provider() === 'turnstile')
            <div class="cf-turnstile" data-sitekey="{{ $captcha->siteKey() }}"></div>
        @elseif($captcha->provider() === 'recaptcha_v3')
            <input type="hidden" name="g-recaptcha-response" class="g-recaptcha-response">
            <script>
                (function () {
                    document.querySelectorAll('form').forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            const responseInput = form.querySelector('input.g-recaptcha-response');
                            if (!responseInput) return;

                            event.preventDefault();
                            if (typeof grecaptcha === 'undefined') {
                                return;
                            }

                            grecaptcha.ready(function () {
                                grecaptcha.execute('{{ $captcha->siteKey() }}', { action: 'submit' }).then(function (token) {
                                    responseInput.value = token;
                                    form.submit();
                                });
                            });
                        });
                    });
                })();
            </script>
            <p class="text-xs text-slate-500">Deze site wordt beschermd door reCAPTCHA v3. <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" class="underline">Privacybeleid Google</a> · <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" class="underline">Gebruiksvoorwaarden</a></p>
        @else
            <div class="g-recaptcha" data-sitekey="{{ $captcha->siteKey() }}"></div>
        @endif
        @error('captcha')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif
