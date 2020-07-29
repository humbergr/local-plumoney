@extends('layouts.dash')

@section('content')
  <div class="grid-container w-background">
    <price-component2></price-component2>
    <volume-component2 v-bind:countries="{{json_encode($countries)}}"></volume-component2>
  </div>
  <div class="grid-container w-background">
    <ads-quick-edit-component></ads-quick-edit-component>
  </div>
    <advertisements-component2 :advertisements2="{{json_encode($data['data']['ad_list'])}}" :url2="{{json_encode($data['pagination']['next'])}}" :countries="{{json_encode($countries)}}" :baseurl2="{{json_encode($baseurl)}}"></advertisements-component2>
@endsection
