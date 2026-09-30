@extends('layouts.app')
@section('title', 'Medewerkers - Servura Admin')
@section('content')
@include('admin.partials.sidebar')
<div class="min-h-screen bg-slate-50 lg:pl-64"><main class="mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
<h1 class="text-2xl font-bold text-slate-900">Medewerkers</h1><p class="mt-1 text-sm text-slate-600">Beheer toegang tot het adminpaneel.</p><div class="mt-6">@include('admin.partials.settings-nav')</div>
@if(session('success'))<div class="mb-6 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 ring-1 ring-emerald-200">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-6 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

@php
$labels = [
    'dashboard.view' => 'Dashboard bekijken',
    'customers.view' => 'Klanten bekijken',
    'customers.edit' => 'Klanten wijzigen',
    'customers.delete' => 'Klanten verwijderen',
    'orders.view' => 'Bestellingen bekijken',
    'orders.edit' => 'Bestellingen wijzigen',
    'payments.manage' => 'Betalingen beheren',
    'refunds.process' => 'Refunds uitvoeren',
    'domains.view' => 'Domeinen bekijken',
    'domains.edit' => 'Domeinen wijzigen',
    'domains.dns.manage' => 'DNS beheren',
    'domains.nameservers.edit' => 'Nameservers wijzigen',
    'domains.holder.edit' => 'Houdergegevens wijzigen',
    'domains.authcode.view' => 'Verhuistoken bekijken',
    'domains.cancel' => 'Domein opzeggen',
    'hosting.view' => 'Hosting bekijken',
    'hosting.edit' => 'Hosting wijzigen',
    'hosting.provision' => 'Hosting provisioning uitvoeren',
    'hosting.directadmin.login' => 'DirectAdmin openen',
    'hosting.terminate' => 'Hosting beëindigen',
    'legal.view' => 'Juridische documenten bekijken',
    'legal.edit' => 'Juridische documenten wijzigen',
    'legal.publish' => 'Nieuwe versie publiceren',
    'settings.view' => 'Algemene instellingen bekijken',
    'settings.edit' => 'Algemene instellingen wijzigen',
    'settings.email.edit' => 'E-mailinstellingen wijzigen',
    'settings.integrations.view' => 'Integraties bekijken',
    'settings.integrations.edit' => 'Integraties wijzigen',
    'mollie.settings.edit' => 'Mollie-instellingen wijzigen',
    'transip.settings.edit' => 'TransIP-instellingen wijzigen',
    'api.credentials.edit' => 'API credentials wijzigen',
    'server.settings.edit' => 'DirectAdmin/server-instellingen wijzigen',
    'staff.view' => 'Medewerkers bekijken',
    'staff.create' => 'Medewerkers aanmaken',
    'staff.edit' => 'Medewerkers wijzigen',
    'staff.permissions.manage' => 'Rechten beheren',
    'staff.delete' => 'Medewerkers verwijderen',
];
@endphp

<div class="grid gap-6 xl:grid-cols-[24rem_1fr]">
<form method="POST" action="{{ route('admin.settings.staff.store') }}" class="h-fit rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">@csrf<h2 class="font-semibold text-slate-900">Account toevoegen</h2>
<div class="mt-5 space-y-4"><div><label class="form-label">Naam</label><input name="name" value="{{ old('name') }}" class="form-input mt-1 w-full" required></div><div><label class="form-label">E-mailadres</label><input type="email" name="email" value="{{ old('email') }}" class="form-input mt-1 w-full" required></div>
<div><label class="form-label">Rol</label><select name="spatie_role" class="form-input mt-1 w-full" required>
    @foreach($roles as $role)<option value="{{ $role->name }}" @selected(old('spatie_role') === $role->name)>{{ ucfirst($role->name) }}</option>@endforeach
