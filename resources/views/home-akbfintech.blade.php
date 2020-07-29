@extends('layouts.home-akb-layout')

@section('content')
<home-component 
    :lang="{{file_get_contents(Lang::get('mvp-home.json_path'))}}"
    :user="{{ json_encode(Auth::user()) }}"
></home-component>
@endsection

