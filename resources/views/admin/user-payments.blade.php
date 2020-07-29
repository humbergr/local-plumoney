@extends('layouts.coinbank-layout')

@php
setlocale(LC_MONETARY, 'en_US.UTF-8');
@endphp

@section('content')

<div class="container">
    <h5 class="dashboard__pageTitle mb-4 mt-3">Historial de Pagos</h5>
    <div class="row mb-4">
        <div class="col-9 col-md-9">
            <div class="media">
                <div class="avatar-90 mr-3 flex-shrink-0 mb-3 mb-md-0">
                    <img src="/{{$profile->selfie}}" class="object-cover">
                </div>
                <div class="media-body">
                    <h6 class="mb-0 lh-125 text-muted font-14 text-uppercase">Promotor financiero</h6>
                    <h3 class="text-primary mb-0">{{ $user->name }}</h3>
                    <h6 class="font-14 mb-2">{{ $user->email }}</h6>
                    <h6 class="font-14 mb-0">Registrado: <strong>{{ $user->created_at }}</strong></h6>
                    <h6 class="font-14 mb-0">País: <strong>{{ $profile->country }}</strong></h6>
                    <div class="border-dotted-3 bg-secondary-light border-secondary px-2 py-1 mt-2 text-center"
                         style="max-width: 180px">
                        @if ($code->is_disabled)
                        <h6 class="text-primary font-weight-bold mb-0">
                            <del>{{$code->code}}</del>
                            <br>
                            <small>Código deshabilitado.</small>
                        </h6>
                        @else
                        <h6 class="text-primary font-weight-bold mb-0">{{$code->code}}</h6>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="nav-scroller mb-4">
        <nav class="nav flex-nowrap ws-nowrap bg-white rounded-lg">
            <a class="nav-link py-3" href="/admin/user/{{ $user->id }}/{{ $code->id }}">Resumen</a>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-3">
            <div class="card shadow-none rounded-lg mb-3">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <h4 class="text-primary mb-0">
                            {{ money_format("%!.2n", $current_debt) }}
                            <small>USD</small>
                        </h4>
                        <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Deuda Actual</div>
                    </div>
                    @if ($current_debt > 0)
                    <div>
                        <button class="btn btn-secondary btn-pill btn-sm px-3" data-toggle="modal"
                                data-target="#pay-modal">Pagar
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!--<div class="card shadow-none rounded-lg mb-3">
                <div class="card-body">
                    <h6 class="card-title font-weight-bold text-primary">Cuenta Bancaria</h6>
                    <ul class="list-unstyled font-14 lh-125 mb-0">
                        <li> {{$user->name}}</li>
                        <li>Bank Of America</li>
                        <li>0000-1234-5678-9123</li>
                        <li>Routing number: 123456790</li>
                    </ul>
                </div>
            </div>-->
        </div>
        <div class="col-md-8 col-lg-8 col-xl-9">
            <div class="card shadow-none mb-4">
                <div class="card-header pt-lg-4">
                    <div class="row">
                        <div class="col-sm-7">
                            <h6 class="card-title font-weight-bold mb-0">Historial</h6>
                            <div class="text-muted font-13">Últimos pagos enviados</div>
                        </div>
                        <div class="col-sm-5">
                            <!--<form action="" class="form-inline justify-content-end flex-nowrap mt-2 mt-md-0">
                                <div class="input-group mr-2">
                                    <input type="text" id="creation-date-filter" class="form-control"
                                           aria-label="Filtrar por fecha" aria-describedby="creation-date-filter">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white text-muted" id="creation-date-filter"><i
                                                    class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm btn-pill px-3">Filtrar</button>
                            </form>-->
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="transactions__list list-unstyled">
                        <!-- show only 8 items per page -->

                        @foreach($list_data as $d)
                        <li class="transactionItem card rounded-lg mb-2">
                            <div class="transactionItem__body py-2">
                                <div class="row no-gutters">
                                    <div class="col-sm-4 my-auto">
                                        <h5 class="text-primary mb-0 text-truncate">
                                            <strong>
                                                {{ $d->payment_total }}
                                                {{ $d->currency }}
                                            </strong>
                                        </h5>
                                    </div>
                                    <div class="col-sm-4 my-auto">
                                        Enviado a: <strong>Sistema de Wallets <i>Mia</i>.</strong>
                                    </div>
                                    <div class="col-sm-4 my-auto">
                                        <div class="font-14 text-sm-right text-muted">
                                            {{ $d->payment_date }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach

                    </ul>

                    {{ $paginator->links('profile.referrals-paginator') }}

                </div>
            </div>
        </div>
    </div>
</div>

<!-- payment modal -->
<div class="modal fade" id="pay-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="/admin/user-payments-pay/{{ $user->id }}/{{ $code->id }}" method="post">
            {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header bg-primary text-white px-lg-4">
                    <div>
                        <h6 class="modal-title">Realizar Pago</h6>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-lg-4">
                    <div class="border p-3 mb-4">
                        <div class="font-14 text-muted text-uppercase">Total a Pagar</div>
                        <h4 class="text-primary font-weight-bold mb-0">{{ money_format("%!.2n", $current_debt) }}
                            USD</h4>
                    </div>
                    <h6 class="text-primary font-weight-bold mb-3">Seleccione a dónde se enviará el dinero</h6>
                    <!--<div class="inputRadio__wrapper">
                        <input type="radio" id="bank-acc" name="payMethod" class="inputRadio">
                        <label for="bank-acc" class="inputRadio__label">Cuenta Bancaria Asociada</label>
                        <div class="inputRadio__content mb-3 font-14 p-2">
                            <div>Caterina Valentino</div>
                            <div>Bank Of America</div>
                            <div>0000-1234-5678-9123</div>
                            <div>Routing number: 123456790</div>
                        </div>
                    </div>-->
                    <div class="inputRadio__wrapper">
                        <input type="radio" readonly checked id="wallet" name="payMethod" class="inputRadio">
                        <label for="wallet" class="inputRadio__label">Billetera de AKB</label>
                        <div class="inputRadio__content mb-3 font-14 p-2">
                            E-mail: {{ $user->email }}
                        </div>
                    </div>
                    <!--<hr>
                    <div class="text-center">
                        <div class="custom-control custom-switch mb-2">
                            <input type="checkbox" class="custom-control-input" id="mark-as-paid">
                            <label class="custom-control-label" for="mark-as-paid">Marcar como pagado.</label>
                        </div>
                    </div>-->
                </div>
                <div class="modal-footer justify-content-between text-center">
                    <a class="btn btn-light btn-pill px-4" data-dismiss="modal">Cancelar</a>
                    <input type="submit" class="btn btn-secondary btn-pill px-4" value="Hecho"/>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection