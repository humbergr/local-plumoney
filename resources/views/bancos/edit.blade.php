@extends('layouts.coinbank-layout-sin-sidebar')


@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/css/countrySelect.min.css" integrity="sha256-nEi3429617679e6HunQ6KpCztvItMxIOkEW5u88qSdM=" crossorigin="anonymous" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css" integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" integrity="sha256-nbyata2PJRjImhByQzik2ot6gSHSU4Cqdz5bNYL2zcU=" crossorigin="anonymous" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/css/flag-icon.min.css" integrity="sha256-YjcCvXkdRVOucibC9I4mBS41lXPrWfqY2BnpskhZPnw=" crossorigin="anonymous" />



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

  <span class="text-danger">  {{ $element }}</span>

  @endforeach

  <div class="card">

    <div class="card-header text-primary font-weight-bold"> <h4><a href="{{ url('bancos') }}">Ir atras</a> (Editar) Cajas & Bancos</h4></div>

    <div class="card-body">



     <form action="{{ url('bancos/'.$banco->id) }}"  onsubmit="return  checkPhone()"  method="POST" accept-charset="utf-8">
      @csrf
          <input type="hidden" name="_method" value="PUT">

      <div class="row">

        <div class="col-md-3">
         <label for="feInputState" class="text-primary">País:</label>
         <input class="form-control" type="text" id="country" />
         <input type="hidden" name="pais" id="country_code" />

       </div>  

       <div class="col-md-3">



        <div class="form-group has-error">
          <label for="feInputState" class="text-primary">Caja u/o Banco:</label>
          <select id="cajaobanco" name="cajaobanco" class="form-control   form-control-alternative{{ $errors->has('cajaobanco') ? ' is-invalid' : '' }}" required >
            <option value="">Selecciona:</option>
            <option value="CAJA" {{ old('CAJA') == 1 ? 'selected' : '' }}>Caja</option>
            <option value="BANCO" {{ old('BANCO') == 1 ? 'selected' : '' }}>Banco</option>

          </select>
          @if ($errors->has('cajaobanco'))
          <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('cajaobanco') }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-5">
        <div class="form-group{{ $errors->has('moneda') ? ' has-danger' : '' }}">
          <label class="form-control-label text-primary" for="input-moneda">{{ __('Currency:') }}</label>
          <br>
          <select id="moneda" class="" class="form-control" name="moneda" required>

            @foreach ( tipoMonedas() as $key => $element)

             

            <option {{ @$banco->moneda ==  $key ? 'selected': '' }} value="{{$key}}" {{ old('moneda') == $key ? 'selected' : '' }}>{{$element}} </option>
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


     <div class="col-md-2">

      <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
        <label class="form-control-label text-primary"   for="input-name">{{ __('Nombre Caja/Banco:') }}</label>
        <input type="text" name="name" id="input-name" class="form-control  form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __(' Nombre Caja/Banco:') }}" value="{{ old('name', @$banco->name) }}" required   >

        @if ($errors->has('name'))
        <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
      </div>


    </div>
    <div class="col-md-4">


      <div class="form-group{{ $errors->has('numero') ? ' has-danger' : '' }}">
        <label class="form-control-label text-primary" for="input-numero" >{{ __('Número de cuanta:') }}</label>
        <input type="text" name="numero" id="input-numero" class="form-control  form-control-alternative{{ $errors->has('numero') ? ' is-invalid' : '' }}" placeholder="{{ __('Número de cuanta:') }}" value="{{ old('numero', @$banco->numero) }}" required   >

        @if ($errors->has('numero'))
        <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('numero') }}</strong>
        </span>
        @endif
      </div>
    </div>
    <div class="col-md-4">

      <div class="form-group{{ $errors->has('beneficiario') ? ' has-danger' : '' }}">
        <label class="form-control-label text-primary" for="input-beneficiario" >{{ __('Beneficiario:') }}</label>
        <input type="text" name="beneficiario" id="input-beneficiario" class="form-control  form-control-alternative{{ $errors->has('beneficiario') ? ' is-invalid' : '' }}" placeholder="{{ __('Beneficiario:') }}" value="{{ old('beneficiario', @$banco->beneficiario) }}" required     >

        @if ($errors->has('beneficiario'))
        <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('beneficiario') }}</strong>
        </span>
        @endif
      </div>

    </div>



    <div class="col-md-2">
     <div class="form-group has-error">
      <label for="feInputState" class="text-primary">Tipo:</label>
      <select id="tipo" name="tipo" class="form-control   form-control-alternative{{ $errors->has('tipo') ? ' is-invalid' : '' }}" required >
        <option value="">Select</option>
        <option value="CORRIENTE" {{ old('tipo') == 'CORRIENTE' ? 'selected' : '' }}>Corriente</option>
        <option value="AHORRO" {{ old('tipo') == 'AHORRO' ? 'selected' : '' }}>Ahorro</option>

      </select>
      @if ($errors->has('tipo'))
      <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('tipo') }}</strong>
      </span>
      @endif
    </div>
  </div>


</div> {{-- row --}}

