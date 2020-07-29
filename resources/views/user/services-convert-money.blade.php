@extends('layouts.mvp-layout-internal')

@section('content')
    <main>
        <convert-money user="{{Auth::user()->personProfileObject()}}"
        trans-tracking="{{uniqid('', false)}}" :bonus="1"></convert-money>
    </main>
@endsection
