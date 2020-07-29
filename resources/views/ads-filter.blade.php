@extends('layouts.app')

@section('content')
<!--  <example-component></example-component> -->
  <price-component></price-component>
  <search-component :advertisements2="{{json_encode($ads_data['ads'])}}" :url2="{{json_encode($ads_data['next_page'])}}" :countries="{{json_encode($countries)}}" :bank2="{{json_encode($bank)}}" :amount2="{{json_encode($amount)}}" :active2="{{json_encode($active)}}" :operation2="{{json_encode($operation)}}" :bitstamp="{{json_encode($bitstamp)}}" :traders2="{{json_encode($traders)}}" :user_role="{{json_encode(Auth::user()->role_id)}}"></search-component>
@endsection
