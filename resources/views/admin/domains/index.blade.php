@extends('layouts.app')

@section('title', 'Domeinen - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="bg-gray-50 min-h-screen lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-4 sm:px-6 lg:px-8">
        <div class="py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Domeinregistraties</h1>
                <p class="mt-1 text-sm text-gray-600">Overzicht van alle geregistreerde en geboekte domeinen.</p>
            </div>
            <a href="{{ route('admin.settings.domains.tlds.index') }}" class="btn btn-outline">Domein TLD's</a>
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Domein</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Klant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Prijs</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Verlengprijs</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Geregistreerd</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Verloopt</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Actie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($domains as $domain)
                            @php $label = $domain->statusLabel; @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $domain->domain_name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $domain->type === 'transfer' ? 'Verhuizing' : 'Registratie' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $domain->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full bg-{{ $label['color'] }}-50 px-2 py-0.5 text-xs font-medium text-{{ $label['color'] }}-700 ring-1 ring-inset ring-{{ $label['color'] }}-600/20">{{ $label['text'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-slate-900">
                                    @if($domain->type === 'transfer')
                                        {{ $domain->transfer_price ? '€ '.number_format((float) $domain->transfer_price, 2, ',', '.') : '-' }}
                                    @else
                                        € {{ number_format((float) $domain->registration_price, 2, ',', '.') }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-slate-900">{{ $domain->renewal_price ? '€ '.number_format((float) $domain->renewal_price, 2, ',', '.') : '-' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $domain->registered_at?->format('d-m-Y') ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $domain->expires_at?->format('d-m-Y') ?? '-' }}</td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <a href="{{ route('admin.domains.show', $domain) }}" class="text-primary-600 hover:text-primary-800 font-medium">Details</a>
                                    @if(in_array($domain->status, [\App\Models\DomainRegistration::STATUS_PENDING, \App\Models\DomainRegistration::STATUS_AWAITING_PAYMENT, \App\Models\DomainRegistration::STATUS_REGISTRATION_FAILED, \App\Models\DomainRegistration::STATUS_TRANSFER_PENDING, \App\Models\DomainRegistration::STATUS_TRANSFER_FAILED, \App\Models\DomainRegistration::STATUS_CANCELLED], true))
                                        <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST" class="inline-block ml-3" onsubmit="return confirm('Weet u zeker dat u dit niet-betaalde domein wilt verwijderen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Verwijderen</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-sm text-slate-500">Nog geen domeinregistraties gevonden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($domains->hasPages())
                <div class="px-4 py-3 border-t border-slate-200">{{ $domains->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
