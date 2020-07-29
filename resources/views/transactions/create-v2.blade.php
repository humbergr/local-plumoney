@extends('layouts.main-transactions-form')

@section('content')

    <section class="section-head-1">
        <div class="grid-container">
            <div class="grid-x">
                <div class="cell text-center">
                    <div class="content">
                        <img src="{{ asset('img/icons/transactions-icon.png') }}" alt="Transactions">
                        <h3>Create transaction</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(Session::has('error'))
        <section class="messages" style="margin-top:30px;">
            <div class="grid-container error-alert">
                <p><strong>Error!</strong> {{ Session::get('error') }}</p>
            </div>
        </section>
    @elseif(Session::has('success'))
        <section class="messages" style="margin-top:30px;">
            <div class="grid-container success-alert">
                <p style="color:green"><strong>Success!</strong> {{ Session::get('success') }}</p>
            </div>
        </section>
    @endif

    <section class="main-form">
        <form action="{{ URL('/create-incoming-transaction') }}" method="post">

            {{ csrf_field() }}

            <div class="grid-container">
                <div class="grid-x">
                    <div class="large-offset-4 large-4 cell radio-buttons">
                        <label class="t-radio">
                            <img src="{{ asset('img/icons/incoming-icon.png') }}"
                                 alt="Incoming transactions">
                            <input type="radio" name="type" value="Incoming" required>
                            Incoming <br>transaction
                        </label>
                        <label class="t-radio">
                            <img src="{{ asset('img/icons/outgoing-icon.png') }}"
                                 alt="Outgoing transactions">
                            <input type="radio" name="type" value="Outgoing" required>
                            Outgoing <br>transaction
                        </label>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-offset-3 large-6 cell form-body">
                        <label>
                            Bank Name or Origin
                            <input type="text" value="" name="origin_name" required="required" class="form-control">
                            <span class="help-text">
                                Name of the bank or the name of the provider (coinbase, localbicoins, user123, etc).
                            </span>
                        </label>
                        <label>
                            ID code
                            <input type="text" value="" name="new_transaction_id" required="required"
                                   class="form-control">
                            <span class="help-text">
                                Required. Identification code of the transaction.
                                <br>
                                Can be the Coinbase transaction ID, LocalBitcoins Contact ID or
                                some private (from <strong>CoinBank</strong>) ID.
                            </span>
                        </label>
                        <label>
                            Amount BTC
                            <input type="number" step="0.00000001" class="form-control" name="amount_btc"
                                   value="">
                            <span class="help-text">This field must contain the amount of BTC to declare</span>
                        </label>
                        <label>
                            Amount
                            <input type="number" step="0.01" name="amount" value="" class="form-control">
                            <span class="help-text">
                                Required. The amount in FIAT of the transaction.
                            </span>
                        </label>
                        <label>
                            Currency
                            <select name="currency" required="required" class="form-control">
                                <option value="USD">USD</option>
                                <option value="VES">VES</option>
                            </select>
                        </label>
                        <label for="visible">
                            Date
                            <input type="datetime-local" class="form-control" name="transaction_date"
                                   value="">
                            <span class="help-text">
                                Transaction Date
                            </span>
                        </label>
                        <label>
                            Additional information
                            <textarea name="msg" rows="6" cols="80" value="" class="form-control"></textarea>
                        </label>
                    </div>
                    <div class="large-offset-3 large-6 cell text-right form-buttons">
                        <button type="submit" name="button" class="button">Create transaction</button>
                        <a href="https://plumoney.com/transactions" class="button alert">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection
