@extends('layouts.main-transactions-list')

@section('content')
  <transactions-list
          :transactions2="{{json_encode($transactions)}}"
  ></transactions-list>

@endsection
