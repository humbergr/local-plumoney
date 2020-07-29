@extends('layouts.coinbank-layout')

@section('content')

<div class="container">
    <section id="wallets-numbers" class="py-section-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="sections-titles">Balance de las Billeteras</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div>
                            <table class="table table-striped table-borderless mb-0">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Total disponible
                                        </h5>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        {{$data['totals']['available']}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div>
                            <table class="table table-striped table-borderless mb-0">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Total en espera por entrar
                                        </h5>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        {{$data['totals']['holdsUp']}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div>
                            <table class="table table-striped table-borderless mb-0">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Total en espera por salir
                                        </h5>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        {{$data['totals']['holdsDown']}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <h4 class="sections-titles">Balance de las billeteras por día.</h4>
                </div>
            </div>
            @foreach ($data['movements'] as $keyY => $year)
            <div class="row">
                <div class="col-md-12">
                    <h5>{{$keyY}}</h5>
                    @foreach ($year as $keyM => $month)
                    <h6>{{$keyM}}</h6>
                        @foreach ($month as $keyD => $day)
                            <div class="card mb-2">
                                <table class="table table-striped table-borderless mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-center" colspan="5">
                                            <h5 class="text-muted font-weight-bold mb-0">
                                                {{$keyM}} - {{$keyD}}
                                            </h5>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <h6 class="text-muted font-weight-bold mb-0">
                                                Recargas en Espera
                                            </h6>
                                        </th>
                                        <th class="text-center">
                                            <h6 class="text-muted font-weight-bold mb-0">
                                                Total Recargas en Espera
                                            </h6>
                                        </th>
                                        <th class="text-center">
                                            <h6 class="text-muted font-weight-bold mb-0">
                                                Retiros en Espera
                                            </h6>
                                        </th>
                                        <th class="text-center">
                                            <h6 class="text-muted font-weight-bold mb-0">
                                                Total Recargas Completadas
                                            </h6>
                                        </th>
                                        <th class="text-center">
                                            <h6 class="text-muted font-weight-bold mb-0">
                                                Total Retiros Completados
                                            </h6>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-left">
                                            @if (!empty($day['numbers']['holdsUp']))
                                                <ul>
                                                @foreach ($day['numbers']['holdsUp'] as $method => $amount)
                                                    <li>{{$method}}: {{money_format('%.2n', $amount)}}</li>
                                                @endforeach
                                                </ul>
                                            @else
                                                Sin recargas en Espera
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{$day['numbers']['holdsUpTotal']}}
                                        </td>
                                        <td class="text-center">
                                            {{$day['numbers']['holdsDown']}}
                                        </td>
                                        <td class="text-center">
                                            {{$day['numbers']['reloads']}}
                                        </td>
                                        <td class="text-center">
                                            {{$day['numbers']['withdrawals']}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>

@endsection
