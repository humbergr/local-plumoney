@extends('layouts.coinbank-layout-sin-sidebar')

@section('content')

<main class="" style="padding: 24px; background: #f4f4f9">
  <div class="container">


    @foreach ($errors->all() as $element)
    <span class="text-danger"> {{ $element }}</span>
    @endforeach

    @if (session('status'))

              <div class="alert alert-success alert-dismissible fade show" role="alert">

                {{ session('status') }}

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                  <span aria-hidden="true">&times;</span>

                </button>

              </div>

              @endif
    <div class="card ">

      <div class="card-header text-primary font-weight-bold">
        <h4>Nuevo Usuario</h4>
      </div>

      <div class="card-body">

        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-12">


              
            
         

              <form class="form-cotrol" action="{{ URL('/movimientos-create-user') }}" method="post">

                {{ csrf_field() }}

                <div class="row">
                  <div class="col-md-2">
                    <label for="visible">User email</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input autofocus type="email" class="form-control" value="" name="email" required></label>
                    </div>
                  </div>
                </div><br>

                <br>
                <div class="row">
                  <div class="col-md-2">
                    <label for="visible">Password</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" value="" name="password" required></label>
                    </div>
                  </div>
                </div>
                <br>

                <div class="row">
                  <div class="col-md-2">
                    <label for="visible">User full name</label>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" value="" name="name" required></label>
                    </div>
                  </div>
                </div>
                
<br>
              

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
                  <button type="submit" class="btn btn-primary" name="button">Create user</button>
                  <a href="{{ URL::to('/app') }}" class="btn" style="color:black">Cancel</a>
                </div>

              </form>


            </div>
          </div>


        </div>
      </div>
    </div>

</main>

@endsection

@section('js')
<script>
  window.onunload = refreshParent;
  function refreshParent() {
    window.opener.location.href='movimientos?add=true'
  }
</script>
    
@endsection