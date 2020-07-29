@extends('layouts.mvp-layout-internal')

@php
    setlocale(LC_MONETARY, 'en_US.UTF-8');
@endphp

@section('content')

<main>
    <div class="container mt-md-n5">
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-9" style="background: #fff;">
                <!----
                *
                *
                *   TRANSACTION LIST
                *
                * <h5 class="d-inline-block text-danger text-strike mb-0" title="Ganancia">+ 10.00 USD <small>(5%)</small></h5>
                ------>

                <div class="card shadow-none mb-4">
                    <div class="card-header pt-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
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
                        </div>
                    </div>
                </div>

                <div class="container" id="usersCharts" style="display: none;">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <canvas id="canvas" height="280" width="600"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card shadow-none mb-4">
                    <div class="card-header pt-lg-4">
                        <div class="row">
                            <div class="col-8">
                                <h6 class="card-title font-weight-bold mb-0">Últimas Transacciones</h6>
                                <div class="text-muted font-13">Últimas operaciones realizadas por tus referidos.</div>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ URL::to('/referrals/transactions') }}"
                                    class="font-14 ws-nowrap">
                                    Ver todas <i class="fa fa-angle-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="transactions__list list-unstyled">

                            @foreach($transactions_data as $d)
                                <li class="transactionItem card rounded-lg mb-2">
                                    <div class="transactionItem__body py-2">
                                        <div class="form-row">
                                            <div class="col-6 col-sm-5 col-md-4 col-lg-6 my-auto">
                                                <h4 class="text-primary mb-0">
                                                    {{ money_format("%!.2n", $d['transaction']->sender_fiat_amount) }}
                                                    {{ $d['currency'] }}
                                                </h4>
                                                <div class="font-13 ws-nowrap text-truncate">
                                                    <span class="d-none d-sm-inline">Usuario:</span>
                                                    <strong>
                                                        <a href="{{ URL::to('/referrals/user/' . $d['user']['id']) }}"
                                                            class="text-primary mb-0">
                                                            {{ $d['truncated_email'] }}
                                                        </a>
                                                    </strong>
                                                </div>
                                            </div>
                                            <div
                                                class="col-6 col-sm-3 col-md-4 col-lg-3 my-auto d-none d-sm-block text-sm-center">
                                                <span
                                                    class="badge {{ $d['badge'] }} badge-pill my-1 my-md-0">
                                                    {{ $d['badge_text'] }}
                                                </span>
                                            </div>
                                            <div class="col-6 col-sm-4 col-md-4 col-lg-3 my-auto">
                                                <div class="ws-nowrap text-right">
                                                    <h5 class="d-inline-block text-success {{ $d['strike'] }} mb-0"
                                                        title="Ganancia">
                                                        +
                                                        {{ money_format("%!.2n", $d['commission_total']) }}
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
                    </div>
                </div>

                <!----
                *
                *
                * REFERRALS LIST
                *
                *
                ------>

                <div class="card shadow-none mb-4">
                    <div class="card-header pt-lg-4">
                        <div class="row">
                            <div class="col-8">
                                <h6 class="card-title font-weight-bold mb-0">Últimos Referidos</h6>
                                <div class="text-muted font-13">Ultimos referidos registrados.</div>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ URL::to('/referrals/list') }}"
                                    class="font-14 ws-nowrap">
                                    Ver todos<i class="fa fa-angle-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="transactions__list list-unstyled">

                            @foreach($referrals_data['referrals'] as $r)
                                <li class="transactionItem card rounded-lg mb-2">
                                    <div class="transactionItem__body py-2">
                                        <div class="form-row">
                                            <div class="col-8 col-sm-4 col-md-3 col-lg-3 my-auto">
                                                <h6 class="mb-0 text-truncate">
                                                    <strong>
                                                        <a href="{{ URL::to('/referrals/user/' . $r['user']['id']) }}"
                                                            class="text-primary mb-0">
                                                            {{ $r['truncated_email'] }}
                                                        </a>
                                                    </strong>
                                                </h6>
                                            </div>
                                            <div
                                                class="col-6 col-sm-3 col-md-3 col-lg-3 my-auto d-none d-sm-block text-sm-center font-14">
                                                <strong>{{ $r['transaction_count'] }}</strong>
                                                transacciones
                                            </div>
                                            <div
                                                class="col-4 col-sm-2 col-md-3 col-lg-3 my-auto text-right text-sm-center">
                                                <span
                                                    class="text-success">+{{ money_format("%!.2n", $r['fiat_percentage']) }}
                                                    USD</span>
                                            </div>
                                            <div class="col-12 col-sm-3 col-md-3 col-lg-3 my-auto">
                                                <div class="font-14 text-right text-muted">
                                                    {{ $r['user']->created_at }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 col-xl-3">
                <div class="card shadow-none mb-3">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between border-bottom">
                        <div>
                            <h3 class="text-primary mb-0">
                                {{ money_format("%!.2n", $totalDebt) }} USD</h3>
                            <div class="text-muted text-uppercase font-14 mt-n1">Pago Acumulado</div>
                        </div>
                        <div>
                            <a href="/referrals/payments" class="font-13 lh-125 ws-nowrap">Ver pagos<i
                                    class="fa fa-angle-right ml-1"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-0">{{ money_format("%!.2n", $totalPayed) }} USD</h4>
                        <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Total Pagado</div>
                    </div>
                </div>
                <div class="card shadow-none mb-3">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between border-bottom">
                        <div>
                            <h2 class="text-primary mb-0">
                                {{ $referrals_count }}
                                <span
                                    class="font-14 text-muted">({{ money_format("%!.2n", $totalEarned) }}
                                    USD)
                                    <i class="fa fa-question-circle" data-toggle="tooltip"
                                        title="Total ganancia por referidos."></i>
                                </span>
                            </h2>
                            <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Personas Referidas</div>
                        </div>
                        <div>
                            <a href="{{ URL::to('/referrals/list') }}"
                                class="font-13 ws-nowrap">
                                Ver usuarios<i class="fa fa-angle-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <h2 class="text-primary mb-0">
                            {{ $referrals_transaction_count }}
                        </h2>
                        <div class="text-muted text-uppercase lh-125 font-14 mt-n1">
                            Transacciones realizadas por tus referidos.
                        </div>
                    </div>

                    <div class="card-body">
                        <p class="text-primary mb-0">Graficos de Usuarios</p>
                        <form action="" method="get" class="flex-md-nowrap">

                            {{ csrf_field() }}

                            <div class="input-group mb-3 mb-md-0 mr-3 pb-3" style="width: 250px">

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

                            <div class="input-group mt-3 mb-md-0">
                                <input type='submit' id="getChart" class="ml-3 btn btn-primary" value="Filtrar">
                                @if(request()['chart-date'])
                                    <a href="/referrals" class="ml-3 btn btn-primary">Limpiar</a>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

@endsection

@section('js')
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
                        id: 1,
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