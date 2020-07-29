@extends('layouts.app')

@section('content')

<div class="container">

    <form class="form-cotrol" action="{{ URL('/user-settings') }}" method="post">

        {{ csrf_field() }}

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Settings</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-2">
                                <label for="visible">User email</label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="email" class="form-control" value="{{Auth::user()->email}}"
                                           name="email" required></label>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-2">
                                <label for="visible">User full name</label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{Auth::user()->name}}" name="name"
                                           required></label>
                                </div>
                            </div>
                        </div>
                        <br>

                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">LocalBitcoins account settings</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-2">
                                <label for="visible">Account to work</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="local_env_number" required>
                                    @foreach($credentials as $credential)
                                    <option value="{{$credential->env_number}}" @if($credential->is_active == 1)
                                        selected @endif>{{$credential->username}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>

                    </div>
                </div>
            </div>
        </div>
        <br>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Exchange rates Fee</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">Buying USD in Venezuela (USD > VEN)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['to_ven']}}"
                                           id="ex_fee_to_ven" name="to_ven" type="number" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                VES
                                <span class="number_to_ven">
                                    {{number_format($exchangeRatesPrices['to_ven'],2,".",",")}}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">Selling USD in Venezuela (VEN > USD)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['from_ven']}}"
                                           id="ex_fee_from_ven" name="from_ven" type="number" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                VES
                                <span class="number_from_ven">
                                    {{number_format($exchangeRatesPrices['from_ven'],2,".",",")}}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">General (Non Venezuela included)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['fee_general']}}"
                                           id="ex_fee_general" name="fee_general" type="number" step="0.1">
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Wallets rates Fee</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">Charge USD with VES (VES > USD)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['wallets_from_ven']}}"
                                           id="wallets_fee_from_ven" name="wallets_from_ven" type="number" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                1 USD => VES
                                <span class="wallets_number_from_ven">
                                    {{number_format($exchangeRatesPrices['wallets_from_ven'],2,".",",")}}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">Withdraw USD to VES (USD > VES)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['wallets_to_ven']}}"
                                           id="wallets_fee_to_ven" name="wallets_to_ven" type="number" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                1 USD => VES
                                <span class="wallets_number_to_ven">
                                    {{number_format($exchangeRatesPrices['wallets_to_ven'],2,".",",")}}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">Charge USD with USD (+USD > USD)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['wallets_usd_usd_charge']}}"
                                           id="wallets_usd_usd_charge" name="wallets_usd_usd_charge" type="number" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                1 USD => USD
                                <span class="wallets_number_usd_usd_charge">
                                    {{number_format($exchangeRatesPrices['wallets_usd_usd_charge'],2,".",",")}}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">Withdraw USD to USD (-USD > USD)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['wallets_usd_usd_withdraw']}}"
                                           id="wallets_usd_usd_withdraw" name="wallets_usd_usd_withdraw" type="number" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                1 USD => USD
                                <span class="wallets_number_usd_usd_withdraw">
                                    {{number_format($exchangeRatesPrices['wallets_usd_usd_withdraw'],2,".",",")}}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">Charge USD with other currencies (X > USD)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['wallets_fee_general_charge']}}"
                                           id="ex_fee_general_charge" name="wallets_fee_general_charge" type="number" step="0.1">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="is_active">Withdraw USD to other currencies (USD > X)</label>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">%</div>
                                    </div>
                                    <input class="form-control" value="{{$exchangeRatesFees['wallets_fee_general_withdraw']}}"
                                           id="ex_fee_general_withdraw" name="wallets_fee_general_withdraw" type="number" step="0.1">
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Website settings</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-2">
                                <label for="is_active">Is open?</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="is_active" id="is_active" required>
                                    <option value="1" @if($settings[
                                    'settings']['is_active'] === 1) selected @endif>Yes</option>
                                    <option value="0" @if($settings[
                                    'settings']['is_active'] === 0) selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                        <br>

                    </div>
                </div>
            </div>
        </div>
        <br>


        <div class="text-center">
            <button type="submit" class="btn btn-default" name="button">Save settings</button>
            <a href="{{ URL::to('/app') }}" class="btn" style="color:black">Cancel</a>
        </div>

    </form>

</div>

@endsection

@section('page-script')
<script type="text/javascript" src="/js/just-settings.js" defer></script>
@stop
