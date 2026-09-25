@extends('emails.layout')

@section('subject', $subject)

@section('content')
{!! $html !!}
@endsection
