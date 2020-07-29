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





  <div class="card mt-3">

    <div class="card-header text-primary font-weight-bold">
      <h4>Batch Transaction Adds</h4>
      <div class="text-primary font-weight-bold">Credit:  <span class="text-success">{{ currency($cre,'USD$ ') }}</span>    </div>
      <div class="text-primary font-weight-bold">Debit:  <span class="text-danger">{{ currency($deb,'USD$ ') }}</span>    </div>
      <div class="text-primary font-weight-bold">Profit Margin Obtained Balance:  <span class="{{  $sum >= 0 ? 'text-success' : 'text-danger'}}">{{ currency($sum,'USD$ ') }}</span>    </div>
    </div>



    <div class="card-body">
      <form class="" action="" method="get">

        <div class="row" style="margin-bottom:10px">



          <div class="col-md-9"></div>




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

        <div class="text-right m-3">
          <div class="btn-group" role="group" aria-label="Basic example">

            <button type="submit" class="btn btn-primary">Filter</button>
            <a class="btn btn-warning" href="{{ url('lotedetalles-banco/'.$id, []) }}">Clear</a>

          </div>
        </div>
      </form>

      <table class="table table-striped">

        <thead class="text-primary">
          <tr>
            <th>Date</th>

            <th>Batch Number</th>
            <th>Bank Account Name</th>
            <th>Fiat</th>
            <th>Initial Balance</th>
            <th>Balance</th>
            <th>Profit</th>

            <th></th>
          </tr>
        </thead>
        <tbody>

          @foreach ($model as $element)
          <tr class="{{ ($element->active) ? '' : 'bg-danger text-white' }}">
            <td>{{$element->created_at->format('m/d/Y')}}</td>

            <td> <a href="{{ url('lotedetalles/'.$element->id) }}">{{$element->lote}}</a></td>
            <td>{{$element->banco->name}} <span class="text-muted"> ({{$element->banco->numero}})</span></td>
            <td>{{ $element->currency}}</td>
            <td>{{currency($element->monto,'')}}</td>
            <td>{{currency($element->saldo,'')}}</td>
            <td class=" {{ $element->profit() >= 0 ? 'text-success' : 'text-danger'  }}" >{{currency($element->profit(),'USD$ ')}}</td>
            @if ($element->active)
            <td><a class="btn btn-danger" onclick="return confirm('Cancel Batch?')"
                href="{{ url('lotedetalles-cancel/'.$element->id) }}">Void Batch </a>
            </td>

            @else
            <td> Void
            </td>
            @endif
          </tr>
          @endforeach

        </tbody>
      </table>

      {{$model->links()}}

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
  let currentYear = new Date().getFullYear();
  let lastYear = currentYear - 1;
  let startDate = moment();
  let endDate = moment();
  let yearStartDate = moment(currentYear + '-01-01');
  let yearEndDate = moment(currentYear + '-12-01');

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




$('#currency').select2({

theme: "bootstrap",
width:'250'

});


$('#bank').select2({

theme: "bootstrap"

});


$("#country2").countrySelect({
defaultCountry: "ve",
preferredCountries: ['ve', 'co', 'us'],
});


$("#country2").countrySelect({
defaultCountry: "ve",
preferredCountries: ['ve', 'co', 'us'],
});
</script>
@endsection