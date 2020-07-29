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
    <form class="" action="" method="get">

        <div class="row" style="margin-bottom:10px">
            <div class="col-md-1">
            </div>

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
                    <select id="banco" name="banco_id"
                        class="select2 form-control form-control-alternative{{ $errors->has('banco_id') ? ' is-invalid' : '' }}"
                        required>
                        <option value="all">Bank</option>

                        @foreach ( bancos() as $element)
                        {{-- expr --}}

                        <option data-moneda="{{$element->moneda}}" data-saldo2={{currency($element->saldo,'')}}
                            data-saldo={{$element->saldo}} value="{{$element->id}}"
                            {{ old('banco_id') == $element->id ? 'selected' : '' }}>{{$element->name}}
                            {{$element->numero}}
                              {{--   {{currency($element->saldo,$element->moneda)}} --}}
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


            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter by</button>
            </div>
        </div>
    </form>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-default">

                <div class="card-body">

                    <div class="row">
                        <table class="table table-striped table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">Type</h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">Transaction ID</h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">Amount</h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">Currency Amount</h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">USD Price/Cost</h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">Rate</h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">Account Name</h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">Date</h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">Profit / Loss</h5>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($model as $transaction)


                                <tr class="text-center">
                                    <td>

                                        @if ($transaction->operacion === 'INGRESO')
                                        <button class="btn btn-outline-secondary btn-sm btn-pill text-muted"
                                            data-toggle="modal" data-target="#transactions-details-modal">
                                            <img src="/img/cb-img/incoming-icon.png" class="button__img mr-1"
                                                height="20px">Incoming
                                        </button>
                                        @else
                                        <button class="btn btn-outline-secondary btn-sm btn-pill text-muted"
                                            data-toggle="modal" data-target="#transactions-details-modal">
                                            <img src="/img/cb-img/outgoing-icon.png" class="button__img mr-1"
                                                height="20px">Outgoing
                                        </button>
                                        @endif







                                    </td>
                                    <td> <a
                                            href="{{ url('lotedetalles/'.$transaction->lote_id, []) }}">{{$transaction->lote}}</a>
                                        <br><small>{{ $transaction->movimiento->banco->name }}</small></td>
                                    <td nowrap class="{{ $transaction->operacion === 'INGRESO' ? '':'text-danger' }}">
                                        {{ currency( $transaction->monto,$transaction->currency)}}</td>
                                    <td>{{$transaction->currency}}</td>
                                    <td>{{ number_format( $transaction->monto / $transaction->tasa,2) }} <br></td>
                                    <td>{{ number_format( $transaction->tasa,2)}}</td>
                                    <td>{{$transaction->movimiento->user->name }}</td>
                                    <td>{{ date('d M Y', strtotime($transaction->created_at)) }}</td>
                                    
                                    @if ($transaction->movimiento->cuenta_id === 9)
                                  <td>  <div class="text-danger">
                                   -
                                    </div>
                                    <div class="badge badge-primary text-center"> Feés Bank </div></td>

                                    @else
                                    <td nowrap
                                    class="font-weight-bold {{ ($transaction->getAnalisis()[1] >= 0) ? 'text-success' : 'text-danger' }}">
                                    {{  currency($transaction->getAnalisis()[1],'USD') }}</td>

                                    @endif
                                    
                                    

                                </tr>


                                @endforeach

                        </table>

                    </div>

                </div>
            </div>

            <div class="text-center">
                <nav aria-label="Page navigation" style="margin-top:10px;">
                    {{ $model->links() }}
                </nav>
            </div>



        </div>
    </div>

</div>

@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js"
    integrity="sha256-vucLmrjdfi9YwjGY/3CQ7HnccFSS/XRS1M/3k/FDXJw=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/moment.min.js"
    integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.js"
    integrity="sha256-iacRP5fv2z3yGk6gnwi/CjK8GRrr5MROIurU7iwYXRM=" crossorigin="anonymous"></script>
<script>
    jQuery(document).ready(function($) {

            $('#banco').select2({

              theme: "bootstrap"

                });

                $('#currency').select2({

                  theme: "bootstrap"

                     });
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

 
</script>
@endsection