@extends('layouts.mvp-layout-internal')

@php
setlocale(LC_MONETARY, 'en_US.UTF-8');
@endphp

@section('content')

<main>
    <div class="container mt-md-n5">
        <div class="card shadow-none mb-4">

            <!-- At least one referral -->
            @if (count($referrals) > 0)

                <div class="card-header pt-lg-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="card-title font-weight-bold mb-0">Últimos Referidos</h6>
                            <div class="text-muted font-13">Ultimos referidos registrados.</div>
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
                        @foreach ($referrals as $r)
                            <li class="transactionItem card rounded-lg mb-2">
                                <div class="transactionItem__body py-2">
                                    <div class="form-row">
                                        <div class="col-8 col-sm-4 col-md-3 col-lg-3 my-auto">
                                            <h6 class="mb-0 text-truncate">
                                                <strong>
                                                    <a href="{{URL::to('/referrals/user/' . $r['user']['id'])}}" class="text-primary mb-0">
                                                        {{ $r['truncated_email'] }}
                                                    </a>
                                                </strong>
                                            </h6>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-3 col-lg-3 my-auto d-none d-sm-block text-sm-center font-14">
                                            <strong>{{ $r['transaction_count'] }}</strong> transacciones
                                        </div>
                                        <div class="col-4 col-sm-2 col-md-3 col-lg-3 my-auto text-right text-sm-center">
                                            <span class="text-success">+{{ money_format("%!.2n", $r['fiat_percentage']) }} USD</span>
                                        </div>
                                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 my-auto">
                                            <div class="font-14 text-right text-muted">{{ $r['user']->created_at }}</div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <!-- Pagination links -->
                    {{ $uses->links('profile.referrals-paginator') }}
                </div>

            <!-- No referrals, show promotion code instead -->
            @else

                <div class="card-body">
                    <div class="py-4 py-lg-5">
                        <div class="row">
                            <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                <img src="/img/landing/empty-transactions.svg" alt="Transactions Empty" class="img-fluid mb-4" style="max-height: 100px">
                                <h5 class="text-primary">Aun no tienes usuarios referidos</h5>
                                <h6 class="text-muted">Comienza a referir usuarios con tu código para mostrartelos aquí</h6>
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
