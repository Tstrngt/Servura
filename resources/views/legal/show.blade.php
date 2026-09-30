@extends('legal.layout')

@section('title', $document->title.' - '.config('company.trade_name', 'Servura'))
@section('meta-description', $document->title.' van '.config('company.trade_name', 'Servura').'.')
@section('meta-keywords', $document->slug.', '.config('company.trade_name', 'Servura'))

@section('legal-title')
    {{ $document->title }}
@endsection

@section('legal-meta')
    Versie {{ $document->version }} – laatst gewijzigd op {{ $document->effective_date?->format('d-m-Y') ?? '-' }}
@endsection

@section('legal-content')
    {!! $document->content !!}
@endsection