<div class="row">

  <div class="col-md-4">
    <div class="form-group{{ $errors->has('ejecutivo') ? ' has-danger' : '' }}">
      <label class="form-control-label text-primary" for="input-ejecutivo">{{ __('Ejecutivo:') }}</label>
      <input type="text" name="ejecutivo" id="input-ejecutivo" class="form-control  form-control-alternative{{ $errors->has('ejecutivo') ? ' is-invalid' : '' }}" placeholder="{{ __('Ejecutivo:') }}" value="{{ old('ejecutivo', @$banco->ejecutivo) }}"     >

      @if ($errors->has('ejecutivo'))
      <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('ejecutivo') }}</strong>
      </span>
      @endif
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group{{ $errors->has('direccion') ? ' has-danger' : '' }}">
      <label class="form-control-label text-primary" for="input-direccion">{{ __('Dirección:') }}</label>
      <input type="text" name="direccion" id="input-direccion" class="form-control  form-control-alternative{{ $errors->has('direccion') ? ' is-invalid' : '' }}" placeholder="{{ __('Dirección:') }}" value="{{ old('direccion', @$banco->direccion) }}"     >

      @if ($errors->has('ejecutivo'))
      <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('ejecutivo') }}</strong>
      </span>
      @endif
    </div>
  </div>


  <div class="col-md-4">
    <div class="form-group{{ $errors->has('telefono') ? ' has-danger' : '' }}">
      <label class="form-control-label text-primary" for="input-telefono">{{ __('Teléfono:') }}</label>
      <input type="text" id="phone" name="phone" id="input-telefono" class="form-control  form-control-alternative{{ $errors->has('telefono') ? ' is-invalid' : '' }}" placeholder="" value="{{ old('telefono', @$banco->telefono) }}"     >

      @if ($errors->has('telefono'))
      <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('telefono') }}</strong>
      </span>
      @endif
    </div>
  </div>


  <div class="col-md-4">
    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
      <label class="form-control-label text-primary" for="input-email">{{ __('Email:') }}</label>
      <input type="email" name="email" id="input-email" class="form-control  form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email:') }}" value="{{ old('email', @$banco->email) }}"     >

      @if ($errors->has('email'))
      <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('email') }}</strong>
      </span>
      @endif
    </div>
  </div>

  
  <div class="col-md-3">
    <div class="form-group{{ $errors->has('comision') ? ' has-danger' : '' }}">
      <label class="form-control-label text-primary" for="input-comision"> </span>{{ __('Comisión:') }}</label>
      <input type="text" name="comision" id="comision"
        class="form-control  form-control-alternative{{ $errors->has('comision') ? ' is-invalid' : '' }}"
        placeholder="{{ __('comision:') }}" value="{{ old('comision', @$banco->comision) }}" required>

      @if ($errors->has('comision'))
      <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('comision') }}</strong>
      </span>
      @endif
    </div>
  </div>

</div>{{-- row --}}



<div class="row">

  <div class="col-md-12 text-right">
    <button type="submit" class="btn btn-warning">Editar Banco u/o Caja</button>
  </div>
</div>

</form>

</div> {{--card-body  --}}
</div>   {{--card  --}}  



 





</div>


@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/js/countrySelect.min.js" integrity="sha256-CJtHxCZRQpS4Q4X7X4T8i/PcJC3ZKT0rnQ25bX4yM5Y=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js" integrity="sha256-vucLmrjdfi9YwjGY/3CQ7HnccFSS/XRS1M/3k/FDXJw=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.11/js/intlTelInput.min.js" integrity="sha256-679hprK8vxlf4fnVBENMDhjXffz6MSULSiah9G9FRZg=" crossorigin="anonymous"></script>

<script>


$(function() {
    $("#cajaobanco").val('{{$banco->cajaobanco}}');
    $("#tipo").val('{{$banco->tipo}}');

});

 $("#country").countrySelect({
  defaultCountry: "{{$banco->pais}}",
  preferredCountries: ['ve', 'co', 'us'],
});


 let dropdown = $('.js-example-basic-single');

 dropdown.empty();

 dropdown.append('<option  disabled>Selecciona una Moneda</option>');
 dropdown.prop('selectedIndex', 0);

 const url = '{{ asset('js/currency.json') }}';

// Populate dropdown with list of provinces
$.getJSON(url, function (data) {
  $.each(data, function (key, entry) {
    if(key === '{{$banco->moneda}}'){

        dropdown.append($('<option selected="true"></option>').attr('value', key).text(entry));

    }else{
  dropdown.append($('<option></option>').attr('value', key).text(entry));

    }
  
  })
});


    
$('.js-example-basic-single').select2({

  theme: "bootstrap"

});
$('#moneda').select2({

theme: "bootstrap"

});




phone = document.querySelector("#phone"),
iti = window.intlTelInput(phone, {

  hiddenInput: "telefono",
  defaultCountry: "ve",
  preferredCountries: ['ve', 'co', 'us'],
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/utils.js"
});


function checkPhone(){
 if (iti.isValidNumber()) {


  return true

} else {
  alert('Instroduce un télefono')
  return false;

}
}
</script>
@endsection
