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
                                <input id="amount" type="text" class="form-control" name="amount"
                                    value="{{$transaction->amount}}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">Fee Bank</label>
                                <input id="fee_bank" type="text" class="form-control" name="fee_bank"
                                    value="{{@$transaction->fee_bank}}">
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
                                <input type="text" id="usd_price" class="form-control" name="usd_price"
                                    value="{{$transaction->usd_price}}" required>
                            </div>
                        </div>

                        <br>

                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <label for='r11' style='width: 350px;'>
                                            <input type='radio' id='r11' name='salida' value='Fiat'
                                                {{ ($transaction->salida === 'Fiat') ? 'checked'  : ''}} required />
                                            Fiat
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"></a>
                                        </label>
                                    </h4>
                                </div>
                                <div id="collapseOne"
                                    class="panel-collapse collapse in {{ ($transaction->salida === 'Fiat') ? 'show'  : ''}}">
                                    <div class="panel-body">
                                        <label for="feInputState" class="text-primary"> Banco donde saco los
                                            fondos:</label>
                                        <select id="banco" name="banco_id"
                                            class="select2 form-control form-control-alternative{{ $errors->has('banco_id') ? ' is-invalid' : '' }}">
                                            <option value="">Selecciona:</option>

                                            @foreach ( bancosMoney($transaction->currency) as $element)
                                            {{-- expr --}}

                                            <option data-moneda="{{$element->moneda}}"
                                                data-saldo2={{currency($element->saldo,'')}}
                                                data-comision={{$element->comision}}
                                                data-saldo={{$element->saldo}} value="{{$element->id}}"
                                                {{ @$transaction->banco_id == $element->id ? 'selected' : '' }}>
                                                {{$element->name}}
                                                {{$element->numero}}
                                                {{currency($element->saldo,$element->moneda)}}
                                            </option>
                                            @endforeach

                                        </select>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class=panel-title>
                                        <label for='r12' style='width: 350px;'>
                                            <input type='radio' id='r12' name='salida' value='Other Exchange'
                                                {{ ($transaction->salida === 'Other Exchange') ? 'checked'  : ''}}
                                                required />
                                            Other Exchange
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"></a>
                                        </label>
                                    </h4>
                                </div>
                                <div id="collapseTwo"
                                    class="panel-collapse collapse {{ ($transaction->salida === 'Other Exchange') ? 'show'  : ''}}">
                                    <div class="panel-body">
                                        <div class="col">

                                            <label for="visible">Exchange</label>
                                            <input type="text" class="form-control" name="exchange"
                                                value="{{$transaction->exchange}}">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <br>
                        <div class="row">
                            <div class="col">
                                <label for="visible">Customer name</label>
                                <input type="text" class="form-control" name="customer_name"
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
                            <div class="col">
                                <label for="visible">Additional information</label>
                                <textarea name="msg" class="form-control" rows="8" cols="80"
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



                @if ($transaction->salida === 'Other Exchange')
                Exchange: <b>
                    {{   $transaction->exchange }}</b>
                @else

                Salida del Banco: <b> {{   $transaction->banco->name }} {{   $transaction->banco->numero }}</b>

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
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.5.10/cleave.min.js"
    integrity="sha256-lqWAcasN+EP6bxH3+SBODfrydkyHQ7FDwcI44sZeff4=" crossorigin="anonymous"></script>

<script>
    jQuery(document).ready(function() {


        var amount = new Cleave('#amount', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true
});
  var fee_bank = new Cleave('#fee_bank', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true
});

var usd_price = new Cleave('#usd_price', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    autoUnmask: true
});


        $('#r11').on('click', function(){
        $('#collapseTwo').removeClass('show')
  $(this).parent().find('a').trigger('click')

 

  $('#banco').change(function(){
   
     

     $.ajax({
         type: "POST",
         url: "{{url('api/lotes') }}",
         data:  { banco : $(this).val() },
         dataType: "json",
         success: function (response) {

             try {
             //   console.log(response[0]['tasa']);
             //console.log(amount.getRawValue()) ;
             $('#usd_price').val( (amount.getRawValue() /response[0]['tasa']).toFixed(2) ); 
           
             } catch (error) {
                $('#usd_price').val(0); 
             }
          
             
         }
     });


   
    user = $('option:selected', this).data("user");
    $('#customer_name').val(user);

    precio = $('option:selected', this).data("precio");
    $('#precio').val(precio);


    envio = $('option:selected', this).data("envio");
    comision = $('option:selected', this).data("comision");
   
    fee_bank.setRawValue((amount.getRawValue()  * comision).toFixed(2));

  //  $('#fee_bank').val(amount.getRawValue()  * comision /100);
    console.log(amount.getRawValue());
    console.log( comision);
    console.log( amount.getRawValue()  * comision);
     

    //var txt = $.trim( $("#texta").val());
    $("#texta").val(envio );
   

    });
})



$('#r12').on('click', function(){
    $('#collapseOne').removeClass('show')
  $(this).parent().find('a').trigger('click')
})

    $('#banco').select2({
    theme: "bootstrap"
    });

    $('#user_exchange_transaction_id').select2({
    theme: "bootstrap"
    });

    $("#banco").val({{$transaction->bank_name}}).trigger('change');


  

    $("#user_exchange_transaction_id").val({{$transaction->user_exchange_transaction_id}}).trigger('change');;
});

 
</script>

@endsection