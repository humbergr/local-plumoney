@extends('layouts.coinbank-layout')

@php
setlocale(LC_MONETARY, 'en_US.UTF-8');
use Carbon\Carbon;
@endphp

@section('content')

<div class="container">
    <h5 class="dashboard__pageTitle mb-4 mt-3">Detalles del promotor financiero</h5>

    <div class="row mb-4">
        <div class="col-9 col-md-9">
            <div class="media">
                <div class="avatar-90 mr-3 flex-shrink-0 mb-3 mb-md-0">
                    <img src="/{{$profile->selfie}}" class="object-cover">
                </div>
                <div class="media-body">
                    <h6 class="mb-0 lh-125 text-muted font-14 text-uppercase">Promotor financiero</h6>
                    <a href="/user-profile/{{ $user->id }}">
                        <h3 class="text-primary mb-0">{{ $user->name }}</h3>
                    </a>

                    <h6 class="font-14 mb-2"> {{ $user->email }} </h6>
                    <h6 class="font-14 mb-0">Registrado: <strong>{{ $user->created_at }}</strong></h6>
                    <h6 class="font-14 mb-0">País: <strong>{{ $profile->country }}</strong></h6>
                    <div class="border-dotted-3 bg-secondary-light border-secondary px-2 py-1 mt-2 text-center" style="max-width: 180px">
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
            <div class="alert alert-info mt-3">
                <div class="media">
                    <img src="/img/landing/info-icon.svg" class="alert-icon mr-3">
                    <div class="media-body">
                        Enlace de referidos:
                        <br>
                        <strong>
                            https://americankryptosbank.com/signin?code={{ $code->code }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 col-md-3 text-md-right">
            @if ($code->is_disabled)
            <p class="text-warning">
                Éste código se encuentra deshabilitado.
            </p>
            @else
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="javascript:void(0)" class="btn btn-outline-primary btn-pill btn-sm mt-2 mt-md-0 mb-md-2 px-3" data-toggle="modal" data-target="#editPublicist">
                        Editar
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="javascript:void(0)" class="btn btn-danger btn-pill btn-sm mt-2 mt-md-0 mb-md-2 px-3" data-toggle="modal" data-target="#disableCode">
                        Deshabilitar
                    </a>
                </li>
            </ul>
            @endif
        </div>
    </div>
    <h5 class="dashboard__pageTitle mb-4 mt-3">Graficas de Registro</h5>


    <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
        <form action="" method="get" class="form-inline flex-md-nowrap ml-md-3">
            
            {{ csrf_field() }}
            <div class="input-group mb-3 mb-md-0 mr-3" style="width: 250px">

                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                </div>

                <select name="searchBy" id="searchBy" class="form-control">
                            <option {{ !(request()['searchBy']) ? 'selected':'' }} disabled>Buscar por</option>
                            <option {{ request()['searchBy'] == '1' ? 'selected':'' }} value="1">Mes</option>
                            <option {{ request()['searchBy'] == '2' ? 'selected':'' }} value="2">Rango de Fecha</option>
                </select>
            </div>

            <div class="input-group mb-3 mb-md-0 mr-3" id="searchByRange" style="width: 250px" {{ (request()['searchBy']) == 2  ? '':'hidden="true"' }}>

                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                </div>

                <input type="text"
                           name="chart-date"
                           id="creation-date-filter-chart"
                           class="form-control"
                           aria-label="Creation date filter"
                           aria-describedby="creation-date-filter"
                           value="{{request()['chart-date']}}">
            </div>

            <div class="input-group mb-3 mb-md-0 mr-3" id="searchByMonth" style="width: 250px" {{ (request()['searchBy']) == 1  ? '':'hidden="true"' }}>

                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                </div>

                <select name="chart-date-month" id="date-select" class="form-control">
                    <option value="" {{ !(request()['searchBy']) ? 'selected':'' }} disabled>Seleccione un Mes</option>       
                </select>

            </div>
            
           
            <div class="input-group mb-3 mb-md-0">
                <input type='submit' id="getChart" class="ml-3 btn btn-primary" value="Filtrar">
            </div>

            @if (request()['chart-date'])
            <div class="input-group mb-3 mb-md-0">
                <a href="/admin/user/{{ $user->id }}/{{ $code->id }}" class="ml-3 btn btn-primary">Limpiar</a>
            </div>
            @endif

        </form>
    </div>
    
    <div class="container" id="usersCharts" style="display: none;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <canvas id="canvas" height="280" width="600"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="nav-scroller mb-4">
        <nav class="nav flex-nowrap ws-nowrap bg-white rounded-lg">
            <a class="nav-link py-3" href="/admin/user-payments/{{ $user->id }}/{{ $code->id }}">Pagos</a>
        </nav>
    </div>

    <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
        <form action="" method="get" class="form-inline flex-md-nowrap ml-md-3">
            <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                </div>
                {{-- <input type="text" class="form-control" name="user-name" value="{{request()['user-name']}}" placeholder="Buscar por nombre"> --}}
                    <select name="status" id="status" class="form-control">
                        <option {{ !(request()['status']) ? 'selected':'' }} disabled>Seleccione un estado</option>
                        <option {{ request()['status'] == '0' ? 'selected':'' }} value="0">Sin Completar</option>
                        <option {{ request()['status'] == '1' ? 'selected':'' }} value="1">Perfiles En Espera</option>
                        <option {{ request()['status'] == '2' ? 'selected':'' }} value="2">Aprobados</option>
                        <option {{ request()['status'] == '3' ? 'selected':'' }} value="3">Rechazados</option>
                        <option {{ request()['status'] == 'block' ? 'selected':'' }} value="block">Bloqueados</option>
                    </select>
            </div>
            <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                </div>
                {{-- <input type="text" class="form-control" name="user-name" value="{{request()['user-name']}}" placeholder="Buscar por nombre"> --}}
                    <select name="transactions" id="transactions" class="form-control">
                        <option {{ !(request()['transactions']) ? 'selected':'' }} disabled>Ordenar Por Operaciones</option>
                        <option {{ request()['transactions'] == '2' ? 'selected':'' }} value="1">Realizaron Operaciones</option>
                        <option {{ request()['transactions'] == '2' ? 'selected':'' }} value="2">Realizaron Operaciones de Billetera</option>
                    </select>
            </div>
            <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                </div>
                <input type="text"
                           name="date-range"
                           id="creation-date-filteer"
                           class="form-control"
                           aria-label="Creation date filter"
                           aria-describedby="creation-date-filter"
                           value="{{request()['date-range']}}">
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    <select name="country"
                            id="person-select-country"
                            required
                            class="custom-select flag-selector flag-selector--full">
                            <option {{ !(request()['country']) ? 'selected':'' }} disabled>Elegir un País</option>
                        <optgroup label="Sur América">
                            <option
                                {{ request()['country'] == 'VE' ? 'selected':'' }}
                                value="VE"
                                data-flag="/img/landing/flags/ve.svg">Venezuela
                            </option>
                            <option
                                {{ request()['country'] == 'CO' ? 'selected':'' }}
                                value="CO"
                                data-flag="/img/landing/flags/co.svg">Colombia
                            </option>
                            <option
                                {{ request()['country'] == 'PE' ? 'selected':'' }}
                                value="PE"
                                data-flag="/img/landing/flags/pe.svg">Perú
                            </option>
                            <option
                                {{ request()['country'] == 'CL' ? 'selected':'' }}
                                value="CL"
                                data-flag="/img/landing/flags/cl.svg">Chile
                            </option>
                            <option
                                {{ request()['country'] == 'AR' ? 'selected':'' }}
                                value="AR"
                                data-flag="/img/landing/flags/ar.svg">Argentina
                            </option>
                            <option
                                {{ request()['country'] == 'BR' ? 'selected':'' }}
                                value="BR"
                                data-flag="/img/landing/flags/br.svg">Brazil
                            </option>
                            <option
                                {{ request()['country'] == 'EC' ? 'selected':'' }}
                                value="EC"
                                data-nb="true"
                                data-flag="/img/landing/flags/ec.svg">Ecuador
                            </option>
                            <option
                                {{ request()['country'] == 'BO' ? 'selected':'' }}
                                value="BO"
                                data-nb="true"
                                data-flag="/img/landing/flags/bo.svg">Bolivia
                            </option>
                            <option
                                {{ request()['country'] == 'PY' ? 'selected':'' }}
                                value="PY"
                                data-nb="true"
                                data-flag="/img/landing/flags/py.svg">Paraguay
                            </option>
                            <option
                                {{ request()['country'] == 'UY' ? 'selected':'' }}
                                value="UY"
                                data-nb="true"
                                data-flag="/img/landing/flags/uy.svg">Uruguay
                            </option>
                        </optgroup>
                        <optgroup label="Centro América">
                            <option
                                {{ request()['country'] == 'PA' ? 'selected':'' }}
                                value="PA"
                                data-flag="/img/landing/flags/pa.svg">Panamá
                            </option>
                            <option
                                {{ request()['country'] == 'GT' ? 'selected':'' }}
                                value="GT"
                                data-nb="true"
                                data-flag="/img/landing/flags/gt.svg">Guatemala
                            </option>
                            <option
                                {{ request()['country'] == 'SV' ? 'selected':'' }}
                                value="SV"
                                data-nb="true"
                                data-flag="/img/landing/flags/sv.svg">El Salvador
                            </option>
                            <option
                                {{ request()['country'] == 'HN' ? 'selected':'' }}
                                value="HN"
                                data-nb="true"
                                data-flag="/img/landing/flags/hn.svg">Honduras
                            </option>
                            <option
                                {{ request()['country'] == 'NI' ? 'selected':'' }}
                                value="NI"
                                data-nb="true"
                                data-flag="/img/landing/flags/ni.svg">Nicaragua
                            </option>
                            <option
                                {{ request()['country'] == 'CR' ? 'selected':'' }}
                                value="CR"
                                data-nb="true"
                                data-flag="/img/landing/flags/cr.svg">Costa Rica
                            </option>
                            <option
                                {{ request()['country'] == 'BZ' ? 'selected':'' }}
                                value="BZ"
                                data-nb="true"
                                data-flag="/img/landing/flags/bz.svg">Belize
                            </option>
                        </optgroup>
                        <optgroup label="Norte América">
                            <option
                                {{ request()['country'] == 'MX' ? 'selected':'' }}
                                value="MX"
                                data-flag="/img/landing/flags/mx.svg">México
                            </option>
                            <option
                                {{ request()['country'] == 'US' ? 'selected':'' }}
                                value="US"
                                data-flag="/img/landing/flags/us.svg">United States
                            </option>
                            <option
                                {{ request()['country'] == 'CA' ? 'selected':'' }}
                                value="CA"
                                data-nb="true"
                                data-flag="/img/landing/flags/ca.svg">Canada
                            </option>
                        </optgroup>
                        <optgroup label="Islas del Caribe">
                            <option
                                {{ request()['country'] == 'DO' ? 'selected':'' }}
                                value="DO"
                                data-nb="true"
                                data-flag="/img/landing/flags/do.svg">República
                                Dominicana
                            </option>
                            <option
                                {{ request()['country'] == 'PR' ? 'selected':'' }}
                                value="PR"
                                data-nb="true"
                                data-flag="/img/landing/flags/pr.svg">Puerto Rico
                            </option>
                            <option
                                {{ request()['country'] == 'AW' ? 'selected':'' }}
                                value="AW"
                                data-nb="true"
                                data-flag="/img/landing/flags/aw.svg">Aruba
                            </option>
                            <option
                                {{ request()['country'] == 'CW' ? 'selected':'' }}
                                value="CW"
                                data-nb="true"
                                data-flag="/img/landing/flags/cw.svg">Curacao
                            </option>
                            <option
                                {{ request()['country'] == 'TT' ? 'selected':'' }}
                                value="TT"
                                data-nb="true"
                                data-flag="/img/landing/flags/tt.svg">Trinidad y
                                Tobago
                            </option>
                            <option
                                {{ request()['country'] == 'BS' ? 'selected':'' }}
                                value="BS"
                                data-nb="true"
                                data-flag="/img/landing/flags/bs.svg">Bahamas
                            </option>
                            <option
                                {{ request()['country'] == 'BB' ? 'selected':'' }}
                                value="BB"
                                data-nb="true"
                                data-flag="/img/landing/flags/bb.svg">Barbados
                            </option>
                        </optgroup>
                        <optgroup label="Europa">
                            <option
                                {{ request()['country'] == 'GB' ? 'selected':'' }}
                                value="GB"
                                data-flag="/img/landing/flags/gb.svg">Reino Unido
                            </option>
                            <option
                                {{ request()['country'] == 'ES' ? 'selected':'' }}
                                value="ES"
                                data-flag="/img/landing/flags/es.svg">España
                            </option>
                            <option
                                {{ request()['country'] == 'PT' ? 'selected':'' }}
                                value="PT"
                                data-flag="/img/landing/flags/pt.svg">Portugal
                            </option>
                            <option
                                {{ request()['country'] == 'IT' ? 'selected':'' }}
                                value="IT"
                                data-flag="/img/landing/flags/it.svg">Italia
                            </option>
                            <option
                                {{ request()['country'] == 'FR' ? 'selected':'' }}
                                value="FR"
                                data-flag="/img/landing/flags/fr.svg">Francia
                            </option>
                            <option
                                {{ request()['country'] == 'DE' ? 'selected':'' }}
                                value="DE"
                                data-flag="/img/landing/flags/de.svg">Alemania
                            </option>
                        </optgroup>
                        <option
                            {{ request()['country'] == 'AU' ? 'selected':'' }}
                            value="AU"
                            data-flag="/img/landing/flags/au.svg">Australia
                        </option>
                    </select>
                </div>
            </div>
           
            <div class="input-group mb-3 mb-md-0">
                <input type="submit" class="ml-3 btn btn-primary" value="Filtrar">
            </div>
        
            @if (request()['user-name'] || request()['user-lastname'] || request()['user-email'] || request()['status'] ||  request()['transactions'] || request()['date-range'] || request()['country'])
            <div class="input-group mb-3 mb-md-0">
                <a href="/admin/user/{{ $user->id }}/{{ $code->id }}" class="ml-3 btn btn-primary">Limpiar</a>
            </div>
            @endif
            @if ( isset($_GET['status']) && $_GET['status'] == 3 )
            <div class="input-group mb-3 mb-md-0">
                <a href="/admin/download-csv/{{$code->id}}" class="ml-3 btn btn-primary">Descargar CSV</a>
            </div>
            @endif
        </form>
    </div>

    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-3">
            <div class="card rounded-lg shadow-none mb-3">
                <div class="card-body border-bottom">
                    <a href="/admin/user/{{ $user->id }}/{{ $code->id }}">
                        <h3 class="text-primary mb-0">{{ $referred_user_count }}</h3>
                        <div class="text-muted text-uppercase font-14 mt-n1">Usuarios referidos</div>
                        <div class="row">
                        </div>
                    </a>
                </div>

                <div class="card-body border-bottom">
                    <div>
                        <a href="?transactions=1">
                            <h3 class="text-primary mb-0">{{ $userCount }}</h3>
                            <div class="text-muted text-uppercase font-14 mt-n1">Usuarios que han realizado transacciones</div>
                            <div class="row">
                            </div>
                        </a>
                    </div>
                    
                </div>

                <div class="card-body border-bottom">

                    <div>
                        <a href="?transactions=2">
                            <h3 class="text-primary mb-0">{{ $userCountWallet }}</h3>
                            <div class="text-muted text-uppercase font-14 mt-n1">Usuarios que han realizado transacciones en Wallet</div>
                            <div class="row">
                            </div>
                        </a>
                    </div>
                    
                </div>

                <div class="card-body d-flex justify-content-between border-bottom">
                    <div>
                        <a href="?status=0">
                            <h5 class="text-primary mb-0"> {{ $whitout_profile }} </h5>
                            <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles Incompletos</div>
                        </a>
                    </div>

                    <div>
                        <a href="?status=1">
                            <h5 class="text-primary mb-0"> {{ $waiting_profile }} </h5>
                            <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles por Verficar</div>
                        </a>
                    </div>

                </div>


                <div class="card-body d-flex justify-content-between border-bottom">
                    <div>
                        <a href="?status=2">
                            <h5 class="text-primary mb-0"> {{ $approve_profile }} </h5>
                            <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles Aprobados</div>
                        </a>
                    </div>
                    <div>
                        <a href="?status=3">
                            <h5 class="text-primary mb-0"> {{ $reject_profile }} </h5>
                            <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles Rechazados</div>
                        </a>
                    </div>
                </div>

                <div class="card-body d-flex justify-content-between border-bottom">
                    <div>
                        <h4 class="text-primary mb-0">
                            {{ money_format("%!.2n", $current_debt) }}
                            <small>USD</small>
                        </h4>
                        <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Deuda Actual</div>
                    </div>
                    <div>
                        <a href="/admin/user-payments/{{ $user->id }}/{{ $code->id }}" class="font-14">
                            Ver pagos <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="text-success mb-0">
                        + {{ money_format("%!.2n", $total_earnings) }} USD
                    </h4>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Ganancias Totales</div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-8 col-xl-9">
            <div class="card rounded-lg shadow-none mb-4">
                <div class="card-header pt-lg-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="card-title font-weight-bold mb-0">Usuarios Referidos</h6>
                            <div class="text-muted font-13">Todos los referidos por el promotor.</div>
                        </div>
                        <div class="col-md-6">
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

                        @foreach($list_data as $d)
                        <li class="transactionItem card rounded-lg mb-2">
                            <div class="transactionItem__body py-2">
                                <div class="form-row">
                                    <div class="col-4 col-sm-4 col-md-5 col-lg-5 my-auto">
                                        <h6 class="mb-0 text-truncate">
                                            <strong>
                                                <a href="/admin/referral/{{ $d['user']->id }}" target="_blank" class="text-primary mb-0">
                                                    {{ $d['user']->name }}
                                                    @if($d['profile_user']->approval_status == 0)
                                                    <i class="fa fa-frown-o" data-toggle="tooltip" title="" data-original-title="Perfil de Usuario sin completar" class="btn-transparent text-primary font-weight-bold" aria-hidden="true"></i>
                                                    @elseif($d['profile_user']->approval_status == 1)
                                                    <i class="fa fa-clock-o" data-toggle="tooltip" title="" data-original-title="Perfil de Usuario en revision" class="btn-transparent text-primary font-weight-bold" aria-hidden="true"></i>
                                                    @elseif($d['profile_user']->approval_status == 2)
                                                    <i class="fa fa-check" data-toggle="tooltip" title="" data-original-title="Perfil de Usuario Verificado" class="btn-transparent text-primary font-weight-bold" aria-hidden="true"></i>
                                                    @elseif($d['profile_user']->approval_status == 3)
                                                    <i class="fa fa-times" data-toggle="tooltip" title="" data-original-title="Perfil de Usuario Rechazado" class="btn-transparent text-primary font-weight-bold" aria-hidden="true"></i>
                                                    @endif
                                                </a>
                                            </strong>
                                        </h6>
                                        <div class="small text-muted lh-125 text-truncate">
                                            {{ $d['user']->email }}
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-2 col-lg-2 my-auto d-none d-sm-block text-sm-left">
                                        <div class="lh-125 font-weight-bold">
                                            {{ $d['total_transactions'] }}
                                        </div>
                                        <div class="small text-muted lh-125 text-truncate">transacciones</div>
                                    </div>
                                    <div class="col-4 col-sm-2 col-md-2 col-lg-2 my-auto text-left">
                                        <div class="text-success lh-125">
                                            {{ money_format('%i',$d['total_earnings']) }}
                                        </div>
                                        <div class="small text-muted lh-125 text-truncate">Ganancias Totales <i class="fa fa-question-circle" data-toggle="tooltip" title="Ganancias totales con este usuario."></i></div>
                                    </div>
                                    <div class="col-4 col-sm-3 col-md-2 col-lg-2 my-auto text-right">
                                        <div class="lh-125 font-14">
                                            {{ Carbon::parse($d['user']->created_at)->format('d/m/Y g:i A') }}
                                        </div>
                                        <div class="small text-muted lh-125 text-truncate">Registro</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    {{ $paginator->appends(request()->all())->links('profile.referrals-paginator') }}

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editPublicist" tabindex="-1" role="dialog" aria-labelledby="editPublicistModal" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white border-bottom">
                <h6 class="modal-title" id="addPromoCodeModal">Código Promocional</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/update-code/{{ $code->id }}" id="saveCodeForm" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="text-primary" for="promo-code">Código</label>
                        <input id="promo-code" type="text" name="promo_code" class="form-control form-control-lg" value="{{ $code->code }}" placeholder="ABCD1234" required>
                    </div>
                    <div class="form-group">
                        <label class="text-primary" for="promo-user">Usuario promotor</label>
                        <input id="promo-user" type="text" class="form-control" readonly value="{{ $user->email }}" placeholder="user@example.com" required>
                    </div>
                    <div class="form-group">
                        <label class="text-primary" for="promo-percentage">Porcentaje de Ganancia</label>
                        <div class="input-group mb-3">
                            <input id="profit-percent" type="number" step="0.1" readonly value="{{ $code->profit_percent }}" class="form-control" placeholder="0.5" required>
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
                            <input id="referal-bonus" type="number" step="0.1" readonly value="{{ $code->referral_bonus }}" class="form-control" placeholder="1" required>
                        </div>
                        <small>* Bono en dólares por usuario registrado</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="submit" form="saveCodeForm" class="btn btn-secondary btn-pill btn-block" value="Guardar Cambios">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="disableCode" tabindex="-1" role="dialog" aria-labelledby="disableCodeModal" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white border-bottom">
                <h6 class="modal-title" id="addPromoCodeModal">Deshabilitar Código</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/disable-code/{{ $code->id }}" id="disableCodeForm" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="text-primary" for="disable-select-code">¿Desea deshabilitar el código?</label>
                        <select id="disable-select-code" name="disable-select-code" class="form-control form-control-lg" required>
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                        <p class="text-danger mt-3">* Ésta acción es irreversible.</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="submit" form="disableCodeForm" class="btn btn-danger btn-pill btn-block" value="Deshabilitar código">
            </div>
        </div>
    </div>
