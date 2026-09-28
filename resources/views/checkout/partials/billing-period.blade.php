@if($primaryPriceOptions->count() > 1)
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-900">Betaalperiode</h2>
        <p class="mt-1 text-sm text-slate-600">Kies de gewenste betaaltermijn voor {{ $primaryItem['service']->title }}.</p>

        <div class="mt-4 flex flex-wrap gap-3">
            @foreach($primaryPriceOptions as $price)
                @php
                    $isSelected = ($primaryItem['price_model']->id ?? null) === $price->id;
                @endphp
                <form action="{{ route('checkout.cart.set-price') }}" method="POST" class="contents">
                    @csrf
                    <input type="hidden" name="index" value="0">
                    <input type="hidden" name="service_price_id" value="{{ $price->id }}">
                    <button type="submit" class="min-w-[8rem] rounded-xl px-4 py-3 text-left text-sm transition {{ $isSelected ? 'bg-slate-900 text-white ring-1 ring-slate-900' : 'border border-slate-200 bg-white text-slate-700 hover:border-primary-400 hover:text-primary-700' }}">
                        <span class="block font-semibold">{{ $price->label }}</span>
                        <span class="block text-xs {{ $isSelected ? 'text-slate-300' : 'text-slate-500' }}">€ {{ number_format($price->price, 2, ',', '.') }}</span>
                    </button>
                </form>
            @endforeach
        </div>
    </section>
@elseif($primaryPriceOptions->count() === 1)
    <input type="hidden" name="service_price_id" form="checkout-form" value="{{ $primaryPriceOptions->first()->id }}">
@endif
