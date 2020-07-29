@extends('layouts.coinbank-layout')

@php
setlocale(LC_MONETARY, 'en_US.UTF-8');
@endphp

@section('content')

<div class="container">
    <h5 class="dashboard__pageTitle mb-4 mt-3">Users Wallet</h5>

    <div class="card shadow-none rounded-lg mb-4">
        <div class="card-header pt-lg-4">
            <div class="row">
                <div class="col-5 col-md-5 col-lg-4">
                    <h6 class="card-title font-weight-bold mb-0">Datos financieros</h6>
                    <div class="text-muted font-13">Datos de Wallets.</div>
                </div>
            </div>

            <form action="" class="form-inline flex-wrap mt-4">
                <div class="input-group mb-2 mb-lg-0 mr-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text text-muted"><i class="fa fa-search"></i></div>
                    </div>
                    <input type="text" name="user-name" class="form-control form-control-sm" id="filter-names"
                           placeholder="Nombre" value="{{request()['user-name']}}">
                </div>
                <div class="input-group mb-2 mb-lg-0 mr-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text text-muted"><i class="fa fa-search"></i></div>
                    </div>
                    <input type="text" name="user-lastname" class="form-control form-control-sm" id="filter-lastnames"
                           placeholder="Apellido" value="{{request()['user-lastname']}}">
                </div>
                <div class="input-group mb-2 mb-lg-0 mr-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text text-muted"><i class="fa fa-star-o"></i></div>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="filter-code"
                           placeholder="Email">
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-pill px-3">Filtrar</button>
            </form>

            <ul class="filter__cloud list-inline mt-3 mb-0">
                
                @if (request()['user-name'] || request()['user-lastname'] || request()['user-email'] || request()['date-range'] )
                <li class="list-inline-item mb-1">
                    <span class="font-14 text-muted mr-1 mr-md-3">Limpiar Filtro</span>
                </li>

                <li class="list-inline-item mb-1">
                    <a href="/wallets/users" class="badge badge-primary badge-lg badge-pill">Limpiar</a>
                </li>
                @endif
            </ul>


        </div>
        <div class="card-body">
            <ul class="transactions__list list-unstyled">
                <!-- show only 8 items per page -->

                @foreach($users as $u)
                <li class="transactionItem card rounded-lg mb-2">
                    <div class="transactionItem__body py-2">
                        <div class="form-row">
                            <div class="col-6 col-sm-5 col-md-6 col-lg-6 my-auto">
                                <h6 class="mb-0 text-truncate">
                                    <strong>
                                        <a href="/user-profile/{{ $u->id }}"
                                            target="_blank"
                                           class="text-primary mb-0">
                                            {{ $u->name }}
                                        </a>
                                    </strong>
                                </h6>
                                <div class="text-muted small text-truncate">
                                    Usuario {{ $u->created_at }}
                                </div>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-3 my-auto d-none d-md-block text-sm-left">
                                <div class="lh-125 font-weight-bold">
                                    @foreach ($u->currencyWallet as $currentyWallet)
                                        {{number_format($currentyWallet->numbers['available'], 2)}} {{$currentyWallet['currency']}}
                                    @endforeach
                                </div>
                                <div class="text-muted small text-truncate">Dinero Disponible</div>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-3 my-auto d-none d-md-block text-sm-left">
                                <div class="lh-125 font-weight-bold">
                                    @foreach ($u->currencyWallet as $currentyWallet)
                                        {{ count($currentyWallet->relatedTransactions) }}
                                    @endforeach
                                </div>
                                <div class="text-muted small text-truncate">Transacciones Realizadas</div>
                            </div>
                            
                        </div>
                    </div>
                </li>
                @endforeach

            </ul>
            <div class="d-flex justify-content-center mt-3">
                {{ $users->appends(request()->all())->links() }}
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