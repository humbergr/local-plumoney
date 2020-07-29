@extends('layouts.coinbank-layout')

@section('content')

<contact-chat :contact_id="{{$advertisement->contact_id}}"></contact-chat>

@endsection
