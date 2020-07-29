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


<style>
  .select2-container--bootstrap .select2-selection--single {
    height: 38px !important;
  }
</style>

@endsection
@section('content')


<div class="container-fluid">




  <a id="newbtn" href="#" class="btn btn btn-primary mb-3">+ Create Movement</a>
  

  <a id="" href="{{ url('/movimientos-bank-to-bank', []) }}" class="btn btn btn-primary mb-3">+ Create Movement Bank To Bank</a>

  

<div id="new" class="card {{(isset($_GET['add'])) ? '' : 'd-none'}}">

  <div class="card-header text-primary font-weight-bold">
    <h4>Movement</h4>

  </div>

  <div class="card-body">



    <form action="{{ url('movimientos') }}" enctype='multipart/form-data' method="POST" accept-charset="utf-8">
      @csrf


      <div class="row">

        <div class="col-md-3">
          <div class="form-group{{ $errors->has('emision') ? ' has-danger' : '' }}">
            <label class="form-control-label text-primary" for="input-emision">{{ __('Date of emission:') }}</label>
            <input type="text" name="emision" id="emision"
              class="form-control  form-control-alternative{{ $errors->has('emision') ? ' is-invalid' : '' }}"
              placeholder="{{ __('DD-MM-YYYY') }}" value="{{ old('emision', @$model->emision) }}" required>

            @if ($errors->has('emision'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('emision') }}</strong>
            </span>
            @endif
          </div>
        </div>

        @php
        $lote = uniqid()
        @endphp
        <div class="col-md-3">
          <div class="form-group{{ $errors->has('lote') ? ' has-danger' : '' }}">
            <label class="form-control-label text-primary" for="input-lote">{{ __('Lots number:') }}</label>
            <input type="text" name="lote_label" id="lote_label"
              class="form-control  form-control-alternative{{ $errors->has('lote_label') ? ' is-invalid' : '' }}"
              placeholder="xxxxxxx" value="{{ $lote }}" disabled>
            <input type="hidden" value="{{ $lote }}" name="lote">

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
            <label for="feInputState" class="text-primary">Bank/Box:</label>
            <select id="banco" name="banco_id"
              class="select2 form-control form-control-alternative{{ $errors->has('banco_id') ? ' is-invalid' : '' }}"
              required>
              <option value="">Selecciona:</option>

              @foreach ( bancos() as $element)
              {{-- expr --}}

              <option data-moneda="{{$element->moneda}}" data-saldo2={{currency($element->saldo,'')}}
                data-saldo={{$element->saldo}} value="{{$element->id}}"
                {{ old('banco_id') == $element->id ? 'selected' : '' }}>{{$element->name}} {{$element->numero}}
                {{currency($element->saldo,$element->moneda)}}
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

        <div class="col-md-4">
          <div class="form-group{{ $errors->has('moneda') ? ' has-danger' : '' }}">
            <label class="form-control-label text-primary" for="input-moneda">{{ __('Currency:') }}</label>
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
          <div class="form-group ">
            <label for="feInputState" class="text-primary">Type movement:</label>
            <select id="tipo" name="tipo"
              class="form-control form-control-alternative{{ $errors->has('tipo') ? ' is-invalid' : '' }}" required>
              <option value="">Selecciona:</option>

              @foreach ( tipoDeMovimiento() as $key => $element)
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
            <label for="feInputState" class="text-primary">Account:</label>
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
            <label for="feInputState" class="text-primary">Type of operation:</label>
            <select id="operacion" name="operacion"
              class="form-control form-control-alternative{{ $errors->has('operacion') ? ' is-invalid' : '' }}"
              required>


              @foreach ( operacion() as $key => $element)
              <option value="{{$key}}" {{ old('operacion') == $key ? 'selected' : '' }}>{{$element}} </option>
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
            <label for="feInputState" class="text-primary">User: <a target="_blank" id="nuevo_user"
                href="{{ url('movimientos-create-user')}}"> + New User</a> </label>
            <select id="usuario" name="user_id"
              class=" form-control form-control-alternative{{ $errors->has('user_id') ? ' is-invalid' : '' }}" required>
              <option value="">Selecciona:</option>



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



        <div class="col-md-3">
          <div class="form-group{{ $errors->has('monto') ? ' has-danger' : '' }}">
            <label class="form-control-label text-primary" for="input-monto">{{ __('Amount:') }} <span
                id="saldo-label"></span></label>
            <input type="text" name="monto" id="monto"
              class="form-control  form-control-alternative{{ $errors->has('monto') ? ' is-invalid' : '' }}"
              placeholder="{{ __('Monto:') }}" value="{{ old('monto', @$model->monto) }}" required>

            @if ($errors->has('monto'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('monto') }}</strong>
            </span>
            @endif
          </div>
        </div>



        <div class="col-md-3">
          <div class="form-group{{ $errors->has('tasa') ? ' has-danger' : '' }}">
            <label class="form-control-label text-primary" for="input-tasa">{{ __('Exchange rate:') }}</label>
            <input type="text" name="tasa" id="tasa"
              class="form-control  form-control-alternative{{ $errors->has('tasa') ? ' is-invalid' : '' }}"
              placeholder="{{ __('tasa:') }}" value="{{ old('tasa', @$model->tasa) }}" required>

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
                class="flag-icon flag-icon-us"></span>{{ __('Amount USD:') }}</label>
            <input type="text" name="monto_usd" id="monto_usd"
              class="form-control  form-control-alternative{{ $errors->has('monto_usd') ? ' is-invalid' : '' }}"
              placeholder="{{ __('monto_usd:') }}" value="{{ old('monto_usd', @$model->monto_usd) }}" required>

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
            <label class="form-control-label text-primary" for="input-comision">{{ __('Charged by banks:') }}</label>
            <input type="text" name="comision" id="comision"
              class="form-control  form-control-alternative{{ $errors->has('comision') ? ' is-invalid' : '' }}"
              placeholder="{{ __('Charged by banks:') }}" value="{{ old('comision', @$model->comision) }}" required>

            @if ($errors->has('comision'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('comision') }}</strong>
            </span>
            @endif
          </div>
        </div>



        <div class="col-md-3">
          <div class="form-group  ">
            <label for="feInputState" class="text-primary">Description:</label>


            <textarea class="form-control" name="descripcion"></textarea>

          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="capture" class="text-primary">Capture:</label>
            <input type="file" class="form-control-file" name="capture" id="capture" placeholder="Capture"
              aria-describedby="fileHelpId">
            <small id="fileHelpId" class="form-text text-muted">Capture the operation Max:4MB</small>
          </div>
        </div>

      </div> {{-- row --}}

      <div class="row">

        <div class="col-md-12 text-right">
          <input type="submit" value="Create Movement" id=""
            onclick="this.disabled=true;this.value='Submitting...'; this.form.submit();" class="btn btn-primary">

        </div>
      </div>

    </form>

  </div> {{--card-body  --}}
