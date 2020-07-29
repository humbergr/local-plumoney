@extends('layouts.coinbank-layout')

@section('content')
<operaciones :user="{{json_encode(Auth::user())}}"
:type="{{json_encode($type)}}">
</operaciones>
@endsection
