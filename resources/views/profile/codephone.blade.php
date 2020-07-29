@extends('layouts.mvp-layout-internal')

@section('content')

<main class="dashboard__main"
style="padding: 24px; background: #f4f4f9">
<div class="container">
    <div class="alert alert-warning rounded-lg px-3 py-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="text-primary mb-2 mb-md-0">Hemos enviado un código a tu celular</h6>
            </div>

        </div>
    </div>



 

    <div class="card shadow-none rounded-lg mb-4">

        <div class="card-body">

            <form action="{{ url('user-verify-phone') }}" method="post" accept-charset="utf-8">
@csrf
                <div class="form-group{{ $errors->has('codigo') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-codigo">{{ __('Ingrese el codigo enviado') }}</label>
                  <input type="text" name="codigo" id="input-codigo" class="form-control  form-control-alternative{{ $errors->has('codigo') ? ' is-invalid' : '' }}" placeholder="{{ __('****') }}" value="{{ old('codigo', @$model->codigo) }}" required   >
                  
                  @if ($errors->has('codigo'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('codigo') }}</strong>
                  </span>
                  @endif
              </div>

              <button class="btn btn-primary" type="submit">Verificar</button>
           


          </form>

      </div>

  </div>

 
</div>
</main>

@endsection
