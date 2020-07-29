@extends('layouts.app')

@section('content')
<!--  <example-component></example-component> -->
  <price-component></price-component>
  <hr>
  <volume-component v-bind:countries="{{json_encode($countries)}}"></volume-component>
  <hr>
  <div class="container">
    <h3>Search Advertisements</h3>
    <hr>
    <form class="" action="/search" method="GET">
      <div class="row">
        <div class="col-md-3">
          <select class="form-control" name="country" required>
            <option  value="" selected disabled>Select Country</option>
            @foreach($countries as $country)
            <option value="{{$country->code}}">{{$country->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-control" name="bank">
            <option  value="" selected disabled>Select Bank</option>
            <option  value="">None</option>
            <option value="(b.*a.*n.*e.*s.*c.*o)i">Banesco</option>
            <option value="(m.*e.*r.*c.*a.*n.*t.*i.*l)i">Banco Mercantil</option>
            <option value="(v.*e.*n.*e.*z.*u.*e.*l.*a)i">Banco de Venezuela</option>
            <option value="(p.*r.*o.*v.*i.*n.*c.*i.*a.*l)i">Banco Provincial</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" class="form-control" name="amount" value="" placeholder="Type Amount">
        </div>
        <div class="col-md-3">
          <select class="form-control" name="operation" required>
            <option value="" selected disabled>Select Operation</option>
            <option  value="buy">Buy Bitcoins</option>
            <option value="sell">Sell Bitcoins</option>
          </select>
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-default">Search</button>
        </div>
      </div>
    </form>
  </div>
  <hr>
  <advertisements-component :advertisements2="{{json_encode($data['data']['ad_list'])}}" :url2="{{json_encode($data['pagination']['next'])}}" :countries="{{json_encode($countries)}}" :baseurl2="{{json_encode($baseurl)}}"></advertisements-component>
<!--  <advertisements-component v-bind:res="{{json_encode($data)}}"></advertisements-component> -->
@endsection
