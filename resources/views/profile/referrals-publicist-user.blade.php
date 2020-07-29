@extends('layouts.mvp-layout-internal')

@php
setlocale(LC_MONETARY, 'en_US.UTF-8');
@endphp

@section('content')

<main>
    <div class="container mt-md-n5">
        <div class="row">
            <div class="col-md-4 col-lg-4 col-xl-3">
                <div class="card rounded-lg bg-primary text-white shadow-none mb-3" style="border: 1px solid #fff">
                    <div class="card-body  border-bottom">
                        <h5 class="mb-2" style="font-size: 17px"> {{ $truncated_email }} </h5>
                        <div class="font-14">Registrado: <strong> {{ $user->created_at }}</strong></div>
                        <div class="font-14">País: <strong>{{ $profile->country }}</strong></div>
                    </div>
                </div>
                <div class="card rounded-lg shadow-none mb-3">
                    <div class="card-body border-bottom">
                        <h3 class="text-primary mb-0">
                            {{ money_format("%!.2n", $total_processed_money) }}
                            {{ $total_processed_money_currency }}
                        </h3>
                        <div class="text-muted text-uppercase font-14 mt-n1">Total Dinero Procesado</div>
                    </div>
                    <div class="card-body">
                        <h4 class="text-success mb-0">
                            + {{ money_format("%!.2n", $total_earnings) }} {{ $total_earnings_currency }}
                        </h4>
                        <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Tus Ganancias Totales</div>
                    </div>
                </div>
                <div class="card rounded-lg shadow-none mb-3">
                    <div class="card-header">
                        <h6 class="card-title font-weight-bold mb-0">Transacciones</h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="form-row">
                            <div class="col-6 text-center border-right">
                                <h2 class="text-success mb-0">
                                    {{ $concreted_transactions }}
                                </h2>
                                <div class="text-muted text-uppercase font-14 mt-n1">Completadas</div>
                            </div>
                            <div class="col-6 text-center">
                                <h2 class="text-danger mb-0">
                                    {{ $non_concreted_transactions }}
                                </h2>
                                <div class="text-muted text-uppercase font-14 mt-n1">Fallidas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-8 col-xl-9">
                <div class="card rounded-lg shadow-none mb-4">
                    <div class="card-header pt-lg-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="card-title font-weight-bold mb-0">Bonificación de registro</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="transactions__list list-unstyled">
                            <li class="transactionItem card rounded-lg mb-2">
                                <div class="transactionItem__body py-2">
                                    <div class="form-row">
                                        <div class="col-6 col-sm-5 col-md-4 col-lg-6 my-auto">
                                            <h4 class="text-primary mb-0">
                                                {{ money_format("%!.2n", $code->referral_bonus) }}
                                                USD
                                            </h4>
                                            <div class="font-13 ws-nowrap text-truncate">
                                                <span class="d-none d-sm-inline">Usuario:</span>
                                                <strong>
                                                    <a href="user.html" class="text-primary mb-0">
                                                        {{ $truncated_email }}
                                                    </a>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-4 col-lg-3 my-auto d-none d-sm-block text-sm-center">
                                            <span class="badge badge-success badge-pill my-1 my-md-0">
                                                Completado
                                            </span>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 my-auto">
                                            <div class="ws-nowrap text-right">
                                                <h5 class="d-inline-block text-success mb-0"
                                                    title="Ganancia">
                                                    {{ money_format("%!.2n", $code->referral_bonus) }}
                                                    USD
                                                </h5>
                                                <div>
                                                    <span class="font-13 text-muted">
                                                        {{ $user->created_at }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card rounded-lg shadow-none mb-4">
                    <div class="card-header pt-lg-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="card-title font-weight-bold mb-0">Transacciones de envío</h6>
                                <div class="text-muted font-13">Últimas operaciones realizadas por el usuario.</div>
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

                    @if (count($data) > 0)

                    <div class="card-body">
                        <ul class="transactions__list list-unstyled">
                            @foreach( $data as $d )
                            <li class="transactionItem card rounded-lg mb-2">
                                <div class="transactionItem__body py-2">
                                    <div class="form-row">
                                        <div class="col-6 col-sm-5 col-md-4 col-lg-6 my-auto">
                                            <h4 class="text-primary mb-0">
                                                {{ money_format("%!.2n", $d['transaction']->sender_fiat_amount) }}
                                                {{ $d['transaction']->sender_fiat }}
                                            </h4>
                                            <div class="font-13 ws-nowrap text-truncate"><span
                                                        class="d-none d-sm-inline">Usuario:</span> <strong><a
                                                            href="user.html" class="text-primary mb-0">
                                                        {{ $truncated_email }}
                                                    </a></strong></div>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-4 col-lg-3 my-auto d-none d-sm-block text-sm-center">
                                                    <span class="badge {{ $d['badge'] }} badge-pill my-1 my-md-0">
                                                        {{$d['badge_text']}}
                                                    </span>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 my-auto">
                                            <div class="ws-nowrap text-right">
                                                <h5 class="d-inline-block text-success {{ $d['strike'] }} mb-0"
                                                    title="Ganancia">
                                                    + {{ money_format("%!.2n", $d['commission_total']) }}
                                                    USD
                                                    <small>( {{ $d['commission_percentage'] }})</small>
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

                    @else

                    <div class="card-body">
                        <div class="py-4 py-lg-5">
                            <div class="row">
                                <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                    <img src="/img/landing/empty-transactions.svg" alt="Transactions Empty"
                                         class="img-fluid mb-4" style="max-height: 100px">
                                    <h5 class="text-primary">Actualmente el historial de transacciones esta vacío</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif
                </div>
                <div class="card rounded-lg shadow-none mb-4">
                    <div class="card-header pt-lg-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="card-title font-weight-bold mb-0">Transacciones de Billetera</h6>
                                <div class="text-muted font-13">Últimas operaciones realizadas por el usuario.</div>
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

                    @if (count($wallets_data) > 0)

                    <div class="card-body">
                        <ul class="transactions__list list-unstyled">
                            @foreach( $wallets_data as $d )
                            <li class="transactionItem card rounded-lg mb-2">
                                <div class="transactionItem__body py-2">
                                    <div class="form-row">
                                        <div class="col-6 col-sm-5 col-md-4 col-lg-6 my-auto">
                                            <h4 class="text-primary mb-0">
                                                {{ money_format("%!.2n", $d['transaction']->sender_fiat_amount) }}
                                                {{ $d['transaction']->sender_fiat }}
                                            </h4>
                                            <div class="font-13 ws-nowrap text-truncate"><span
                                                        class="d-none d-sm-inline">Usuario:</span> <strong><a
                                                            href="user.html" class="text-primary mb-0">
                                                        {{ $truncated_email }}
                                                    </a></strong></div>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-4 col-lg-3 my-auto d-none d-sm-block text-sm-center">
                                                    <span class="badge {{ $d['badge'] }} badge-pill my-1 my-md-0">
                                                        {{$d['badge_text']}}
                                                    </span>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 my-auto">
                                            <div class="ws-nowrap text-right">
                                                <h5 class="d-inline-block text-success {{ $d['strike'] }} mb-0"
                                                    title="Ganancia">
                                                    + {{ money_format("%!.2n", $d['commission_total']) }}
                                                    USD
                                                    <small>( {{ $d['commission_percentage'] }})</small>
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

                    @else

                    <div class="card-body">
                        <div class="py-4 py-lg-5">
                            <div class="row">
                                <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                    <img src="/img/landing/empty-transactions.svg" alt="Transactions Empty"
                                         class="img-fluid mb-4" style="max-height: 100px">
                                    <h5 class="text-primary">Actualmente el historial de transacciones esta vacío</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
