@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card card-default">
            <div class="card-header">Confirm your account</div>
              <div class="card-body">

                <form class="form-cotrol" action="{{ URL('/verify-account') }}" method="post">

                  {{ csrf_field() }}

                  <input type="hidden" name="verification_token" value="{{$token}}">
                  <input type="hidden" name="email" value="{{$user->email}}">

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Your email</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="email"  class="form-control" value="{{$user->email}}" name="email" required disabled></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">User full name</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text"  class="form-control" value="{{$user->name}}" name="name" required disabled></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Password</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="password"  class="form-control" value="" name="password" required></label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <p>Password must have a minimum of 8 characters and must contain an uppercase letter and a special symbol.</p>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Password verification</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="password"  class="form-control" value="" name="password_verification" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="text-center">
                    <button type="submit" class="btn btn-default" name="button">Confirm</button>
                    <a href="{{ URL::to('/') }}" class="btn" style="color:black">Cancel</a>
                  </div>

                </form>

              </div>
          </div>
      </div>
  </div>
</div>

@endsection
