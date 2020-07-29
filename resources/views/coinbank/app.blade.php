@extends('layouts.coinbank-layout')

@section('content')

  <price-component-v3></price-component-v3>

  <volume-component v-bind:countries="{{json_encode($countries)}}"></volume-component>

  <advertisements-component-v3 :countries="{{json_encode($countries)}}"
                               :traders="{{json_encode($traders)}}"
                               :initexchanges="{{json_encode($exchanges)}}">
  </advertisements-component-v3>

@endsection
