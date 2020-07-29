@extends('layouts.mvp-layout-internal')
<!--<script>
    fbq('track', 'SubmitApplication');
</script>-->

@section('content')
    <send-cash :order="{{json_encode($transaction)}}"
               :destination="{{json_encode($destination)}}"
               :user="{{Auth::user()}}"
               :profile="{{Auth::user()->personProfileObject()}}"
               :accounts="{{json_encode($account)}}">
    </send-cash>
@endsection