</div>

<form action="/admin/referral/{{$user->id}}/get" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="1">
    <input type="hidden" name="">
</form>
@endsection

@section('scripts')
<script src="https://raw.githubusercontent.com/nnnick/Chart.js/master/dist/Chart.bundle.js"></script>

<script type="application/javascript">
        var date_array = new Array();
        var users_array = new Array();
        var myBar;
        var info;
        var month_list = '',
            date = new Date(),
            month = date.getMonth(),
            year = date.getFullYear(),
            select = document.getElementById('date-select');
            
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", 
                            "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        for (var i = 0; i<=month; i++){
            var opt = document.createElement('option');
            opt.value = (i + 1) + "/" + "01" + "/" + year + " - " + (i + 2) + "/" + "01" + "/" + year;
            opt.innerHTML = monthNames[i];
            select.appendChild(opt);
        }

        $(document).ready(function () {
            
            $("#searchBy").change(function (e) { 
                e.preventDefault();
                var value = $("#searchBy").val();
                if (value == 1) {
                    document.getElementById("searchByRange").hidden = true;
                    document.getElementById("searchByMonth").hidden = false;
                    
                }
                if(value == 2){
                    document.getElementById("searchByMonth").hidden = true;
                    document.getElementById("searchByRange").hidden = false;
                    
                } 
            });
            
            $("#getChart").click(function (e) { 
                e.preventDefault();
                info = document.getElementById("searchBy").value == 1 ? document.getElementById("date-select").value :
                       document.getElementById("searchBy").value == 2 ? document.getElementById("creation-date-filter-chart").value : null;
                console.log(info);
                
                $.ajax({
                    url: "/admin/referral/{{$code->id}}/get",
                    method: "POST",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        chart: info
                    },
                    success: function (res) {
                        var arreglo = JSON.parse(res);
                        date_array = [];
                        users_array = [];
                        arreglo.forEach(function(data){
                            date_array.push(data.created_date);
                            users_array.push(data.total);
                        });
                        generarGrafica(arreglo);
                    }
                });
            });
        });

        function generarGrafica(arreglo){
            if (arreglo != null) {
                console.log("Not null");
                console.log(arreglo);
                $("#usersCharts").show("slow");
            }
            
            var barChartData = null;

            barChartData = {
                labels: date_array,
                datasets: [{
                    label: 'Usuarios Registrados',
                    backgroundColor: "rgba(244,101,50,0.1)",
                    data: users_array
                }]
            };

            if (myBar) {
                myBar.destroy();
            }

            var ctx = document.getElementById("canvas").getContext("2d");
            myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 1.5,
                            borderColor: 'rgb(244, 101, 30)',
                            borderSkipped: 'bottom'
                        }
                    },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Registro de usuarios'
                    }
                }
            });
        }
</script>

@stop