</select></div>
<div><label class="form-label">Tijdelijk wachtwoord</label><input type="password" name="password" class="form-input mt-1 w-full" minlength="12" required></div><div><label class="form-label">Bevestig wachtwoord</label><input type="password" name="password_confirmation" class="form-input mt-1 w-full" required></div></div>
<button class="btn btn-primary mt-5 w-full" @cannot('staff.create') disabled @endcannot>Account aanmaken</button>
</form>

<div class="space-y-4">@foreach($staff as $member)
@php $memberPerms = $member->permissions->pluck('name')->toArray(); $memberRoles = $member->getRoleNames()->toArray(); @endphp
<form method="POST" action="{{ route('admin.settings.staff.update', $member) }}" class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">@csrf
<div class="flex flex-col gap-4 lg:flex-row lg:items-end"><div class="grid flex-1 gap-3 sm:grid-cols-2 lg:grid-cols-4"><div><label class="form-label">Naam</label><input name="name" value="{{ $member->name }}" class="form-input mt-1 w-full" required></div><div><label class="form-label">E-mail</label><input type="email" name="email" value="{{ $member->email }}" class="form-input mt-1 w-full" required></div><div><label class="form-label">Rol</label><select name="spatie_role" class="form-input mt-1 w-full" {{ $member->isOwner() ? 'disabled' : '' }}>
    @foreach($roles as $role)<option value="{{ $role->name }}" @selected(in_array($role->name, $memberRoles) || ($member->isOwner() && $role->name === 'owner'))>{{ ucfirst($role->name) }}</option>@endforeach
</select></div><div><label class="form-label">Laatste login</label><div class="mt-1 py-2.5 text-sm text-slate-600">{{ $member->last_login_at?->format('d-m-Y H:i') ?? 'Nog nooit' }}</div></div></div>
<div class="flex items-center gap-2">@if(!$member->isOwner())<label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="is_active" value="1" @checked($member->is_active) class="rounded"> Actief</label>@else<span class="rounded-lg bg-primary-50 px-3 py-2 text-sm font-semibold text-primary-700">Eigenaar</span>@endif<button class="btn btn-outline" @cannot('staff.edit') disabled @endcannot>Opslaan</button></div></div>

<div class="mt-4 grid gap-3 border-t border-slate-100 pt-4 sm:grid-cols-2"><input type="password" name="password" placeholder="Nieuw wachtwoord (optioneel)" class="form-input" minlength="12"><div class="flex gap-2"><input type="password" name="password_confirmation" placeholder="Bevestigen" class="form-input flex-1">@if(!$member->isOwner())<button type="submit" formmethod="POST" formaction="{{ route('admin.settings.staff.destroy', $member) }}" name="_method" value="DELETE" class="rounded-lg px-3 text-sm font-semibold text-red-600 hover:bg-red-50" onclick="return confirm('Dit medewerkeraccount verwijderen?')" @cannot('staff.delete') disabled @endcannot>Verwijderen</button>@endif</div></div>

@if(auth()->user()->can('staff.permissions.manage'))
<div class="mt-6 border-t border-slate-100 pt-4">
    <h3 class="text-sm font-semibold text-slate-900">Rechten</h3>
    <div class="mt-3 grid gap-6 md:grid-cols-2">
        @foreach($groups as $groupName => $groupPerms)
            <div class="rounded-xl bg-slate-50 p-4">
                <div class="mb-2 flex items-center justify-between"><span class="text-sm font-semibold text-slate-700">{{ $groupName }}</span><label class="flex items-center gap-1.5 text-xs text-slate-500"><input type="checkbox" class="group-toggle rounded" data-group="group-{{ $loop->index }}"> Alles</label></div>
                <div class="space-y-2 group-{{ $loop->index }}">
                    @foreach($groupPerms as $perm)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="permissions[]" value="{{ $perm }}" @checked(in_array($perm, $memberPerms)) class="rounded permission-checkbox">
                            {{ $labels[$perm] ?? $perm }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
</form>@endforeach</div>
</div>
</main></div>
<script>
document.querySelectorAll('.group-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.' + this.dataset.group + ' .permission-checkbox').forEach(cb => cb.checked = checked);
    });
});
</script>
@endsection