</div> {{--card  --}}



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




<div class="card mt-3">


  <div class="card-header text-primary font-weight-bold">
    <h4>List of Movements</h4>
  </div>



  <div class="card-body">

    <form class="" action="" method="get">

      <div class="row" style="margin-bottom:10px">
        

        <div class="col-md-3">
          <select id="currency" class="" class="form-control" name="currency" required>
            <option value="all">All Currency</option>

            @foreach ( tipoMonedas() as $key => $element)


            <option value="{{$key}}" {{ old('moneda') == $key ? 'selected' : '' }}>{{$element}} </option>
            @endforeach


          </select>
        </div>

        <div class="col-md-3">
          <div class="">
            <select id="bank" name="bank_id"
              class="select2 form-control form-control-alternative{{ $errors->has('bank_id') ? ' is-invalid' : '' }}"
              required>
              <option value="all">Bank</option>

              @foreach ( bancos() as $element)
              {{-- expr --}}

              <option data-moneda="{{$element->moneda}}" data-saldo2={{currency($element->saldo,'')}}
                data-saldo={{$element->saldo}} value="{{$element->id}}"
                {{ old('bank_id') == $element->id ? 'selected' : '' }}>{{$element->name}}
                {{$element->numero}}
                {{--   {{currency($element->saldo,$element->moneda)}} --}}
              </option>
              @endforeach

            </select>
            @if ($errors->has('bank_id'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('bank_id') }}</strong>
            </span>
            @endif
          </div>
        </div>



     

        <div class="col-md-3">


          <div class="form-group  ">
           
            <select id="cuenta2" name="cuenta_id"
              class=" form-control form-control-alternative{{ $errors->has('cuenta_id') ? ' is-invalid' : '' }}"
              >
              <option value="">Acount In/Eg:</option>

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

          <div class="d-none">
            <input type="text" name="start" id="start">
            <input type="text" name="end" id="end">
          </div>
          <div class="input-group">
            <input type="text" id="creation-date-filter" class="form-control" aria-label="Creation date filter"
              aria-describedby="creation-date-filter">
            <div class="input-group-append">
              <span class="input-group-text bg-white text-muted">
                <i class="fa fa-calendar"></i>
              </span>
            </div>
          </div>
        </div>


       
      </div>

      <div class="text-right mb-2">

         <div class="btn-group" role="group" aria-label="Basic example">

                <button type="submit" class="btn btn-primary">Filter</button>
                <a class="btn btn-warning" href="{{ url('movimientos', []) }}">Clear</a>

              </div>
      </div>
    </form>

    <div class="b4-table"></div>
    <table class="table table-striped" style="font-size: 13px">

      <thead class="text-primary">
        <tr>
          <th>ID</th>
          <th>Created</th>
          <th>Country</th>
          <th>Bank</th>
           
          <th>Type</th>
          <th>Acount In/Eg</th>
           
          <th>Operation</th>
          <th>Exchange rate </th>
          
          <th>Credit</th>
          <th>Debit</th>
          @if (isset($_GET['bank_id']))
              
          <th>Balance</th>
          @endif

          <th>Usd</th>

        </tr>
      </thead>
      <tbody>
