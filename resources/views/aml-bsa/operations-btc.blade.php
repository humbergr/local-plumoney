@extends('layouts.coinbank-layout')

@section('content')
<operaciones-btc      :transactions="{{ json_encode($transactions) }}" 
                      :type="{{json_encode($type)}}">
</operaciones-btc>
@endsection
