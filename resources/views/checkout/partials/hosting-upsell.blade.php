@if($hostingServices->isNotEmpty())
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="text-lg font-semibold text-slate-900">Wilt u ook webhosting voor dit domein?</h2>
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($hostingServices as $hosting)
                @php
                    $hostingPrice = $hosting->prices->firstWhere('is_enabled', true) ?? $hosting->prices->first();
                @endphp
                <form action="{{ route('checkout.cart.add-hosting') }}" method="POST" class="flex flex-col rounded-xl border border-slate-200 p-4 hover:border-primary-400 transition-colors">
                    @csrf
                    <input type="hidden" name="service_slug" value="{{ $hosting->slug }}">
                    @if($hostingPrice)
                        <input type="hidden" name="service_price_id" value="{{ $hostingPrice->id }}">
                    @endif
                    <h3 class="font-heading font-bold text-slate-900">{{ $hosting->title }}</h3>
                    <p class="mt-1 flex-1 text-sm text-slate-600">{{ $hosting->short_description }}</p>
                    @if($hostingPrice)
                        <p class="mt-3 font-semibold text-slate-900">€ {{ number_format($hostingPrice->price, 2, ',', '.') }}<span class="text-xs font-normal text-slate-500">/{{ $hostingPrice->label }}</span></p>
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm mt-3 w-full justify-center">Kies {{ $hosting->title }}</button>
                </form>
            @endforeach
        </div>
    </section>
@endif
