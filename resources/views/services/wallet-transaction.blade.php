@extends('layouts.mvp-layout-internal')
<!--<script>
    fbq('track', 'SubmitApplication');
</script>-->

@section('content')
    <wallet-transaction :order="{{json_encode($transaction)}}"
                        :user="{{Auth::user()}}"
                        :profile="{{Auth::user()->personProfileObject()}}"
                        :accounts="{{json_encode($account)}}">
    </wallet-transaction>
@endsection
