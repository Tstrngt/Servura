@if($mollieProfileId)
    <div x-show="selectedMethod === 'creditcard'" x-transition x-init="window.initMollieComponents && window.initMollieComponents()" class="mt-5 space-y-3">
        <p class="text-sm font-semibold text-white">Creditcard-gegevens</p>

        <div class="space-y-2">
            <div>
                <label for="card-holder" class="block text-xs font-medium text-slate-300">Kaarthouder</label>
                <div id="card-holder" class="mt-1 min-h-[42px] rounded-lg border border-slate-500 bg-white p-2"></div>
            </div>
            <div>
                <label for="card-number" class="block text-xs font-medium text-slate-300">Kaartnummer</label>
                <div id="card-number" class="mt-1 min-h-[42px] rounded-lg border border-slate-500 bg-white p-2"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="expiry-date" class="block text-xs font-medium text-slate-300">Vervaldatum</label>
                    <div id="expiry-date" class="mt-1 min-h-[42px] rounded-lg border border-slate-500 bg-white p-2"></div>
                </div>
                <div>
                    <label for="verification-code" class="block text-xs font-medium text-slate-300">CVC</label>
                    <div id="verification-code" class="mt-1 min-h-[42px] rounded-lg border border-slate-500 bg-white p-2"></div>
                </div>
            </div>
        </div>

        <p x-show="cardError" x-text="cardError" class="text-sm text-red-300"></p>
    </div>

    <script src="https://js.mollie.com/v1/mollie.js"></script>
    <script>
        window.initMollieComponents = function () {
            if (window.mollieInitialized) return;

            const profileId = @js($mollieProfileId);
            const testMode = @js($mollieTestMode ?? true);

            const mollie = Mollie(profileId, {
                testMode: testMode,
                locale: 'nl_NL',
            });

            mollie.createComponent('cardHolder').mount('#card-holder');
            mollie.createComponent('cardNumber').mount('#card-number');
            mollie.createComponent('expiryDate').mount('#expiry-date');
            mollie.createComponent('verificationCode').mount('#verification-code');

            window.mollie = mollie;
            window.mollieInitialized = true;
        };
    </script>
@else
    <div x-show="selectedMethod === 'creditcard'" x-transition class="mt-4 rounded-lg bg-amber-400/10 p-3 text-sm text-amber-200 ring-1 ring-amber-400/20">
        Creditcard-gegevens kunnen hier nog niet inline worden ingevoerd omdat geen Mollie-profiel is geconfigureerd. Creditcard wordt doorgestuurd naar de beveiligde Mollie-omgeving.
    </div>
@endif
