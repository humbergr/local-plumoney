@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card card-default">
            <div class="card-header">Invite User</div>
              <div class="card-body">

                <form class="form-cotrol" action="{{ URL('/create-user') }}" method="post">

                  {{ csrf_field() }}

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">User email</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="email"  class="form-control" value="" name="email" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">User full name</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text"  class="form-control" value="" name="name" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">User role</label>
                    </div>
                    <div class="col-md-6">
                      <select class="form-control" name="role_id" required>
                        @foreach($roles as $role)
                          <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Currency to work</label>
                    </div>
                    <div class="col-md-6">
                      <select class="form-control" name="currency" required>
                          <option value="All Currencies">All Currencies</option>
                          <option value="USD">USD</option>
                          <option value="VES">VES</option>
                      </select>
                    </div>
                  </div><br>

                  <div class="text-center">
                    <button type="submit" class="btn btn-default" name="button">Create user</button>
                    <a href="{{ URL::to('/app') }}" class="btn" style="color:black">Cancel</a>
                  </div>

                </form>

              </div>
          </div>
      </div>
  </div>
</div>

@endsection
