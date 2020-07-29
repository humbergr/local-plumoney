@extends('layouts.coinbank-layout-sin-sidebar')


@section('css')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/css/countrySelect.min.css" integrity="sha256-nEi3429617679e6HunQ6KpCztvItMxIOkEW5u88qSdM=" crossorigin="anonymous" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css" integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" integrity="sha256-nbyata2PJRjImhByQzik2ot6gSHSU4Cqdz5bNYL2zcU=" crossorigin="anonymous" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/css/flag-icon.min.css" integrity="sha256-YjcCvXkdRVOucibC9I4mBS41lXPrWfqY2BnpskhZPnw=" crossorigin="anonymous" />

 --}}

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

    <div class="card-header text-primary font-weight-bold"> <h4><a href="{{ url('cuentas') }}">Ir atras</a> (Editar) Cuentas</h4></div>

    <div class="card-body">



     <form action="{{ url('cuentas/'.$cuenta->id) }}"  onsubmit="return  checkPhone()"  method="POST" accept-charset="utf-8">
      @csrf
          <input type="hidden" name="_method" value="PUT">

       


    <div class="row">


     <div class="col-md-12">

      <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
        <label class="form-control-label text-primary"   for="input-name">{{ __('Nombre Cuenta:') }}</label>
        <input type="text" name="name" id="input-name" class="form-control  form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __(' Nombre Cuenta:') }}" value="{{ old('name', @$cuenta->name) }}" required   >

        @if ($errors->has('name'))
        <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
      </div>


    </div>
    


</div> {{-- row --}}

 



<div class="row">

  <div class="col-md-12 text-right">
    <button type="submit" class="btn btn-warning">Editar Cuenta</button>
  </div>
</div>

</form>

</div> {{--card-body  --}}
</div>   {{--card  --}}  



 





</div>


@endsection

@section('js')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/js/countrySelect.min.js" integrity="sha256-CJtHxCZRQpS4Q4X7X4T8i/PcJC3ZKT0rnQ25bX4yM5Y=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js" integrity="sha256-vucLmrjdfi9YwjGY/3CQ7HnccFSS/XRS1M/3k/FDXJw=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.11/js/intlTelInput.min.js" integrity="sha256-679hprK8vxlf4fnVBENMDhjXffz6MSULSiah9G9FRZg=" crossorigin="anonymous"></script>
 --}}
 

 
@endsection
