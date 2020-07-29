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
      <h4>Account</h4>

      <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modelId">List account</a>
    </div>


    <div class="card-body">



      <form action="{{ url('cuentas') }}" onsubmit="return  checkPhone()" method="POST" accept-charset="utf-8">
        @csrf


        <div class="row">


          <div class="col-md-12">

            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
              <label class="form-control-label text-primary" for="input-name">{{ __('Nombre Cuenta:') }}</label>
              <input type="text" name="name" id="input-name"
                class="form-control  form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}"
                placeholder="{{ __(' Nombre Cuenta:') }}" value="{{ old('name', @$model->name) }}" required>

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
            <button type="submit" class="btn btn-primary">Crear Cuenta</button>
          </div>
        </div>

      </form>

    </div> {{--card-body  --}}
  </div> {{--card  --}}



  <div class="card mt-3">

    <div class="card-header text-primary font-weight-bold">
      <h4>Report account</h4>
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


          
          <div class="col-md-3">
            <div class="form-group  ">

              <select id="cuenta" name="cuenta_id"
                class=" form-control form-control-alternative{{ $errors->has('cuenta_id') ? ' is-invalid' : '' }}">
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





          

         



     


        </div>


        <div class="text-right">

          <div class="btn-group" role="group" aria-label="Basic example">

            <button type="submit" class="btn btn-primary">Filter</button>
            <a class="btn btn-warning" href="{{ url('cuentas', []) }}">Clear</a>

          </div>
        </div>

        
      </form>

      <hr>


      <table class="table table-striped" style="font-size: 13px">

        <thead class="text-primary">
          <tr>
            <th>ID</th>
            <th>Created</th>
            <th>Country</th>
            <th>Bank</th>
            <th>Number</th>
            <th>Type</th>
            <th>Acount In/Eg</th>
             
           
            
          
            <th class="text-right">Amount</th>
          
  
            <th class="text-right">Usd</th>
  
          </tr>
        </thead>
        <tbody>
  @php
      $sum = 0;
  @endphp
          @foreach ($model as $element)
  @php
      $sum += $element->monto;
  @endphp
          <tr>
            <td><a href="{{ url('movimientos/'.$element->id.'/edit') }}">{{$element->id}}</a></td>
            <td nowrap>{{$element->created_at->format('m-d-y')}}</td>
            <td><a href="{{ url('cuentas?filter=pais&val='.$element->banco->pais) }}"><span
                  class="flag-icon flag-icon-{{$element->banco->pais}}"></span></a></td>
            <td>{{$element->banco->name}} </td>
            <td><a href="{{ url('cuentas?bank_id='.$element->banco_id) }}">{{$element->banco->numero}}</a>
            </td>
            <td>{{$element->tipo}}</td>
            <td>{{$element->cuenta->name}}</td>
             
          
           
            
     
            <td nowrap class="text-right text-danger">
              {{($element->operacion === 'EGRESO')? currency($element->monto, $element->moneda) : '' }}
  
             
  
            </td>
         
            <td nowrap class="text-{{($element->monto_usd >= 0)?'success':'danger'}} text-right  ">
              {{currency($element->monto_usd, 'USD')}}</td>
  
          </tr>
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
            <td>Total</td>
            <td nowrap class="text-danger">{{currency($sum_debitos, $currency)}}</td>
            <td nowrap class="text-danger text-right text-danger">{{currency($sum_usd,'USD')}}</td>
            
  
  
  
  
          </tr>
  
  
         
  
        </tfoot>
        @endif
      </table>

      @if (@$_GET['start'])
      {{$model->appends(request()->input())->links()}}
          
      @endif
    </div> {{--card-body  --}}
  </div> {{--card  --}}



  <!-- Modal -->
  <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Account List</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-striped">

            <thead class="text-primary">
              <tr>
                <th>ID</th>

                <th>Nombre</th>

                <th></th>
              </tr>
            </thead>
            <tbody>

              @foreach ($model1 as $element)
              <tr>
                <td>{{$element->id}}</td>

                <td>{{$element->name}}</td>

                <td><a href="{{ url('cuentas/'.$element->id.'/edit') }}"><img width="30px"
                      src="{{ asset('img/icons/attended.svg') }}" alt=""></a></td>
              </tr>
              @endforeach

            </tbody>
          </table>

          {{$model1->links()}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>


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
  $("#emision").daterangepicker({
  singleDatePicker: true,
  showDropdowns: true,
  locale: {
    format: "MM-DD-YYYY",
  },
});

let currentYear = new Date().getFullYear();
let lastYear = currentYear - 1;
let startDate = moment();
let endDate = moment();
let yearStartDate = moment(currentYear + "-01-01");
let yearEndDate = moment(currentYear + "-12-01");

$("#creation-date-filter").daterangepicker(
  {
    opens: "center",
    startDate: startDate,
    endDate: endDate,
    ranges: {
      Hoy: [moment(), moment()],
      Ayer: [moment().subtract(1, "days"), moment().subtract(1, "days")],
      "Últimos 7 días": [moment().subtract(6, "days"), moment()],
      "Últimos 30 días": [moment().subtract(29, "days"), moment()],
      "Este mes": [moment().startOf("month"), moment().endOf("month")],
      "Mes pasado": [
        moment()
          .subtract(1, "month")
          .startOf("month"),
        moment()
          .subtract(1, "month")
          .endOf("month"),
      ],
    },
  },
  function(start, end, label) {
    $("#start").val(start.format("YYYY-MM-DD"));
    $("#end").val(end.format("YYYY-MM-DD"));
    console.log(
      "A new date selection was made: " +
        start.format("YYYY-MM-DD") +
        " to " +
        end.format("YYYY-MM-DD")
    );
  }
);

$("#start").val(startDate.format("YYYY-MM-DD"));
$("#end").val(endDate.format("YYYY-MM-DD"));


$("#bank").select2({
  theme: "bootstrap",
});

 

$("#cuenta").select2({
  theme: "bootstrap",
});

 
$("#currency").select2({
  theme: "bootstrap",
 });


 
</script>
@endsection