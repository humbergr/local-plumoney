@extends('layouts.mvp-layout-internal')

@php
setlocale(LC_MONETARY, 'en_US.UTF-8');
@endphp

@section('content')

<main>
    <div class="container mt-md-n5">
        <div class="card shadow-none mb-4">
            
            <!-- there's at least one referral transaction done -->
            @if (count($data) > 0)

                <div class="card-header pt-lg-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="card-title font-weight-bold mb-0">Últimas Transacciones de Envío</h6>
                            <div class="text-muted font-13">Últimas operaciones realizadas por tus referidos.</div>
                        </div>
                        <div class="col-md-6">
                            <!--<form action="" class="form-inline justify-content-end flex-nowrap mt-2 mt-md-0">
                                <div class="input-group mr-2">
                                    <input type="text" id="creation-date-filter" class="form-control" aria-label="Filtrar por fecha" aria-describedby="creation-date-filter">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white text-muted" id="creation-date-filter"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm btn-pill px-3">Filtrar</button>
                            </form>-->
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="transactions__list list-unstyled">
                        
                        @foreach ($data as $d)
                            <li class="transactionItem card rounded-lg mb-2">
                                <div class="transactionItem__body py-2">
                                    <div class="form-row">
                                        <div class="col-6 col-sm-5 col-md-4 col-lg-6 my-auto">
                                            <h4 class="text-primary mb-0">
                                                {{ money_format("%!.2n", $d['transaction']->sender_fiat_amount) }}
                                                {{ $d['currency'] }}
                                            </h4>
                                            <div class="font-13 ws-nowrap text-truncate">
                                                <span class="d-none d-sm-inline">
                                                    Usuario:
                                                </span> 
                                                <strong>
                                                    <a href="{{URL::to('/referrals/user/' . $d['user']['id'])}}" class="text-primary mb-0">
                                                        {{ $d['truncated_email'] }}
                                                    </a>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-4 col-lg-3 my-auto d-none d-sm-block text-sm-center">
                                            <span class="badge {{$d['badge']}} badge-pill my-1 my-md-0">
                                                {{$d['badge_text']}}
                                            </span>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 my-auto">
                                            <div class="ws-nowrap text-right">
                                                <h5 class="d-inline-block text-success {{ $d['strike'] }} mb-0" title="Ganancia">
                                                    + {{ money_format("%!.2n", $d['commission_total']) }}
                                                      USD
                                                    <small>
                                                    ({{ $d['commission_percentage'] }})
                                                    </small>
                                                </h5>
                                                <div>
                                                    <span class="font-13 text-muted">
                                                        {{ $d['transaction']->created_at }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>

                    {{ $paginator->links('profile.referrals-paginator') }}

                </div>

            <!-- there's no referral transactions done -->
            @else  

                <div class="card-body">
                    <div class="py-4 py-lg-5">
                        <div class="row">
                            <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                <img src="/img/landing/empty-transactions.svg" alt="Transactions Empty" class="img-fluid mb-4" style="max-height: 100px">
                                <h5 class="text-primary">Actualmente el historial de transacciones esta vacío</h5>
                                <h6 class="text-muted">Todas las transacciones que tus referidos hagan se irán mostrando aquí</h6>
                                <hr class="mt-5">
                                <div class="text-muted mt-3">Invita nuevos usuarios que se registren con tu código<br>
                                    <span class="d-inline-block border-dotted-3 bg-secondary-light border-secondary p-1 text-center mt-2">
                                        <h6 class="text-primary font-weight-bold mb-0">
                                            {{ $code }}
                                        </h6>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        </div>

        <div class="card shadow-none mb-4">

            <!-- there's at least one referral transaction done -->
            @if (count($wallets_data) > 0)

                <div class="card-header pt-lg-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="card-title font-weight-bold mb-0">Últimas Transacciones de Billetera</h6>
                            <div class="text-muted font-13">Últimas operaciones realizadas por tus referidos.</div>
                        </div>
                        <div class="col-md-6">
                            <!--<form action="" class="form-inline justify-content-end flex-nowrap mt-2 mt-md-0">
                                <div class="input-group mr-2">
                                    <input type="text" id="creation-date-filter" class="form-control" aria-label="Filtrar por fecha" aria-describedby="creation-date-filter">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white text-muted" id="creation-date-filter"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm btn-pill px-3">Filtrar</button>
                            </form>-->
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="transactions__list list-unstyled">

                        @foreach ($wallets_data as $d)
                            <li class="transactionItem card rounded-lg mb-2">
                                <div class="transactionItem__body py-2">
                                    <div class="form-row">
                                        <div class="col-6 col-sm-5 col-md-4 col-lg-6 my-auto">
                                            <h4 class="text-primary mb-0">
                                                {{ money_format("%!.2n", $d['transaction']->sender_fiat_amount) }}
                                                {{ $d['currency'] }}
                                            </h4>
                                            <div class="font-13 ws-nowrap text-truncate">
                                                <span class="d-none d-sm-inline">
                                                    Usuario:
                                                </span>
                                                <strong>
                                                    <a href="{{URL::to('/referrals/user/' . $d['user']['id'])}}" class="text-primary mb-0">
                                                        {{ $d['truncated_email'] }}
                                                    </a>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-4 col-lg-3 my-auto d-none d-sm-block text-sm-center">
                                            <span class="badge {{$d['badge']}} badge-pill my-1 my-md-0">
                                                {{$d['badge_text']}}
                                            </span>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 my-auto">
                                            <div class="ws-nowrap text-right">
                                                <h5 class="d-inline-block text-success {{ $d['strike'] }} mb-0" title="Ganancia">
                                                    + {{ money_format("%!.2n", $d['commission_total']) }}
                                                      USD
                                                    <small>
                                                    ({{ $d['commission_percentage'] }})
                                                    </small>
                                                </h5>
                                                <div>
                                                    <span class="font-13 text-muted">
                                                        {{ $d['transaction']->created_at }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>

                    {{ $wallets_paginator->links('profile.referrals-paginator') }}

                </div>

            <!-- there's no referral transactions done -->
            @else

                <div class="card-body">
                    <div class="py-4 py-lg-5">
                        <div class="row">
                            <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                <img src="/img/landing/empty-transactions.svg" alt="Transactions Empty" class="img-fluid mb-4" style="max-height: 100px">
                                <h5 class="text-primary">Actualmente el historial de transacciones esta vacío</h5>
                                <h6 class="text-muted">Todas las transacciones que tus referidos hagan se irán mostrando aquí</h6>
                                <hr class="mt-5">
                                <div class="text-muted mt-3">Invita nuevos usuarios que se registren con tu código<br>
                                    <span class="d-inline-block border-dotted-3 bg-secondary-light border-secondary p-1 text-center mt-2">
                                        <h6 class="text-primary font-weight-bold mb-0">
                                            {{ $code }}
                                        </h6>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>
</main>

<script type="text/javascript">
    $(function() {
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#creation-date-filter').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#creation-date-filter').daterangepicker({
            opens: 'center',
            startDate: start,
            endDate: end,
            locale: {
                "customRangeLabel": "Seleccionar rango",
            },
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
    });
</script>

@endsection