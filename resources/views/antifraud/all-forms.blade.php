@extends('layouts.app')

@section('content')

<div class="container">

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-default">
          <div class="card-header">Recorded forms</div>
            <div class="card-body">
                <div class="row">
                  <table style="width:100%">
                    <tr class="text-center">
                      <th>type</th>
                      <th>full name</th>
                      <th>email</th>
                      <th>location</th>
                      <th></th>
                    </tr>
                    @foreach($forms as $form)
                      <tr class="text-center">
                        <th>{{$form->type}}</th>
                        <th>{{$form->fullname}}</th>
                        <th>{{$form->email}}</th>
                        <th>{{$form->location}}</th>
                        <th><a href="{{URL::to('/form-view').'/'.$form->id}}" class="btn btn-default" style="color:black">View</a></th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            </div>
        </div>
    </div>
</div><br>

<nav aria-label="Page navigation">
		  {{ $forms->links() }}
</nav>

</div>

@endsection
