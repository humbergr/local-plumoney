@extends('layouts.coinbank-layout')

@php
setlocale(LC_MONETARY, 'en_US.UTF-8');
@endphp

@section('content')

<div class="container">
    <h5 class="dashboard__pageTitle mb-4 mt-3">Brokers</h5>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-none rounded-lg mb-3">
                <div class="card-body">
                    <h2 class="text-primary mb-0">{{ $publicist_count }}</h2>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">
                        Promotores financieros registrados
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none rounded-lg mb-3">
                <div class="card-body">
                    <h2 class="text-primary mb-0">{{ $total_referred_user_count }}</h2>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">
                        Usuarios referidos
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-none rounded-lg mb-3">
                <div class="card-body">
                    <h2 class="text-success mb-0">
                        {{ money_format("%!.2n", $total_current_debt) }} USD
                    </h2>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">
                        Deuda Total
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-none rounded-lg mb-4">
        <div class="card-header pt-lg-4">
            <div class="row">
                <div class="col-5 col-md-5 col-lg-4">
                    <h6 class="card-title font-weight-bold mb-0">Promotores financieros</h6>
                    <div class="text-muted font-13">Ultimos referidos registrados.</div>
                </div>
                <div class="col-7 col-md-7 col-lg-8">
                    <div class="text-right">
                        <button type="button"
                                class="btn btn-primary btn-pill btn-sm ws-nowrap px-3"
                                data-toggle="modal"
                                data-target="#addPromoCodeModal">
                            Agregar Nuevo +
                        </button>
                    </div>
                </div>
            </div>

            <!--<form action="" class="form-inline flex-wrap mt-4">
                <div class="input-group mb-2 mb-lg-0 mr-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text text-muted"><i class="fa fa-search"></i></div>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="filter-names"
                           placeholder="Nombre o correo electrónico">
                </div>
                <div class="input-group mb-2 mb-lg-0 mr-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text text-muted"><i class="fa fa-star-o"></i></div>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="filter-code"
                           placeholder="Código promocional">
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-pill px-3">Filtrar</button>
            </form>

            <ul class="filter__cloud list-inline mt-3 mb-0">
                <li class="list-inline-item mb-1">
                    <span class="font-14 text-muted mr-1 mr-md-3">Limpiar Filtro</span>
                </li>
                <li class="list-inline-item mb-1">
                    <a href="/admin/publicists" class="badge badge-primary badge-lg badge-pill">Limpiar</a>
                </li>
            </ul> -->


        </div>
        <div class="card-body">
            <ul class="transactions__list list-unstyled">
                <!-- show only 8 items per page -->

                @foreach($publicists as $p)
                <li class="transactionItem card rounded-lg mb-2">
                    <div class="transactionItem__body py-2">
                        <div class="form-row">
                            <div class="col-5 col-sm-5 col-md-4 col-lg-3 my-auto">
                                <h6 class="mb-0 text-truncate">
                                    <strong>
                                        <a href="/admin/user/{{$p['user']->id}}/{{ $p['code']->id }}"
                                           class="text-primary mb-0">
                                            {{ $p['user']->name }}
                                        </a>
                                    </strong>
                                </h6>
                                <div class="text-muted small text-truncate">
                                    {{ $p['user']->email }}
                                </div>
                            </div>
                            <div class="col-3 col-sm-4 col-md-2 col-lg-3 my-auto  text-sm-left">
                                @if ($p['code']->is_disabled)
                                <div class="lh-125 font-weight-bold">
                                    <del>{{ $p['code']->code }}</del>
                                </div>
                                <div class="text-muted small text-truncate">Código Deshbilitado</div>
                                @else
                                <div class="lh-125 font-weight-bold">
                                    {{ $p['code']->code }}
                                </div>
                                <div class="text-muted small text-truncate">Código Promocional</div>
                                @endif
                            </div>
                            <div class="col-6 col-sm-3 col-md-2 col-lg-2 my-auto d-none d-md-block text-sm-left">
                                <div class="lh-125 font-weight-bold">
                                    {{ $p['referrals_count'] }}
                                </div>
                                <div class="text-muted small text-truncate">Referidos</div>
                            </div>
                            <div class="col-4 col-sm-3 col-md-2 col-lg-2 my-auto text-right text-md-left">
                                <div class="text-success lh-125">
                                    {{ money_format("%!.2n", $p['debt']) }} {{ $p['debt_currency'] }}
                                </div>
                                <div class="text-muted small text-truncate">Deuda Pendiente</div>
                            </div>
                            <div class="col-12 col-sm-3 col-md-2 col-lg-2 my-auto d-none d-md-block text-right">
                                <a href="/admin/user-payments/{{ $p['user']->id }}/{{ $p['code']->id }}"
                                   class="btn btn-outline-primary btn-pill btn-sm px-3">Pagar</a>
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

<div class="modal fade" id="addPromoCodeModal" tabindex="-1" role="dialog" aria-labelledby="addPromoCodeModal"
     aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white border-bottom">
                <h6 class="modal-title" id="addPromoCodeModal">Código Promocional</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/save-new-code" id="saveCodeForm" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="text-primary" for="promo-code">Código</label>
                        <input id="promo-code"
                               type="text"
                               name="promo_code"
                               class="form-control form-control-lg"
                               placeholder="ABCD1234" required>
                    </div>
                    <div class="form-group">
                        <label class="text-primary" for="promo-user">Usuario promotor</label>
                        <input id="promo-user"
                               type="text"
                               name="user_email"
                               class="form-control"
                               placeholder="user@example.com" required>
                    </div>
                    <div class="form-group">
                        <label class="text-primary" for="promo-percentage">Porcentaje de Ganancia</label>
                        <div class="input-group mb-3">
                            <input id="profit-percent"
                                   type="number"
                                   step="0.1"
                                   name="profit_percent"
                                   class="form-control"
                                   placeholder="0.5" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-primary" for="promo-percentage">Bonus por registro</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">USD</span>
                            </div>
                            <input id="referal-bonus"
                                   type="number"
                                   step="0.1"
                                   name="referral_bonus"
                                   class="form-control"
                                   placeholder="1" required>
                        </div>
                        <small>* Bono en dólares por usuario registrado</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="submit" form="saveCodeForm" class="btn btn-secondary btn-pill btn-block" value="Agregar">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href="/js/vendor/jquery-flexdatalist-2.2.4/jquery.flexdatalist.min.css" rel="stylesheet" type="text/css">
<script src="/js/vendor/jquery-flexdatalist-2.2.4/jquery.flexdatalist.min.js" defer></script>
<script type="application/javascript" defer>
    $(function () {
        $('#promo-user').flexdatalist({
            url: '/api/search',
            minLength: 4,
            keywordParamName: 'search_keyword',
            visibleProperties: ['email', 'fullname'],
            searchContain: true,
            searchIn: ['email'],
        }).on('after:flexdatalist.search', function () {

        })
        //     .on('select:flexdatalist', function () {
        //     $('#search-form').submit();
        // });
    });
</script>
@stop