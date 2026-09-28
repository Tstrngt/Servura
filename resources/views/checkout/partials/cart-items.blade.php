<section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    <h2 class="text-lg font-semibold text-slate-900">Gekozen producten</h2>
    <div class="mt-5 space-y-4">
        @forelse($resolved['items'] as $index => $item)
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 rounded-xl border border-slate-200 p-4">
                <div>
                    <h3 class="font-heading font-bold text-slate-900">{{ $item['service']->title }}</h3>
                    @if($item['domain'])
                        <p class="text-sm text-slate-600">{{ $item['domain'] }}</p>
                    @endif
                    @if($item['service']->fulfillment_type === 'domain')
                        <p class="text-xs text-slate-500">
                            <a href="{{ route('domains.checker') }}" class="text-primary-700 underline hover:text-primary-900">Wijzigen</a>
                        </p>
                    @elseif($item['domain_mode'] === 'register')
                        <p class="text-xs text-slate-500">Nieuw domein registreren</p>
                    @elseif($item['domain_mode'] === 'transfer')
                        <p class="text-xs text-slate-500">Domein verhuizen naar Servura</p>
                    @elseif($item['domain_mode'] === 'existing')
                        <p class="text-xs text-slate-500">Bestaand domein gebruiken</p>
                    @endif
                    @error("cart.{$index}")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    @error("cart.{$index}.domain")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    @error("cart.{$index}.auth_code")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="text-right">
                    <p class="font-semibold text-slate-900">€ {{ number_format($item['price'], 2, ',', '.') }}</p>
                    @if(($item['domain_price'] ?? 0) > 0)
                        <p class="text-sm text-slate-600">+ € {{ number_format($item['domain_price'], 2, ',', '.') }} domein</p>
                    @endif
                    @if(count($resolved['items']) > 1)
                        <form action="{{ route('checkout.cart.remove') }}" method="POST" class="mt-2 inline-block">
                            @csrf
                            <input type="hidden" name="index" value="{{ $index }}">
                            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Verwijderen</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-slate-600">Er zijn geen producten in uw winkelmandje.</p>
        @endforelse
    </div>
</section>
