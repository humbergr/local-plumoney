@extends('layouts.app')

@section('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css"
    integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous" />

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
    integrity="sha256-nbyata2PJRjImhByQzik2ot6gSHSU4Cqdz5bNYL2zcU=" crossorigin="anonymous" />

<style>
    .select2-container--bootstrap .select2-selection--single {
        height: 38px !important;
    }
</style>


@endsection

@section('content')
<div class="container">



    <form class="form-cotrol" action="{{ URL('/transactions/edit/' . $transaction->id) }}" method="post">






        <div class="row justify-content-center">
            <div class="{{Auth::user()->id === 31 || Auth::user()->id === 2 ? 'col-md-6' : 'col-md-12'}}">
                <div class="card card-default">
                    <div class="card-header">Update transaction</div>
                    <div class="card-body">
                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col">
                                <label for="visible">Bank Name or ID code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{$transaction->bank_name}}"
                                        name="bank_name" required></label>
                                </div>
                            </div>


                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">Transaction ID code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{$transaction->transaction_id}}"
                                        name="transaction_id" required></label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">Amount BTC</label>
                                <input type="number" readonly class="form-control" value="{{$transaction->amount_btc}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">Amount</label>
                                <input type="text" id="amount" class="form-control" name="amount"
                                    value="{{$transaction->amount}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">Currency to work</label>
                                <input type="text" class="form-control" name="currency"
                                    value="{{$transaction->currency}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">USD Price</label>
                                <input type="text" id="precio" class="form-control" name="usd_price"
                                    value="{{$transaction->usd_price}}" required>
                            </div>
                        </div>
                        <br>


                        <div class="container">

                            @if ($transaction->salida)
                            <div class="alert alert-info" role="alert">


                                @if ($transaction->salida === 'Transaction')
                                Transaction Customer Tracking Id: <b>

                                    @if ($transaction->user_exchange_transaction_id != '')
                                    {{ customerTransaction_id( $transaction->user_exchange_transaction_id)->tracking_id . ' Email:'. customerTransaction_id( $transaction->user_exchange_transaction_id)->userAccount->email       }}</b>
                                @endif

                                @elseif($transaction->salida === 'Incoming wallet')
                                Incoming wallet Customer Tracking Id:
                                {{ UserWalletsTransactions( $transaction->user_exchange_transaction_id)->tracking_id . ' Email:'. UserWalletsTransactions( $transaction->user_exchange_transaction_id)->userAccount->email       }}</b>



                                @else

                                Salida a un Banco: <b> {{   $transaction->banco->name }}
                                    {{   $transaction->banco->numero }}</b>

                                @endif
                            </div>

                            @endif
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <label for='r11' style='width: 350px;'>
                                                <input type='radio' id='r11' name='salida' value='Transaction'
                                                    {{ ($transaction->salida === 'Transaction') ? 'checked'  : ''}}
                                                    required />
                                                Pagar un cliente
                                                <a data-toggle="collapse" data-parent="#accordion"
                                                    href="#collapseOne"></a>
                                            </label>
                                        </h4>
                                    </div>
                                    <div id="collapseOne"
                                        class="panel-collapse collapse in  {{ ($transaction->salida === 'Transaction') ? 'show'  : ''}} ">
                                        <div class="panel-body">
                                            <label for="visible">Customer transaction</label>
                                            <select id="user_exchange_transaction_id"
                                                name="user_exchange_transaction_id"
                                                class="select2 form-control form-control-alternative{{ $errors->has('user_exchange_transaction_id') ? ' is-invalid' : '' }}">
                                                <option value="">Selecciona:</option>

                                                @foreach ( customerTransaction($transaction->currency) as $element)

                                                @php
                                                $accountType = 'Ahorro';
                                                if ($element->destinationAccount->account_type === 1) {
                                                $accountType = 'Corriente';
                                                }

                                                $envio = 'Envío: '.number_format($element->sender_fiat_amount, 2).'
                                                '.$element->sender_fiat.' =>
                                                '.number_format($element->receiver_fiat_amount, 2).'
                                                '.$element->receiver_fiat.' Email:'.
                                                $element->destinationAccount->email.'
                                                Cel:'.$element->destinationAccount->phone_number. ' Nombre: '.
                                                $element->destinationAccount->name.'
                                                '.$element->destinationAccount->lastname. ' CI:'.
                                                $element->destinationAccount->id_number. ' Banco:'
                                                .$element->destinationAccount->bank_name. ' '.$accountType . ' ' .
                                                $element->destinationAccount->account_number;
                                                @endphp

                                                @if ($element->sender_fiat !== 'USD')

                                                @if (isset($element->walletTransaction->exchange_rate))
                                                <option value="{{$element->id}}"
                                                    data-user="{{$element->userAccount->name}}"
                                                    data-precio="{{  number_format($element->receiver_fiat_amount /  $element->walletTransaction->exchange_rate)}}"
                                                    data-envio="{{$envio}}"
                                                    {{ old('user_exchange_transaction_id') == $element->id ? 'selected' : '' }}>
                                                    {{$element->tracking_id}} - {{$element->userAccount->name}} -
                                                    {{$element->userAccount->email}} -
                                                    {{   number_format($element->receiver_fiat_amount /  $element->walletTransaction->exchange_rate)  }}USD

                                                </option>
                                                @endif
                                                @else
                                                <option value="{{$element->id}}"
                                                    data-user="{{$element->userAccount->name}}"
                                                    data-precio="{{ $element->sender_fiat_amount}}"
                                                    data-envio="{{$envio}}"
                                                    {{ old('user_exchange_transaction_id') == $element->id ? 'selected' : '' }}>
                                                    {{$element->tracking_id}} - {{$element->userAccount->name}} -
                                                    {{$element->userAccount->email}} -
                                                    {{ $element->sender_fiat_amount}}USD

                                                </option>

                                                @endif


                                                @endforeach

                                            </select>

                                            <br>
                                            @if ($transaction->salida)
                                            @if ($transaction->user_id != '')

                                            <div class="alert alert-info" role="alert">

                                                Sele pago a: {{   $transaction->user->name     }}</b>
                                            </div>
                                            @endif
                                            @endif
                                            <label for="visible">O a un usuario en espesifico</label>

                                            <select id="usuario" name="user_id"
                                                class=" form-control form-control-alternative{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                                                 >
                                                <option value="">Selecciona:</option>



                                            </select>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class=panel-title>
                                            <label for='r12' style='width: 350px;'>
                                                <input type='radio' id='r12' name='salida' value='Bank'
                                                    {{ ($transaction->salida === 'Bank') ? 'checked'  : ''}} required />
                                                Cargar un banco
                                                <a data-toggle="collapse" data-parent="#accordion"
                                                    href="#collapseTwo"></a>
                                            </label>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo"
                                        class="panel-collapse collapse {{ ($transaction->salida === 'Bank') ? 'show'  : ''}}">
                                        <div class="panel-body">
                                            <div class="col">

                                                <label for="feInputState" class="text-primary">Caja u/o Banco:</label>
                                                <select id="banco" name="banco_id"
                                                    class="select2 form-control form-control-alternative{{ $errors->has('banco_id') ? ' is-invalid' : '' }}">
                                                    <option value="">Selecciona:</option>

                                                    @foreach ( bancosMoney($transaction->currency) as $element)
                                                    {{-- expr --}}

                                                    <option data-moneda="{{$element->moneda}}"
                                                        data-saldo2={{currency($element->saldo,'')}}
                                                        data-saldo={{$element->saldo}} value="{{$element->id}}"
                                                        {{@$transaction->banco_id == $element->id  ? 'selected' : '' }}>
                                                        {{$element->name}}
                                                        {{$element->numero}}
                                                        {{currency($element->saldo,$element->moneda)}}
                                                    </option>
                                                    @endforeach

                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">Customer name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name"
                                    value="{{$transaction->customer_name}}">
                            </div>
                        </div>



                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">Customer rate</label>
                                <input type="number" step="0.01" class="form-control" name="customer_rate"
                                    value="{{$transaction->customer_rate}}">
                            </div>
                        </div>
                        <br>
                        @if($transaction->type === 'Incoming')
                        <div class="row">
                            <div class="col">
                                <label for="visible">Category</label>
                                <select class="form-control" name="category" required>
                                    <option>-- Select One --</option>
                                    @foreach($btc_trans->categories as $key => $category)
                                    <option value="{{$key}}" {{($btc_trans->category === $key) ? 'selected' : '' }}>
                                        {{$category}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        @endif
                        <input type="hidden" name="error" value="0">
                        <div class="row">


                            <div id="envio">

                            </div>
                            <div class="col">
                                <label for="visible">Additional information</label>
                                <textarea name="msg" id="texta" class="form-control" rows="8" cols="80"
                                    value="">{{$transaction->msg}}</textarea>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
                <br>
            </div>
            @if (Auth::user()->id === 31 || Auth::user()->id === 2)
            <div class="col-md-6">
                <div class="card card-default sticky-top">
                    <div class="card-header">Information</div>
                    <div class="card-body">
                        <h5 class="card-title">Localbitcois contact information</h5>
                        @if (is_numeric($transaction->transaction_id))
                        <p class="card-text">
                            Seller: {{$transaction->json_data['seller']['name']}} <br><br>
                            @php
                            $btcRate = round(((float)$transaction->json_data['amount'] /
                            (float)$transaction->json_data['amount_btc']), 2);
                            $humanBtcRate = number_format($btcRate, 2, ',', '.');
                            @endphp
                            BTC Rate: {{$transaction->currency}} {{$btcRate}}
                            ({{$transaction->currency}} {{$humanBtcRate}}) <br><br>
                            Related Bitstamp Price: USD {{$bitstamp['price']}} <br><br>
                            @php
                            $usdPrice = round($btcRate / $bitstamp['price'], 2);
                            $humanUsdPrice = number_format($usdPrice, 2, ',', '.');
                            @endphp
                            @if ($transaction->currency !== 'USD')
                            Estimated USD exchange rate:
                            {{$transaction->currency}} {{$usdPrice}}
                            ({{$transaction->currency}} {{$humanUsdPrice}}) <br><br>
                            @endif
                            @if ($transaction->currency !== 'USD')
                            BTC rate cost:
                            USD {{number_format($btcRate / $usdPrice , 2)}} <br><br>
                            @endif
                            @php
                            $sUsdPrice = round((float)$transaction->json_data['amount'] / $usdPrice, 2);
                            $humanSUsdPrice = number_format($sUsdPrice, 2);
                            @endphp
                            @if ($transaction->currency !== 'USD')
                            Suggested USD price:
                            USD {{$sUsdPrice}}
                            (USD {{$humanSUsdPrice}}) <br><br>
                            @endif
                        </p>
                        @else
                        <p class="card-text">
                            Related Bitstamp Price: USD {{$bitstamp['price']}} <br><br>
                            @php
                            $sUsdPrice = round($transaction->amount_btc * $bitstamp['price'], 2);
                            $humanSUsdPrice = number_format($sUsdPrice, 2);
                            @endphp
                            Suggested USD price:
                            USD {{$sUsdPrice}}
                            (USD {{$humanSUsdPrice}}) <br><br>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>



        <div class="text-center">

            @if ($transaction->salida)
            <div class="alert alert-info" role="alert">


                @if ($transaction->salida === 'Transaction')
                Transaction Customer Tracking Id: <b>

                    @if ($transaction->user_exchange_transaction_id != '')
                    {{ customerTransaction_id( $transaction->user_exchange_transaction_id)->tracking_id . ' Email:'. customerTransaction_id( $transaction->user_exchange_transaction_id)->userAccount->email       }}</b>
                @endif
                @elseif($transaction->salida === 'Incoming wallet')
                Incoming wallet Customer Tracking Id:
                {{ UserWalletsTransactions( $transaction->user_exchange_transaction_id)->tracking_id . ' Email:'. UserWalletsTransactions( $transaction->user_exchange_transaction_id)->userAccount->email       }}</b>


                @else

                Salida a un Banco: <b> {{   $transaction->banco->name }} {{   $transaction->banco->numero }}</b>

                @endif
            </div>

            @endif

            @if ($transaction->salida === null)



            <button type="submit" class="btn btn-default" name="button">Update transaction</button>
            <a href="{{ URL::to('/transactions') }}" class="btn" style="color:black">Cancel</a>

            @endif
        </div>





    </form>


</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js"
    integrity="sha256-vucLmrjdfi9YwjGY/3CQ7HnccFSS/XRS1M/3k/FDXJw=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.5.10/cleave.min.js"
    integrity="sha256-lqWAcasN+EP6bxH3+SBODfrydkyHQ7FDwcI44sZeff4=" crossorigin="anonymous"></script>
<script>
    jQuery(document).ready(function() {
    $('#r11').on('click', function(){
        $('#collapseTwo').removeClass('show')
  $(this).parent().find('a').trigger('click')
})

$('#r12').on('click', function(){
    $('#collapseOne').removeClass('show')
  $(this).parent().find('a').trigger('click')
})

var amount = new Cleave('#amount', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true
});

var usd_price = new Cleave('#precio', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true
});

    

    $("#banco").val({{$transaction->bank_name}}).trigger('change');


    $('#user_exchange_transaction_id').change(function(){

    user = $('option:selected', this).data("user");
    $('#customer_name').val(user);

    precio = $('option:selected', this).data("precio");
    $('#precio').val(precio);


    envio = $('option:selected', this).data("envio");

    console.log(envio);
     

    //var txt = $.trim( $("#texta").val());
    $("#texta").val(envio );
   

    });

    $("#user_exchange_transaction_id").val({{$transaction->user_exchange_transaction_id}}).trigger('change');;
});

   
jQuery(document).ready(function($) {


    $('#banco').select2({
    theme: "bootstrap"
    });

    $('#usuario').select2({
    theme: "bootstrap"
    });

    $('#user_exchange_transaction_id').select2({
    theme: "bootstrap"
    });


    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $('#usuario').select2({
      theme: "bootstrap",
    ajax: {
    url: "{{ url('api/users') }}",
    type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              _token: CSRF_TOKEN,
              search: params.term // search term
            };
          },
          processResults: function (response) {
            return {
              results: response
            };
          },
          cache: true
        }
});
});
</script>

@endsection