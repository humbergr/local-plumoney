@extends('layouts.mvp-layout-internal')

@php
setlocale(LC_MONETARY, 'en_US.UTF-8');
@endphp

@section('content')

<main>
    <div class="container mt-md-n5">
        <!--<div class="nav-scroller mb-4">
            <nav class="nav flex-nowrap ws-nowrap bg-white rounded-lg">
                <a class="nav-link py-3" href="payments.html">Solicitar Pago</a>
                <a class="nav-link py-3 active" href="payment-history.html">Historial de Pagos</a>
                <a class="nav-link py-3" href="bank-account.html">Cuenta Bancaria</a>
            </nav>
        </div>-->

        <div class="row">
            <div class="col-xl-10 mx-auto">
                <div class="card shadow-none mb-4">
                    <div class="card-header pt-lg-4">
                        <div class="row">
                            <div class="col-sm-7">
                                <h6 class="card-title font-weight-bold mb-0">Historial</h6>
                                <div class="text-muted font-13">Últimos pagos realizados.</div>
                            </div>
                            <!--<div class="col-sm-5">
                                <form action="" class="form-inline justify-content-end flex-nowrap mt-2 mt-md-0">
                                    <div class="input-group mr-2">
                                        <input type="text" id="creation-date-filter" class="form-control" aria-label="Filtrar por fecha" aria-describedby="creation-date-filter">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white text-muted" id="creation-date-filter"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm btn-pill px-3">Filtrar</button>
                                </form>
                            </div>-->
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="transactions__list list-unstyled">
                            <!-- show only 8 items per page -->
                            @foreach ($payments as $p)
                            <li class="transactionItem card rounded-lg mb-2">
                                <div class="transactionItem__body py-2">
                                    <div class="row no-gutters">
                                        <div class="col-sm-4 my-auto">
                                            <h5 class="text-primary mb-0 text-truncate">
                                                <strong>
                                                    {{ money_format("%!.2n", $p['payment_total']) }}
                                                    {{ $p['currency'] }}
                                                </strong>
                                            </h5>
                                        </div>
                                        <div class="col-sm-4 my-auto">
                                            Enviado a: <strong>Billetera AKB</strong>
                                        </div>
                                        <div class="col-sm-4 my-auto">
                                            <div class="font-14 text-sm-right text-muted">
                                                {{ $p['payment_date'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>

                        <!-- Pagination links -->
                        {{ $payments->links('profile.referrals-paginator') }}
                    </div>
                    <div class="text-center pb-4">
                        <a href="/wallets" class="btn btn-secondary btn-pill">
                            <img class="d-none d-md-inline-block mr-2"
                                 src="/img/landing/simpleWallet-secondary.svg"
                                 height="32">Ir a la gestión de Billetera
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection