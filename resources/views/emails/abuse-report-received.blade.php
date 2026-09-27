@component('mail::message')
# Nieuwe abuse-melding

Er is een nieuwe abuse-melding binnengekomen via {{ config('company.trade_name', 'Servura') }}.

**Type:** {{ $report->category_label }}
**Domein:** {{ $report->domain ?? '-' }}
**URL:** {{ $report->url }}

**Melder:**
- Naam: {{ $report->name }}
- E-mail: {{ $report->email }}

**Omschrijving:**
{{ $report->description }}

**Reden:**
{{ $report->reason }}

Bekijk de melding in het beheersysteem voor meer informatie.

{{ config('company.trade_name', 'Servura') }}
@endcomponent