@php
    $sum = 0;
    $debit =0;
@endphp
        @foreach ($model  as $key => $element)
@php

if ($key === 0) {
  $sum += $balance  ;
} else {
  $sum = $balance + $debit   ;
}
    
@endphp
        <tr>
          <td><a href="{{ url('movimientos/'.$element->id.'/edit') }}">{{$element->id}}</a></td>
          <td nowrap>{{$element->created_at->format('m-d-y')}}</td>
          <td><a href="{{ url('movimientos?filter=pais&val='.$element->banco->pais) }}"><span
                class="flag-icon flag-icon-{{$element->banco->pais}}"></span></a></td>
          <td>{{$element->banco->name}} <div><a href="{{ url('movimientos?bank_id='.$element->banco_id) }}">{{$element->banco->numero}}</a></div> </td>
           
          <td>{{$element->tipo}}</td>
          <td>{{$element->cuenta->name}}</td>
           
          <td>{{$element->operacion}}</td>
          <td nowrap >{{currency($element->tasa, $element->moneda)}}</td>
          
          <td nowrap class="text-right text-success">
            {{($element->operacion === 'INGRESO')? currency($element->monto, $element->moneda) : '' }}
          </td>
          <td nowrap class="text-right text-danger">
            {{($element->operacion === 'EGRESO')? currency($element->monto, $element->moneda) : '' }}

           

          </td>
          @if (isset($_GET['bank_id']))
          <td nowrap class="text-primary">{{ currency($sum,$currency) }}</td>
          @endif
          <td nowrap class="text-{{($element->monto_usd >= 0)?'success':'danger'}}">
            {{currency($element->monto_usd, 'USD')}}</td>

        </tr>


        @php
            $debit += $element->monto *-1;
        @endphp
        @endforeach

      </tbody>
      @if (isset($_GET['bank_id']))


      <tfoot class="text-right">

        <tr class="font-weight-bold text-primary">
           
          
           
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Total</td>
          <td nowrap>{{currency($sum_creditos, $currency)}}</td>
          <td nowrap class="text-danger">{{currency($sum_debitos, $currency)}}</td>
          <td></td>
          <td> </td>




        </tr>


        <tr class="font-weight-bold text-primary">
           
          
          
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Balance</td>
          <td nowrap>{{currency($balance,$currency)}}</td>
          <td></td>
          <td></td>
          <td nowrap>{{currency($sum_usd,'USD')}}</td>


        </tr>


      </tfoot>
      @endif
    </table>

    {{$model->appends(request()->input())->links()}}

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.11/js/utils.js"
  integrity="sha256-Dgk4ywhuqU0wvPuVIPRY9AtcRW0G7YaGT/MCLDAVDNE=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.11/js/intlTelInput-jquery.min.js"
  integrity="sha256-uQ9YDP/OGOHLO5qgqlVq1nRTM7OJEAee5mryVZLlJVg=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.5.10/cleave.min.js"
  integrity="sha256-lqWAcasN+EP6bxH3+SBODfrydkyHQ7FDwcI44sZeff4=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/moment.min.js"
  integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.js"
  integrity="sha256-iacRP5fv2z3yGk6gnwi/CjK8GRrr5MROIurU7iwYXRM=" crossorigin="anonymous"></script>
<script>
  $('#emision').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    "locale": {
        "format": "MM-DD-YYYY",
    }
    
  });


