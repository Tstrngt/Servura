@extends('legal.layout')

@section('title', 'Misbruik melden - '.config('company.trade_name', 'Servura'))
@section('meta-description', 'Meld misbruik of illegale content die gebruik maakt van de diensten van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', 'misbruik melden, abuse, phishing, spam, malware, Servura')

@section('legal-title')
    Misbruik of illegale content melden
@endsection

@section('legal-meta')
    Meldingen worden vertrouwelijk behandeld
@endsection

@section('legal-form')
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-emerald-800 ring-1 ring-emerald-600/20">
            {{ session('success') }}
        </div>
    @else
        <form action="{{ route('legal.abuse.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 md:p-8 shadow-sm ring-1 ring-slate-200">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Naam</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input w-full" placeholder="Uw naam">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mailadres</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-input w-full" placeholder="uw@email.nl">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="domain" class="block text-sm font-medium text-slate-700 mb-1">Betreffend domein (optioneel)</label>
                    <input type="text" name="domain" id="domain" value="{{ old('domain') }}" class="form-input w-full" placeholder="voorbeeld.nl">
                    @error('domain')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="url" class="block text-sm font-medium text-slate-700 mb-1">Exacte URL</label>
                    <input type="url" name="url" id="url" value="{{ old('url') }}" required class="form-input w-full" placeholder="https://...">
                    @error('url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Type melding</label>
                <select name="category" id="category" required class="form-input w-full">
                    <option value="">Kies een type</option>
                    @foreach(App\Models\AbuseReport::CATEGORIES as $key => $label)
                        <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Omschrijving</label>
                <textarea name="description" id="description" rows="4" required class="form-input w-full" placeholder="Beschrijf wat u heeft geconstateerd...">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="reason" class="block text-sm font-medium text-slate-700 mb-1">Waarom is deze content of activiteit onrechtmatig of schadelijk?</label>
                <textarea name="reason" id="reason" rows="3" required class="form-input w-full" placeholder="Leg uit waarom u dit meldt...">{{ old('reason') }}</textarea>
                @error('reason')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="attachment" class="block text-sm font-medium text-slate-700 mb-1">Eventueel bewijs / bijlage (optioneel)</label>
                <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.gif,.pdf,.txt,.md" class="form-input w-full">
                <p class="mt-1 text-xs text-slate-500">Toegestaan: JPG, PNG, GIF, PDF, TXT, MD. Maximaal 10 MB.</p>
                @error('attachment')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="truth_declaration" value="1" {{ old('truth_declaration') ? 'checked' : '' }} required class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm text-slate-700">Ik verklaar dat deze melding naar waarheid is ingevuld.</span>
                </label>
                @error('truth_declaration')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn btn-primary w-full md:w-auto">Melding versturen</button>
        </form>
    @endif
@endsection
