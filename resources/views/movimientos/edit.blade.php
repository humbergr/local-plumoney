@extends('layouts.coinbank-layout-sin-sidebar')


@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/css/countrySelect.min.css"
  integrity="sha256-nEi3429617679e6HunQ6KpCztvItMxIOkEW5u88qSdM=" crossorigin="anonymous" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css"
  integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous" />

<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
  integrity="sha256-nbyata2PJRjImhByQzik2ot6gSHSU4Cqdz5bNYL2zcU=" crossorigin="anonymous" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/css/flag-icon.min.css"
  integrity="sha256-YjcCvXkdRVOucibC9I4mBS41lXPrWfqY2BnpskhZPnw=" crossorigin="anonymous" />



@endsection
@section('content')


<div class="container">


  @if (session('status'))

  <div class="alert alert-success alert-dismissible fade show" role="alert">

    {{ session('status') }}

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

      <span aria-hidden="true">&times;</span>

    </button>

  </div>

  @endif
  @foreach ($errors->all() as $element)

  <span class="text-danger"> {{ $element }}</span>

  @endforeach

  <div class="card">

    <div class="card-header text-primary font-weight-bold">
      <h4><a href="{{ url('movimientos') }}">Ir atras</a> (Editar) Movimiento</h4>
    </div>

    <div class="card-body">



      <form action="{{ url('movimientos/'.$movimiento->id) }}" method="POST" accept-charset="utf-8">
        @csrf

        <fieldset disabled>

          <input type="hidden" name="_method" value="PUT">

          <div class="row">

            <div class="col-md-3">
              <div class="form-group{{ $errors->has('emision') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-emision">{{ __('Fecha de emision:') }}</label>
                <input type="date" name="emision" id="emision"
                  class="form-control  form-control-alternative{{ $errors->has('emision') ? ' is-invalid' : '' }}"
                  placeholder="{{ __('DD-MM-YYYY') }}" value="{{ old('emision', @$movimiento->emision) }}" required>

                @if ($errors->has('emision'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('emision') }}</strong>
                </span>
                @endif
              </div>
            </div>


            <div class="col-md-3">
              <div class="form-group{{ $errors->has('lote') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-lote">{{ __('Fecha de lote:') }}</label>
                <input type="text" name="lote" id="lote"
                  class="form-control  form-control-alternative{{ $errors->has('lote') ? ' is-invalid' : '' }}"
                  placeholder="{{ __('DD-MM-YYYY') }}" value="{{ old('lote', @$movimiento->lote) }}" required>

                @if ($errors->has('lote'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('lote') }}</strong>
                </span>
                @endif
              </div>
            </div>

          </div>

          <div class="row">



            <div class="col-md-4">



              <div class="form-group  ">
                <label for="feInputState" class="text-primary">Caja u/o Banco:</label>
                <select id="banco" name="banco_id"
                  class="select2 form-control form-control-alternative{{ $errors->has('banco_id') ? ' is-invalid' : '' }}"
                  required>
                  <option value="">Selecciona:</option>

                  @foreach ( bancos() as $element)
                  {{-- expr --}}

                  <option data-moneda="{{$element->moneda}}" value="{{$element->id}}"
                    {{ old('banco_id') == $element->id ? 'selected' : '' }}>{{$element->name}} {{$element->numero}}
                  </option>
                  @endforeach

                </select>
                @if ($errors->has('banco_id'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('banco_id') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="col-md-5">
              <div class="form-group{{ $errors->has('moneda') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-moneda">{{ __('Moneda:') }}</label>
                <br>
                <select id="moneda" class="" class="form-control" name="moneda" required>

                  @foreach ( tipoMonedas() as $key => $element)


                  <option value="{{$key}}" {{ old('moneda') == $key ? 'selected' : '' }}>{{$element}} </option>
                  @endforeach


                </select>
                @if ($errors->has('moneda'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('moneda') }}</strong>
                </span>
                @endif
              </div>

            </div>


          </div>


          <div class="row">


            <div class="col-md-3">

              <div class="form-group  ">
                <label for="feInputState" class="text-primary">Tipo de movimiento:</label>
                <select id="tipo" name="tipo"
                  class="form-control form-control-alternative{{ $errors->has('tipo') ? ' is-invalid' : '' }}" required>
                  <option value="">Selecciona:</option>

                  @foreach ( tipoDeMovimiento() as $key => $element)
                  {{-- expr --}}

                  <option value="{{$key}}" {{ old('tipo') == $key ? 'selected' : '' }}>{{$element}} </option>
                  @endforeach

                </select>
                @if ($errors->has('tipo'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('tipo') }}</strong>
                </span>
                @endif
              </div>


            </div>




            <div class="col-md-3">


              <div class="form-group  ">
                <label for="feInputState" class="text-primary">Cueta Ingreso/Egreso:</label>
                <select id="cuenta" name="cuenta_id"
                  class=" form-control form-control-alternative{{ $errors->has('cuenta_id') ? ' is-invalid' : '' }}"
                  required>
                  <option value="">Selecciona:</option>

                  @foreach ( cuentas() as $element)
                  {{-- expr --}}

                  <option value="{{$element->id}}" {{ old('cuenta_id') == $element->id ? 'selected' : '' }}>
                    {{$element->name}}</option>
                  @endforeach

                </select>
                @if ($errors->has('cuenta_id'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('cuenta_id') }}</strong>
                </span>
                @endif
              </div>

            </div>

            <div class="col-md-3">
              <div class="form-group ">
                <label for="feInputState" class="text-primary">Tipo de operación:</label>
                <select id="operacion" name="operacion"
                  class="form-control form-control-alternative{{ $errors->has('operacion') ? ' is-invalid' : '' }}"
                  required>
                  <option value="">Selecciona:</option>

                  @foreach ( operacion() as $key => $element)
                  <option value="{{$key}}" {{  @$movimiento->operacion == $key ? 'selected' : '' }}>{{$element}}
                  </option>
                  @endforeach

                </select>
                @if ($errors->has('operacion'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('operacion') }}</strong>
                </span>
                @endif
              </div>
            </div>





          </div> {{-- row --}}
          <div class="row">

            <div class="col-md-4">
              <div class="form-group  ">
                <label for="feInputState" class="text-primary">Usuario:</label>
                <select id="usuario" name="user_id"
                  class=" form-control form-control-alternative{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                  required>
                  <option value="">Selecciona:</option>

                  @foreach ( usuarioGet($movimiento->user_id) as $element)
                  {{-- expr --}}

                  <option value="{{$element->id}}">
                    {{$element->name}} {{$element->email}}</option>
                  @endforeach

                </select>
                @if ($errors->has('user_id'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('user_id') }}</strong>
                </span>
                @endif
              </div>

            </div>



          </div> {{-- row --}}

          <div class="row">

            <div class="col-md-8">
              <div class="form-group  ">
                <label for="feInputState" class="text-primary">Descripción:</label>


                <textarea class="form-control" name="descripcion">{{$movimiento->descripcion}}</textarea>

              </div>
            </div>

          </div> {{-- row --}}

          <div class="row">



            <div class="col-md-3">


              <div class="form-group{{ $errors->has('monto') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-monto">{{ __('Monto:') }}</label>
                <input type="text" step="any" name="monto" id="input-monto"
                  class="form-control  form-control-alternative{{ $errors->has('monto') ? ' is-invalid' : '' }}"
                  placeholder="{{ __('Monto:') }}" value="{{ old('monto', currency( @$movimiento->monto,'')) }}" required>

                @if ($errors->has('monto'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('monto') }}</strong>
                </span>
                @endif
              </div>

            </div>



            <div class="col-md-3">
              <div class="form-group{{ $errors->has('tasa') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-tasa">{{ __('tasa de cambio:') }}</label>
                <input type="text" step="any" name="tasa" id="tasa"
                  class="form-control  form-control-alternative{{ $errors->has('tasa') ? ' is-invalid' : '' }}"
                  placeholder="{{ __('tasa:') }}" value="{{ old('tasa', currency( @$movimiento->tasa,'')) }}" required>

                @if ($errors->has('tasa'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('tasa') }}</strong>
                </span>
                @endif
              </div>
            </div>


            <div class="col-md-3">
              <div class="form-group{{ $errors->has('monto_usd') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-monto_usd"><span
                    class="flag-icon flag-icon-us"></span>{{ __('Monto USD:') }}</label>
                <input type="text" step="any" name="monto_usd" id="monto_usd"
                  class="form-control  form-control-alternative{{ $errors->has('monto_usd') ? ' is-invalid' : '' }}"
                  placeholder="{{ __('monto_usd:') }}" value="{{ old('monto_usd',  currency(@$movimiento->monto_usd,'')) }}" required>

                @if ($errors->has('monto_usd'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('monto_usd') }}</strong>
                </span>
                @endif
              </div>
            </div>

          

          </div> {{-- row --}}


          <div class="row">

            <div class="col-md-3">
              <div class="form-group{{ $errors->has('comision') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-comision"> </span>{{ __('Comisión Bancaria:') }}</label>
                <input type="text" name="comision" id="comision"
                  class="form-control  form-control-alternative{{ $errors->has('comision') ? ' is-invalid' : '' }}"
                  placeholder="{{ __('comision:') }}" value="{{ old('comision',  currency(@$movimiento->comision ,'')) }}" required>
          
                @if ($errors->has('comision'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('comision') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group{{ $errors->has('monto_bruto') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-monto_bruto"> </span>{{ __('Monto bruto:') }}</label>
                <input type="text" name="monto_bruto" id="monto_bruto"
                  class="form-control  form-control-alternative{{ $errors->has('monto_bruto') ? ' is-invalid' : '' }}"
                  placeholder="{{ __('monto_bruto:') }}" value="{{ old('monto_bruto', currency(@$movimiento->monto_bruto,'')) }}" required>
          
                @if ($errors->has('monto_bruto'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('monto_bruto') }}</strong>
                </span>
                @endif
              </div>
            </div>
          </div>
          <div class="">


            @if ($movimiento->capture != null)

            <div class="d-flex justify-content-around">
              <div class="">
                <a target="_blank" href="{{ asset($movimiento->capture) }}"><span class="badge badge-primary"> capture
                    de la operación</span></a>
              </div>
            </div>
            @endif

            {{--   <div class="col-md-12 text-right">
    <button type="submit" class="btn btn-warning">Editar Banco u/o Caja</button>
  </div> --}}



          </div>
        </fieldset>
      </form>

    </div> {{--card-body  --}}
  </div> {{--card  --}}









</div>


@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/js/countrySelect.min.js"
  integrity="sha256-CJtHxCZRQpS4Q4X7X4T8i/PcJC3ZKT0rnQ25bX4yM5Y=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js"
  integrity="sha256-vucLmrjdfi9YwjGY/3CQ7HnccFSS/XRS1M/3k/FDXJw=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.11/js/intlTelInput.min.js"
  integrity="sha256-679hprK8vxlf4fnVBENMDhjXffz6MSULSiah9G9FRZg=" crossorigin="anonymous"></script>

<script>
  $(function() {
    $("#moneda").val('{{$movimiento->moneda}}');


  });






  jQuery(document).ready(function($) {

      $('#banco').select2({

    theme: "bootstrap",
     disabled: true

  });


    $('#tipo').select2({

     
    theme: "bootstrap",
    disabled: true

    });

    $('#cuenta').select2({

     
    theme: "bootstrap",
    disabled: true

    });

    $('#usuario').select2({

     
    theme: "bootstrap",
    disabled: true

    });

    $('#moneda').select2({

    
    theme: "bootstrap",
    disabled: true

   });



    $("#cuenta").val('{{$movimiento->cuenta_id}}').trigger('change');;;
    $("#banco").val('{{$movimiento->banco_id}}').trigger('change');;
    $("#tipo").val('{{$movimiento->tipo}}').trigger('change');;
    $("#usuario").val('{{$movimiento->user_id}}').trigger('change');;



    $('#banco').change(function(){

      moneda2 = $('option:selected', this).data("moneda");

      console.log(moneda2);

      $('#moneda').val(moneda2).trigger('change');





    });


  });






</script>
@endsection