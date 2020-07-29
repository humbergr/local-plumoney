@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Solve errors</div>
                    <div class="card-body">
                        @if ($error->not_usd_price)
                            <div class="row">
                                <p>There are some transactions without USD price. For solve this issue update them with
                                    the correct USD price. Solve it <a href="{{URL::to('/transactions')}}">here</a>
                                </p>
                            </div>
                        @else
                            <form class="" action="{{URL::to('/solve-error').'/'.$error->id}}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="error_amount" value="{{$error->amount_btc}}">

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="visible">Bank Name or Origin Name</label>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="" name="origin_name"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <p>
                                                Name of the bank or the name of the provider (coinbase, localbicoins,
                                                user123, etc).
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="visible">ID code</label>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="" name="new_transaction_id"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <p>
                                                Required. Identification code of the transaction.
                                                <br>
                                                Can be the Coinbase transaction ID, LocalBitcoins Contact ID or
                                                some private (from <strong>CoinBank</strong>) ID.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="visible">Transaction ID to Solve</label>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{{$error->transaction_id}}"
                                                   name="transaction_id" required readonly>
                                        </div>
                                        <br>
                                        <span class="help-block">Account:
                                                {{$error->json_data['data']['seller']['username']}}</span>
                                        <br>
                                        <span class="help-block">Date:
                                                {{$error->json_data['data']['released_at']}}</span>
                                        <br>
                                        <span class="help-block">Contact link:
                                            <a href="{{$error->json_data['actions']['release_url']}}">
                                                {{$error->json_data['actions']['release_url']}}
                                            </a>
                                        </span>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <p>Required. The identification of the transaction.</p>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="visible">Amount BTC</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" step="0.00000001" class="form-control" name="amount_btc"
                                               value="" required>
                                    </div>
                                    <div class="col-md-5">
                                        <p>This field must be equal or higher
                                            than {{$error->json_data['data']['amount_btc']}} BTC</p>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="visible">Date</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="datetime-local" class="form-control" name="transaction_date"
                                               value="" required>
                                    </div>
                                    <div class="col-md-5">
                                        <p>Transaction Date</p>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="visible">Amount USD</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" step="0.01" class="form-control" name="amount" value=""
                                               required>
                                    </div>
                                    <div class="col-md-5">
                                        <p></p>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="visible">Additional information</label>
                                    </div>
                                    <div class="col-md-5">
                                        <textarea name="msg" class="form-control" rows="8" cols="80"
                                                  value=""></textarea>
                                    </div>
                                </div>
                                <br>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-default" name="button">Create transaction
                                    </button>
                                    <a href="{{ URL::to('/transactions') }}" class="btn" style="color:black">Cancel</a>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>

@endsection
