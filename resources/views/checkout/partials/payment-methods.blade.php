@php
    $selectedMethod = old('mollie_method', $paymentMethods[0]['id'] ?? 'ideal');
@endphp

<fieldset class="mt-5">
    <legend class="mb-2 text-sm font-semibold text-white">Betaalmethode</legend>

    <div class="grid grid-cols-2 gap-3">
        @foreach($paymentMethods as $method)
            @php
                $isSelected = $selectedMethod === $method['id'];
            @endphp
            <label class="flex cursor-pointer items-center gap-3 rounded-lg p-3 text-sm transition {{ $isSelected ? 'bg-white/10 ring-1 ring-white/30' : 'bg-white/5 ring-1 ring-white/10 hover:bg-white/[0.08]' }}">
                <input type="radio" name="mollie_method" value="{{ $method['id'] }}" form="checkout-form" required {{ $isSelected ? 'checked' : '' }} class="border-slate-500 bg-slate-800 text-primary-500 focus:ring-primary-500">

                @if($method['image'])
                    <img src="{{ $method['image'] }}" alt="" class="h-5 w-auto shrink-0">
                @else
                    <span class="flex h-5 w-8 shrink-0 items-center justify-center text-xs font-bold text-slate-400">
                        {{ strtoupper(substr($method['id'], 0, 2)) }}
                    </span>
                @endif

                <span class="font-medium {{ $isSelected ? 'text-white' : 'text-slate-300' }}">{{ $method['name'] }}</span>
            </label>
        @endforeach
    </div>

    @error('mollie_method')
        <span class="mt-2 block text-sm text-red-300">{{ $message }}</span>
    @enderror
</fieldset>
