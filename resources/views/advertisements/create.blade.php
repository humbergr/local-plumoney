@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">Create a bitcoin trade advertisement</div>
                <form class="form-cotrol" action="{{URL::to('/create-advertisement')}}" method="post">
                  {{ csrf_field() }}
                  <create-advertisement-component  :url="{{json_encode(URL::to('/create-advertisement'))}}" :methods="{{json_encode($methods)}}"></create-advertisement-component>
              </form>
            </div>
        </div>
      </div>
  </div>
@endsection
