@extends('layouts.app')

@section('content')
  <my-ads-component :advertisements2="{{json_encode($ads['data']['ad_list'])}}" :paymentmethods2="{{json_encode($methods)}}"></my-ads-component>

  <div class="container">
    <div style="text-align:right">
      <a class="btn btn-default grey" style="color:black" href="{{URL::to('/create-advertisement')}}">Create new advertisement</a>
      <a href="{{ URL::to('/app') }}" class="btn" style="color:black">Return</a>
    </div>
  </div>
@endsection