let currentYear                     = new Date().getFullYear();
            let lastYear                        = currentYear - 1;
            let startDate                       = moment();
            let endDate                         = moment();
            let yearStartDate                   = moment(currentYear + '-01-01');
            let yearEndDate                     = moment(currentYear + '-12-01');
            
            $('#creation-date-filter').daterangepicker({
                opens: 'center',
                startDate: startDate,
                endDate: endDate,
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                
                
                            }, function(start, end, label) {
                                
                                $('#start').val(start.format('YYYY-MM-DD'));
                                $('#end').val(end.format('YYYY-MM-DD'));
                                  console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                                   });
                            
           
                                $('#start').val(startDate.format('YYYY-MM-DD'));
                                $('#end').val(endDate.format('YYYY-MM-DD'));
  
  $("#capture").change(function () {

  if (this.files && this.files[0]) {
   if (this.files[0].size > 4000000) {

   $(this).val('');

alert('Archivo supera el limite');
return false;

       }
}

 
});

var monto = new Cleave('#monto', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true,
    numeralDecimalScale: 8

});

var tasa = new Cleave('#tasa', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true
});

var comision = new Cleave('#comision', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true
});


var monto_usd = new Cleave('#monto_usd', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true
});

  function filter(param) {
  $('#keyword').val();

  

  if ($('#keyword').val() === '') {
  alert('Coloque una palabra clave...');
    return false;
  }

  
  @if (@$_GET['de'])
  var de = '{{@$_GET['de']}}';
  var hasta = '{{@$_GET['hasta']}}';
  window.location.href = "{{url('movimientos?filter=')}}"+param+'&val='+ $('#keyword').val()+'&de='+de+'&hasta='+hasta;
    @else
    window.location.href = "{{url('movimientos?filter=')}}"+param+'&val='+ $('#keyword').val();    
    @endif
  

  
}
  jQuery(document).ready(function($) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $('#usuario').select2({
      theme: "bootstrap",
    ajax: {
    url: "{{ url('api/users') }}",
    type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              _token: CSRF_TOKEN,
              search: params.term // search term
            };
          },
          processResults: function (response) {
            return {
              results: response
            };
          },
          cache: true
        }
});

    $('#banco').select2({

      theme: "bootstrap"

    });

    $('#currency').select2({

theme: "bootstrap"

});


$('#bank').select2({

theme: "bootstrap"

});



    $('#tipo').select2({

      theme: "bootstrap"

    });

    $('#cuenta').select2({

      theme: "bootstrap"

    });

    $('#cuenta2').select2({

theme: "bootstrap"

});

 
    $('#moneda').select2({

     theme: "bootstrap",
    

   });

   $('#operacion').select2({

theme: "bootstrap"

});


    $('#banco').change(function(){
     
      saldo = $('option:selected', this).data("saldo2");
      $('#saldo-label').html(saldo);
      moneda2 = $('option:selected', this).data("moneda");

      console.log(moneda2);

      $('#moneda').val(moneda2).trigger('change');

 });

 let auto = "{{@$_GET['add']}}";
 if (auto != '') {
  $('#banco').val(auto).trigger('change');
   
 }

 
 });







  $(document).ready(function() {


    $('#monto, #tasa').on('input', function(event) {
     
      console.log(monto.getRawValue());
      event.preventDefault();
      
      //multiplica Dolar a bolivar
      //divide cuando es de Biolivares a Dolar
      //los simbolos y las monedas 
      ///Verificar esta funcion
      //Rango de fecha 
      //Redondeo de 2 decimales
      //dos filas una para los creditos y otra de los debitos
      //ARCHIVO ADJUNTOS
       
        
      let monto_usd_result = monto.getRawValue() / tasa.getRawValue() ;
     

      monto_usd.setRawValue(monto_usd_result);
    
    });
   
     


  



    $('#newbtn').on('click', function(event) {
      event.preventDefault();
      $('#new').toggle().removeClass('d-none');
    });

    $('#newbtn2').on('click', function(event) {
      event.preventDefault();
      $('#new2').toggle().removeClass('d-none');
    });


  });

  
 
 

/*

Funcion cuanto



phone = document.querySelector("#phone"),
iti = window.intlTelInput(phone, {

  hiddenInput: "telefono",
  defaultCountry: "ve",
  preferredCountries: ['ve', 'co', 'us'],
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/utils.js"
});

*/
function checkPhone(){
 if (iti.isValidNumber()) {


  return true

} else {
  alert('Instroduce un télefono')
  return false;

}
}


$('#nuevo_user').on('click', function (e) {
    e.preventDefault();
    window.open("{{ url('movimientos-create-user')}}","nuevoUsuario", "width=500,height=680,scrollbars=no");

});
</script>
@endsection