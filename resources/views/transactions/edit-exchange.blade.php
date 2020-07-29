@extends('layouts.coinbank-layout')
@php
use App\Akb\Banker;

$destinationAccount = 'Borrado';

if (isset($transaction->destinationAccount)) {
$destinationAccount = $transaction->destinationAccount->bank_name;
} elseif (!isset($transaction->destinationAccount) && isset($transaction->destination->destination_account_json)) {
$destinationAccount = $transaction->destination_account_json->bank_name;
}

$title = $transaction->merchant->name . '. Envía: ' . number_format($transaction->sender_fiat_amount, 2). ' ' .
$transaction->sender_fiat.
' => '.number_format($transaction->receiver_fiat_amount, 2). ' ' . $transaction->receiver_fiat . ' - ' .
$destinationAccount;
@endphp



@section('css')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css"
    integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous" />

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
    integrity="sha256-nbyata2PJRjImhByQzik2ot6gSHSU4Cqdz5bNYL2zcU=" crossorigin="anonymous" />





@endsection
@section('content')

<div class="container">
    <div class="row">
        <div class="col">
            @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p>Correct the following errors:</p>
                <ul>
                    @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="{{$transaction->exchange_rate_id ? 'col-lg-8' : 'col-lg-12'}} px-0 px-md-3">
            <div class="card shadow-none mb-4 wow fadeInUp">

                <div class="card-body py-4 py-lg-4">

                    <div class="col-md-12 pr-lg-5">
                        <h5 class="text-primary font-weight-bold mb-1">Datos Personales del Cliente</h5>
                        <h6 class="text-primary">Número de transaciones del dia <span class="badge badge-primary">
                                {{$transactionOfToday}}</span> </h6>
                        @if(isset($transaction->trader_info))
                        <p class="text-primary font-weight-bold mb-1">Assigned
                            to: {{$transaction->trader_info['name']}}</p>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Nombre</label>
                                    <input type="text" class="form-control" value="{{$transaction->merchant->name}}"
                                        readonly>
                                </div>
                                <span class="mb-2 d-block">
                                    @if ($transaction->merchant->score['failurePercent'] < 20) <span>
                                        <img src="/img/icons/aprobado.png" alt="Cliente Seguro" title="Cliente Seguro">

                                        ({{$transaction->merchant->score['totalOperations']}};
                                        {{$transaction->merchant->score['successPercent']}}%)
                                </span>
                                @endif
                                @if ($transaction->merchant->score['failurePercent'] >= 20 &&
                                $transaction->merchant->score['failurePercent'] < 50) <span>
                                    <img src="/img/icons/menor-riesgo.png" alt="Cliente de Riesgo Menor"
                                        title="Cliente de Riesgo Menor">

                                    ({{$transaction->merchant->score['totalOperations']}};
                                    {{$transaction->merchant->score['successPercent']}}%)
                                    </span>
                                    @endif
                                    @if ($transaction->merchant->score['failurePercent'] >= 50 &&
                                    $transaction->merchant->score['failurePercent'] < 80) <span>
                                        <img src="/img/icons/riesgo.png" alt="Cliente de Riesgo"
                                            title="Cliente de Riesgo">

                                        ({{$transaction->merchant->score['totalOperations']}};
                                        {{$transaction->merchant->score['successPercent']}}%)
                                        </span>
                                        @endif
                                        @if ($transaction->merchant->score['failurePercent'] >= 80)
                                        <span>
                                            <img src="/img/icons/alto-riesgo.png" alt="Alto Riesgo"
                                                title="Cliente de Alto Riesgo">

                                            ({{$transaction->merchant->score['totalOperations']}};
                                            {{$transaction->merchant->score['successPercent']}}%)
                                        </span>
                                        @endif
                                        </span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Email</label>
                                    <input type="text" class="form-control" value="{{$transaction->merchant->email}}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-primary font-weight-bold mb-1">Datos de la transaccion</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Tracking ID</label>
                                    <input type="text" class="form-control" value="{{$transaction->tracking_id}}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Referencia/Notes</label>
                                    <input type="text" class="form-control" value="{{$transaction->notes}}" readonly>
                                </div>
                            </div>
                        </div>
                        @if ($transaction->payment_way === 'Pago123' )
                        @php
                        $datos = json_decode( $transaction->stripe_data,true);
                        @endphp

                        <h5 class="text-primary font-weight-bold mb-1">Merchant Pago 123</h5>

                        <div class="row">


                            <div class="col-md-6">
                                <p>{{  'Pan: '.  $datos['ms_pan_enmascarada'] }}</p>
                                <p>{{    'Monto: '.$datos['ms_monto'] }}</p>
                                <p>{{    'Cod Transaccion: '.$datos['cod_transaccion'] }}</p>
                                <p>{{    'Respuesta: '.$datos['mensaje_respuesta'] }}</p>
                            </div>
                            <div class="col-md-6">

                                <p>{{    'Aut: '.$datos['authid'] }}</p>
                                <p>{{    'Referencia: '.$datos['referencia'] }}</p>
                                <p>{{    'Nai: '.$datos['ms_nai'] }}</p>
                                <p>{{    'Currency: '.$datos['currency'] }}</p>

                            </div>
                        </div>

                        {{--   Array ( [ms_pan_enmascarada] => 6011 6*******1 6611 [ms_monto] => 107.85 [cod_transaccion] => 00 
                                 [mensaje_respuesta] => SUCCESS [authid] => 123456 [referencia] => 5354161051 [ms_nai] => 99 [currency] => USD [factura] => {"authcode":"123456","response_code":"100","orderid":"99","response":"1",
                                 "responsetext":"SUCCESS","avsresponse":"","cvvresponse":"N","type":"sale","transactionid":"5354161051"} ) 1 --}}
                        @endif

                        @if ($transaction->exchange_rate_id &&
                        Banker::getExchangeRateByID($transaction->exchange_rate_id))


                        @php
                        $exchangeRateArray = Banker::getExchangeRateByID(
                        $transaction->exchange_rate_id,
                        true,
                        $transaction->fee_at_moment
                        );

                        // dd($exchangeRateArray)
                        @endphp
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    @php
                                    use App\Http\Controllers\HelpersController;
                                    $htmlSelect = HelpersController::getAdminCountries(
                                    $transaction->sender_fiat,
                                    $transaction->receiver_fiat
                                    );
                                    @endphp
                                    <label for="merchant-select-country" class="text-primary">Enviado desde:</label>
                                    @if (isset($exchangeRateArray))
                                    <input type="text" value="{{$exchangeRateArray[0]->sender_country}}"
                                        readonly="readonly" class="form-control">
                                    @else
                                    <select id="merchant-select-country"
                                        class="custom-select flag-selector flag-selector--full" disabled>
                                        {!! $htmlSelect[0] !!}
                                    </select>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merchant-select-country" class="text-primary">Enviado a:</label>
                                    @if (isset($exchangeRateArray))
                                    <input type="text" value="{{$exchangeRateArray[0]->receiver_country}}"
                                        readonly="readonly" class="form-control">
                                    @else
                                    <select id="merchant-select-country"
                                        class="custom-select flag-selector flag-selector--full" disabled>
                                        {!! $htmlSelect[1] !!}
                                    </select>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Monto que Envía</label>
                                    <input type="text" class="form-control"
                                        value="{{number_format($transaction->sender_fiat_amount, 2)}} {{$transaction->sender_fiat}}"
                                        readonly>
                                </div>
                            </div>
                            @if ($transaction->exchange_rate_id && isset($exchangeRateArray))
                            <div class="col-md-6">
                                <div class="form-group">

                                    @if ($transaction->receiver_fiat !== 'USD')
                                    @if ($transaction->sender_fiat !== 'USD')
                                    USD$:
                                    {{ number_format($transaction->receiver_fiat_amount /  $transaction->walletTransaction->exchange_rate) }}
                                    @endif
                                    @endif



                                    <label for="nombres" class="text-primary">Monto a Recibir</label>
                                    @if ($transaction->receiver_fiat !== 'USD')
                                    <input type="text" class="form-control"
                                        value="{{number_format($transaction->receiver_fiat_amount / $exchangeRateArray[2], 2)}} USD => {{number_format($transaction->receiver_fiat_amount, 2)}} {{$transaction->receiver_fiat}}"
                                        readonly>
                                    @else
                                    <input type="text" class="form-control"
                                        value="{{number_format($transaction->sender_fiat_amount, 2)}} {{$transaction->sender_fiat}} => {{number_format($transaction->receiver_fiat_amount, 2)}} {{$transaction->receiver_fiat}}"
                                        readonly>
                                    @endif
                                </div>
                            </div>
                            @endif

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Tipo de Pago</label>
                                    <input type="text" class="form-control" value="{{$transaction->payment_way}}"
                                        readonly>
                                </div>
                            </div>
                            @if ($transaction->exchange_rate_id && isset($exchangeRateArray))
                            @php
                            $exchangeRatePrice = $exchangeRateArray[2];
                            if ($exchangeRateArray[2] < 1) { $exchangeRatePrice=1 /$exchangeRateArray[2]; } @endphp <div
                                class="col-md-6">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Tasa de Cambio Convenida</label>
                                    @if ($transaction->receiver_fiat !== 'USD')
                                    <input type="text" class="form-control"
                                        value="{{number_format($exchangeRatePrice, 2)}} {{$transaction->receiver_fiat}}"
                                        readonly>
                                    @else
                                    <input type="text" class="form-control"
                                        value="{{number_format($exchangeRatePrice, 2)}} {{$transaction->sender_fiat}}"
                                        readonly>
                                    @endif
                                </div>
                        </div>
                        @endif
                    </div>

                    @php
                    $fileArray = explode('.', $transaction->payment_support);
                    $endType = end($fileArray);
                    @endphp

                    @if ($transaction->payment_support !== null)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-primary">Soporte de pago (Click para ampliar o
                                    abrir)</label>
                                <br>
                                @if ($endType !== 'pdf')
                                <a href="{{$transaction->payment_support}}" title="Ver archivo" target="_blank">
                                    <img src="{{$transaction->payment_support}}" class="img-fluid"
                                        style="height: 200px !important" alt="Soporte de pago">
                                </a>
                                @else
                                <a href="{{$transaction->payment_support}}" title="Ver archivo" target="_blank">
                                    Ver Archivo
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @php
                    if (!isset($transaction->destinationAccount) && isset($transaction->destination_account_json)):
                    $transaction->destinationAccount = (object)$transaction->destination_account_json;
                    $deleted = true;
                    endif;
                    @endphp
                    @if (isset($transaction->destinationAccount))
                    <h5 class="text-primary font-weight-bold mb-1">
                        Datos de la cuenta bancaria destino
                        @if (isset($deleted) && $deleted === true)
                            - (Borrada)
                        @endif
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary">
                                    Nombre:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$transaction->destinationAccount->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Apellido:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$transaction->destinationAccount->lastname}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Email:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$transaction->destinationAccount->email}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Móvil:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$transaction->destinationAccount->phone_number}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    ID:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$transaction->destinationAccount->id_number}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Tax ID o RIF:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$transaction->destinationAccount->tax_id_number}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Banco:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$transaction->destinationAccount->bank_name}}" readonly>
                            </div>
                        </div>
                        @php
                        $accountType = 'Ahorro';
                        if ($transaction->destinationAccount->account_type === 1) {
                        $accountType = 'Corriente';
                        }
                        @endphp
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    N. Cuenta:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$accountType . ' ' . $transaction->destinationAccount->account_number}}"
                                    readonly>
                            </div>
                        </div>
                        @if(isset($transaction->destinationAccount) &&
                        $transaction->destinationAccount->pago_movil === 1)
                        <label for="" style="color:red">*Cuenta con Pago Movil.</label>
                        @endif
                    </div>

                    @if(!is_null($transaction->contact_id) && !is_null($transaction->outgoing) &&
                    $transaction->outgoing->profit !== 0)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    Profit:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{number_format($transaction->getProfit(), 2)}} USD" readonly>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        @if(isset($transaction->destinationAccount->aba_number))
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    ABA number:
                                </label>
                                <input type="text" class="form-control"
                                    value="{{$transaction->destinationAccount->aba_number}}" readonly>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>
                                Información para copiar
                            </label>
                            <textarea class="form-control" style="font-size: 17px"
                                rows="7">Envío: {{number_format($transaction->sender_fiat_amount, 2)}} {{$transaction->sender_fiat}} => {{number_format($transaction->receiver_fiat_amount, 2)}} {{$transaction->receiver_fiat}}&#13;&#10;Email: {{$transaction->destinationAccount->email}}&#13;&#10;Cel: {{$transaction->destinationAccount->phone_number}}&#13;&#10;Nombre: {{$transaction->destinationAccount->name}} {{$transaction->destinationAccount->lastname}}&#13;&#10;CI: {{$transaction->destinationAccount->id_number}}&#13;&#10;Banco: {{$transaction->destinationAccount->bank_name}} {{$accountType . ' ' . $transaction->destinationAccount->account_number}}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        @if ($transaction->bank_move)


                        @if($transaction->is_payed === 1)
                        @if(count($transaction->payment) !== 0)
                        <div class="col-md-6">
                            <label for="">&nbsp;</label>
                            <button type="button" data-toggle="modal" data-target="#get-payment-data-modal" id=""
                                class="btn btn-light btn-block rounded-0">
                                Comprobante de pago
                            </button>
                        </div>
                        @elseif (Auth::user()->id === $transaction->trader_id || Auth::user()->role_id === 1 ||
                        Auth::user()->role_id === 2 || Auth::user()->role_id === 6)
                        <div class="col-md-6">
                            <label for="">&nbsp;</label>
                            <button type="button" data-toggle="modal" data-target="#get-payment-modal" id=""
                                class="btn btn-light btn-block rounded-0">
                                Cargar comprobante de pago
                            </button>
                        </div>
                        @endif
                        @endif

                        @else


                        <div class="col-md-6">
                            <label for="">&nbsp;</label>
                            <button type="button" data-toggle="modal" data-target="#get-mov-bank" id=""
                                class="btn btn-light btn-block rounded-0">
                                Crear movimiento bancario
                            </button>
                        </div>
                        <div class="col-md-6">
                            <label for="">&nbsp;</label>
                            <button type="button" data-toggle="modal" data-target="#cryptocurrency-transactions" id=""
                                class="btn btn-light btn-block rounded-0">
                                Cryptocurrency Transactions
                            </button>
                        </div>


                        @endif
                    </div>

                    @if($transaction->payment_way === 'cash_deposit')
                    <div class="row">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">
                            <label for="">&nbsp;</label>
                            <button type="button" data-toggle="modal" data-target="#get-messages-modal" id=""
                                class="btn btn-light btn-block rounded-0">
                                Ver Mensajes
                            </button>
                        </div>
                    </div>
                    @endif

                    @if (($transaction->is_payed === 0 || $transaction->is_payed === null) &&
                    ($transaction->status === 0 || $transaction->status === 4) &&
                    (Auth::user()->id === $transaction->trader_id || Auth::user()->role_id === 1 ||
                    Auth::user()->role_id === 2 || Auth::user()->role_id === 6))
                    <hr>

                    <form class="" action="{{URL::to('mark-as-payed/'.$transaction->id)}}" method="post">
                        <div class="row">
                            <div class="col-md-4">

                            </div>
                            {{ csrf_field() }}
                            <div class="col-md-4">
                                <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                                    Marcar como pagado
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                    @else
                    <p class="mb-2 mt-2 text-center">
                        Cuenta de destino definitivamente borrada
                        <br>
                        Hecha antes del 16/11/2019
                    </p>

                    @if($transaction->payment_way === 'cash_deposit')
                    <div class="row">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4">
                            <label for="">&nbsp;</label>
                            <button type="button" data-toggle="modal" data-target="#get-messages-modal" id=""
                                class="btn btn-light btn-block rounded-0">
                                Ver Mensajes
                            </button>
                        </div>
                    </div>
                    @endif
                    @endif

                    @if ($transaction->bank_move)
                    @if ( docBank('Customer Transaction',$transaction->id) != null)
                        
                 
                    <div class="container p-4 text-justify" style="background: #fafafa;">
                      <h6><span class="text-primary font-weight-bold">Info Movements Bank</span></h6>
                        <h5 class="text-secondary font-weight-bold"> Bank:
                            {{ docBank('Customer Transaction',$transaction->id)->banco->name }}</h5>
                        <h6><span class="font-weight-bold">Numero:</span>
                            {{ docBank('Customer Transaction',$transaction->id)->banco->numero }}</h6>
                        <span class="text-muted">
                            {{ docBank('Customer Transaction',$transaction->id)->created_at->format('d/m/Y g:i A') }}</span>


                    </div>
                    @endif
                    @endif


                    @foreach($notes as $note)
                    @if($note->transaction_id === $transaction->id)
                    <hr>

                    <div class="container p-4 text-justify" style="background: #fafafa;">
                        <h6><span class="text-primary font-weight-bold">{{$note->traderProfile->name}}</span></h6>
                        <h5 class="text-secondary font-weight-bold"> Estado de la transacción:
                            @if($note->status == 0)
                            Open
                            @elseif($note->status == 1)
                            Approved
                            @elseif($note->status == 2)
                            Rejected
                            @elseif($note->status == 5)
                            Refund
                            @elseif($note->status == 4)
                            In Process
                            @elseif($note->status == 3)
                            Failed
                            @endif
                        </h5>
                        <h6><span class="font-weight-bold">Motivo:</span> {{$note->subject->subject}}</h6>
                        <h6>
                            {{$note->msg}}
                        </h6>
                        <span class="text-muted">{{ $note->created_at->format('d/m/Y g:i A') }} - {{ $note->ip }}
                        </span>
                        <hr>
                        @if($note->status == 4)
                        @if($note->reply === null)
                        <p>No reply yet</p>
                        @else
                        <h6><span class="text-primary font-weight-bold">{{$transaction->merchant->name}}</span></h6>
                        <h6>{{$note->reply}}</h6><span class="text-muted">{{ $note->updated_at->format('d/m/Y g:i A') }}
                            - {{ $note->ip_reply }} </span>
                        @php
                        $fileArray = explode('.', $note->reply_file);
                        $endType = end($fileArray);
                        @endphp

                        @if ($note->reply_file !== null)
                        @php
                        $files = explode(" , ", $note->reply_file);
                        foreach ($files as $key) {
                        $fileArray = explode('.', $note->reply_file);
                        $endType = end($fileArray);
                        }
                        $cont = 1;
                        @endphp
                        <label class="text-primary">(Click para ampliar o
                            abrir)</label>
                        <br>

                        <h6><span class="text-primary font-weight-bold">Archivos: {{ count($files)-1 }}</span></h6>
                        @foreach ($files as $item)
                        @php
                        $fileArray = explode('.', $item);
                        $endType = end($fileArray);
                        @endphp
                        @if($item != '')
                        @if ($endType === 'jpeg' || $endType === 'gif' || $endType === 'png' || $endType === 'jpg')
                        <a href="{{$item}}" title="Ver archivo" target="_blank">
                            <img src="{{$item}}" class="img-fluid" style="height: 200px !important" alt="Imagen">
                        </a>
                        @else
                        <a href="{{$item}}" title="Ver archivo {{$cont}}" target="_blank">
                            Ver Archivo {{ $cont }}
                        </a>
                        @endif
                        @endif
                        @php
                        $cont++
                        @endphp
                        @endforeach

                        @endif
                        @endif
                        @endif

                    </div>

                    @endif
                    @endforeach




                    <form class="" action="{{URL::to('edit-exchange-transaction/'.$transaction->id)}}" method="post">
                        <hr>
                        <div class="row">
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="t-note" class="text-primary">Internal Notes</label>
                                    <textarea class="form-control" name="notes" id="t-note" rows="7"
                                        placeholder="Internal notes...">{{$transaction->notes}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{ csrf_field() }}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="change-t-status" class="text-primary">Status</label>
                                    <select class="custom-select" id="change-t-status" value="{{$transaction->status}}"
                                        name="{{$transaction->status === 0 || $transaction->status === 4 ? 'status' : ''}}" {{($transaction->status === 0 || $transaction->status === 4)
                                                    && (Auth::user()->id === $transaction->trader_id || Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 6)
                                                    ? '' : 'disabled'}}>


                                        <option value="0" @if($transaction->status === 0) selected @endif>
                                            Open
                                        </option>
                                        <option value="4" @if($transaction->status === 4) selected @endif>
                                            In Process
                                        </option>
                                        @if($transaction->is_revised == 0 && $transaction->bank_move )
                                        @if ($transaction->is_payed === 1)
                                        <option value="1" @if($transaction->status === 1) selected @endif>
                                            Approved
                                        </option>
                                        @endif
                                        <option value="5" @if($transaction->status === 5) selected @endif>
                                            Refund
                                        </option>
                                        @endif
                                        <option value="2" @if($transaction->status === 2) selected @endif>
                                            Rejected
                                        </option>
                                        @if ($transaction->payment_way !== 'Stripe' && $transaction->payment_way !==
                                        'QuickBook')
                                        @if ($transaction->payment_way !== 'userWallet')
                                        <option value="3" @if($transaction->status === 3) selected @endif>
                                            Failed
                                        </option>
                                        @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="">&nbsp;</label>
                                @if ($transaction->status === 0 || $transaction->status === 4)
                                <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                                    Actualizar
                                </button>
                                @endif
                            </div>
                        </div>
                    </form>



                    @if(!is_null($transaction->approved_by) || !is_null($transaction->failed_by) ||
                    !is_null($transaction->rejected_by) || !is_null($transaction->refund_by))
                    <div class="row">
                        <div class="col">
                            <label>
                                Información de los cambios de status
                            </label>
                            <textarea class="form-control" style="font-size: 17px" rows="7" readonly>{{is_null($transaction->approved_by) ? '' : 'Aprobada por: '.$transaction->approved_by."\n"}}{{is_null($transaction->refund_by) ? '' : 'Reembolsada por: '.$transaction->refund_by."\n"}}{{is_null($transaction->failed_by) ? '' : 'Marcada como fallida por: '.$transaction->failed_by."\n"}}{{is_null($transaction->rejected_by) ? '' : 'Rechazada por: '.$transaction->rejected_by."\n"}}
                            </textarea>
                        </div>

                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    @if ($transaction->exchange_rate_id && Banker::getExchangeRateByID($transaction->exchange_rate_id))
    <div class="col-lg-4">
        <div class="card shadow-none mb-4 wow fadeInUp">
            <div class="card-body py-4 py-lg-4">
                <div class="col-md-13">
                    <h5 class="text-primary font-weight-bold mb-1">Anuncios asociados a esta tasa.</h5>
                    <h6 class="text-primary text-center font-weight-bold mb-1">
                        @if ($transaction->receiver_fiat === 'USD')
                        Compra de BTC
                        @else
                        Venta de BTC
                        @endif
                    </h6>
                </div>
                @foreach(json_decode($exchangeRateArray[1]->sell_price_announces, true) as $announce)
                <hr>
                Banco: {{$announce['data']['bank_name']}}
                <br><br>
                <a href="{{$announce['actions']['public_view']}}">
                    {{$announce['actions']['public_view']}}
                </a>
                @endforeach
            </div>
        </div>
    </div>


    @endif
</div>
</div>
@if (isset($transaction->destinationAccount) && $transaction->destinationAccount !== null)
<!-- add contact modal - conditional person or company -->
<div class="modal fade" id="get-payment-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Comprobante de pago</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <form class="" action="{{URL::to('/payment-data/'.$transaction->id)}}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="container">
                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Banco</label>
                            <input type="text" name="bank_name" value="{{$transaction->destinationAccount->bank_name}}"
                                class="form-control form-control-font-lg" placeholder="Nombre del banco" required
                                readonly>
                        </div>

                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Cuenta de deposito</label>
                            <input type="text" name="account_number"
                                value="{{$transaction->destinationAccount->account_number}}"
                                class="form-control form-control-font-lg" placeholder="Cuenta de deposito" required
                                readonly>
                        </div>

                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Numero de deposito</label>
                            <input type="text" name="deposit_number" class="form-control form-control-font-lg"
                                placeholder="Numero de deposito" required>
                        </div>

                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Fecha del deposito</label>
                            @php
                            $nowDate = Carbon\Carbon::now('EDT')->format('Y-m-d');
                            @endphp
                            <v-date-picker :input-props="{
                                          id: 'dest-birthday',
                                          class: 'form-control',
                                          required: true,
                                          name: 'deposit_date',
                                          type: 'text',
                                          'placeholder': 'Fecha del deposito'
                                     }" :value="'{{$nowDate}}'" :masks="{
                                          input: 'MM/DD/YYYY',
                                          data: 'MM/DD/YYYY',
                                     }"></v-date-picker>
                        </div>

                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Documento</label>
                            <input type="file" name="file" placeholder="Escriba monto a enviar">
                        </div>

                        <div class="form-group">
                            <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                                Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(count($transaction->payment) !== 0)
<!-- add contact modal - conditional person or company -->
<div class="modal fade" id="get-payment-data-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Datos del comprobante de pago</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <form class="" action="" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="container">
                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Banco</label>
                            <input type="text" name="bank_name" class="form-control form-control-font-lg"
                                value="{{$transaction->payment[0]->bank_name}}" placeholder="Nombre del banco" readonly>
                        </div>

                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Numero de deposito</label>
                            <input type="text" name="deposit_number" class="form-control form-control-font-lg"
                                value="{{$transaction->payment[0]->deposit_number}}" placeholder="Numero de deposito"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Cuenta de deposito</label>
                            <input type="text" name="account_number" class="form-control form-control-font-lg"
                                value="{{$transaction->payment[0]->account_number}}" placeholder="Cuenta de deposito"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Fecha del deposito</label>
                            <input type="text" name="date" class="form-control form-control-font-lg"
                                placeholder="Cuenta de deposito"
                                value="{{date('d M Y', strtotime($transaction->payment[0]->deposit_date))}}" readonly>
                        </div>

                        @if($transaction->payment[0]->attachment_name !== '')
                        <div class="form-group">
                            <a href="{{asset('/img/exchange-payment-data/'.$transaction->id.'/'.$transaction->payment[0]->attachment_name)}}"
                                class="btn btn-light btn-block rounded-0">
                                Ver Imagen o Archivo
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endif
<!-- add contact modal - conditional person or company -->
<div class="modal fade" id="get-messages-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Chat de la Transaccion</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="oChat" style="max-height:70vh">
                    <div class="oChat__header px-3 py-2 py-md-3">
                        <div class="media">
                            <img src="{{asset('img/landing/msg-light.svg')}}" class="img-fluid mr-3">
                            <div class="media-body text-primary">
                                <h6 class="lh-125 mb-0">Chat con</h6>
                                <h6 class="font-weight-bold lh-125 mb-0">{{$transaction->merchant->name}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="oChat__body p-3">
                        @foreach($transaction->messages as $msg)
                        @if($transaction->merchant->id === $msg->sender_id && $msg->msg !== '')
                        <div class="oChat__item mb-4 --received">
                            <div class="oChat__item__msg">
                                <div class="oChat__item__avatar rounded-circle">
                                    <img src="{{asset('img/cb-img/avatar.png')}}" class="object-cover rounded-circle">
                                </div>
                                <div class="oChat__item__msgText">
                                    {{$msg->msg}}
                                </div>
                            </div>
                        </div>
                        @elseif($transaction->merchant->id !== $msg->sender_id && $msg->msg !== '')
                        <div class="oChat__item mb-4 --sent">
                            <div class="oChat__item__msg">
                                <div class="oChat__item__avatar rounded-circle">
                                    <img src="{{asset('img/cb-img/avatar.png')}}" class="object-cover rounded-circle">
                                </div>
                                <div class="oChat__item__msgText">
                                    {{$msg->msg}}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($transaction->merchant->id === $msg->sender_id && $msg->attachment_name !== '')
                        <div class="oChat__item mb-4 --received">
                            <div class="oChat__item__msg">
                                @if($msg->msg === '')
                                <div class="oChat__item__avatar rounded-circle">
                                    <img src="{{asset('img/cb-img/avatar.png')}}" class="object-cover rounded-circle">
                                </div>
                                @endif
                                <div class="oChat__item__msgText">
                                    <a href="{{URL::to('/img/orders-imgs/'.$transaction->id.'/'.$msg->attachment_name)}}"
                                        target="_blank" class="font-weight-bold"
                                        style="color:white">{{$msg->attachment_name}} <i
                                            class="fa fa-paperclip va-middle"></i></a>
                                </div>
                            </div>
                        </div>
                        @elseif($transaction->merchant->id !== $msg->sender_id && $msg->attachment_name !== '')
                        <div class="oChat__item mb-4 --sent">
                            <div class="oChat__item__msg">
                                @if($msg->msg === '')
                                <div class="oChat__item__avatar rounded-circle">
                                    <img src="{{asset('img/cb-img/avatar.png')}}" class="object-cover rounded-circle">
                                </div>
                                @endif
                                <div class="oChat__item__msgText">
                                    <a href="{{URL::to('/img/orders-imgs/'.$transaction->id.'/'.$msg->attachment_name)}}"
                                        target="_blank" class="font-weight-bold">{{$msg->attachment_name}} <i
                                            class="fa fa-paperclip va-middle"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-status fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold" id="modal-title-status"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <form action="{{URL::to('new-note-status/'.$transaction->id)}}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="container">
                        <h6><span class="text-danger">Atención Operador: </span>
                            El siguiente mensaje aqui anexado llegara directametne
                            al cliente via correo electronico
                        </h6>
                        <input type="hidden" name="status" id="status">
                        <div class="form-group">
                            <label for="subjects">Motivo del estado</label>
                            <select class="custom-select" id="subjects" value="{{$transaction->status}}" name="subjects"
                                required>


                            </select>
                            @if( Auth::user()->role_id === 6 || Auth::user()->role_id === 1)
                            <div class="mt-2 button-ud">
                                <button data-toggle="modal" data-target="#subjects-modal"
                                    class="btn-edit btn btn-light rounded-0">
                                    Crear Nuevo Motivo
                                </button>
                            </div>
                            @endif

                        </div>


                        <div class="form-group">
                            <label for="status-message" class="text-primary">Mensaje</label>
                            <textarea class="form-control" style="font-size: 17px" rows="7" name="status-message"
                                placeholder="Mensaje" required>American Kryptos Bank #CreciendoContigo</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-light btn-block rounded-0">
                                Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="subjects-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Borrar</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="container align-content-center">
                    <h5>Crear Motivo</h5>
                    <div class="container py-3">
                        <table class="table text-center table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Motivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="list_subjects">

                            </tbody>
                        </table>


                        <button data-toggle="modal" data-target="#new-modal" class="btn-edit btn btn-light rounded-0">
                            Nuevo Motivo
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="new-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Nuevo Motivo</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="container align-content-center">
                    <h5>Agregar un nuevo motivo</h5>
                    <div class="container py-3">
                        <form action="{{URL::to('new-subject')}}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="hidden" name="status" id="setStatus">
                                <input class="form-control" type="text" name="subject" placeholder="Nuevo Motivo" />
                            </div>
                            <button type="submit" class="btn btn-success rounded-0">Guardar</button>
                            <button class="btn btn-light rounded-0" data-dismiss="modal">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="get-mov-bank" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Movimiento bancario</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <form class="" action="{{URL::to('/payment-bank/'.$transaction->id)}}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="container">



                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('emision') ? ' has-danger' : '' }}">
                                    <label class="form-control-label text-primary"
                                        for="input-emision">{{ __('Fecha de emision:') }}</label>
                                    <input type="text" name="emision" id="emision"
                                        class="form-control  form-control-alternative{{ $errors->has('emision') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('DD-MM-YYYY') }}"
                                        value="{{ old('emision', @$model->emision) }}" required>

                                    @if ($errors->has('emision'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('emision') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="feInputState" class="text-primary">Caja u/o Banco:</label>
                                    <select id="banco" name="banco_id"
                                        class="select2 form-control form-control-alternative{{ $errors->has('banco_id') ? ' is-invalid' : '' }}"
                                        required>
                                        <option value="">Selecciona:</option>

                                        @foreach ( bancosMoney($transaction->receiver_fiat) as $elementselect)
                                        {{-- expr --}}

                                        <option data-moneda="{{$elementselect->moneda}}"
                                            data-saldo2="{{currency($elementselect->saldo,'')}}"
                                            data-saldo="{{$elementselect->saldo}}"
                                            data-comision="{{$elementselect->comision}}" value="{{$elementselect->id}}"
                                            {{ old('banco_id') == $elementselect->id ? 'selected' : '' }}>
                                            {{$elementselect->name}}
                                            {{$elementselect->numero}}
                                            {{currency($elementselect->saldo,$elementselect->moneda)}}
                                        </option>
                                        @endforeach

                                    </select>
                                    @if ($errors->has('banco_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('banco_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Monto</label>
                                    @if ($transaction->receiver_fiat !== 'USD')
                                    <input type="text" class="form-control"
                                        value="{{number_format($transaction->receiver_fiat_amount / $exchangeRateArray[2], 2)}} USD => {{number_format($transaction->receiver_fiat_amount, 2)}} {{$transaction->receiver_fiat}}"
                                        readonly>
                                    @else
                                    <input type="text" class="form-control"
                                        value="{{number_format($transaction->sender_fiat_amount, 2)}} {{$transaction->sender_fiat}} => {{number_format($transaction->receiver_fiat_amount, 2)}} {{$transaction->receiver_fiat}}"
                                        readonly>
                                    @endif
                                </div>
                            </div>

                            @if(isset($exchangeRatePrice))
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Tasa de Cambio Convenida</label>
                                    @if ($transaction->receiver_fiat !== 'USD')
                                    <input type="text" class="form-control"
                                        value="{{number_format($exchangeRatePrice, 2)}} {{$transaction->receiver_fiat}}"
                                        readonly>
                                    @else
                                    <input type="text" class="form-control"
                                        value="{{number_format($exchangeRatePrice, 2)}} {{$transaction->sender_fiat}}"
                                        readonly>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('comision') ? ' has-danger' : '' }}">
                                    <label class="form-control-label text-primary" for="input-comision">
                                        </span>{{ __('Banking fee:') }}</label>
                                    <input type="text" value="0" name="comision" id="comision"
                                        class="form-control  form-control-alternative{{ $errors->has('comision') ? ' is-invalid' : '' }}"
                                        placeholder="{{ __('comision:') }}"
                                        value="{{ old('comision', @$model->comision) }}" required>

                                    @if ($errors->has('comision'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('comision') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                        </div>

                        <div class="form-group">
                            <input type="submit" value="Enviar" id=""
                                onclick="this.disabled=true;this.value='Submitting...'; this.form.submit();"
                                class="btn btn-light btn-block rounded-0">


                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="cryptocurrency-transactions" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Cryptocurrency Transactions</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <form class="" action="{{URL::to('/cryptocurrency-transactions/'.$transaction->id)}}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="container">

                        <div style="height: 200px; overflow-y: scroll;">

                            <table class="table table-striped  table-responsive">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Transaction_id</th>
                                        <th>Currency</th>
                                        <th>Amount</th>

                                </thead>
                                <tbody>
                                    @php
                                        $currenciesISO = [];
                                        $currenciesISO[] = $transaction->receiver_fiat;
                                        if ($transaction->receiver_fiat === 'PAB') {
                                            $currenciesISO[] = 'USD';
                                        } elseif ($transaction->receiver_fiat === 'USD') {
                                            $currenciesISO[] = 'PAB';
                                        }
                                    @endphp

                                    @foreach (transactionForCurrency('Incoming', $currenciesISO) as $item)

                                    <tr>
                                        <td scope="row"><input type="checkbox" class="mr-2" value="{{ $item->id }}"
                                                name="transaction[]" id="">{{ $item->id }} - {{ $item->transaction_id }}
                                        </td>
                                        <td>{{ $item->currency }}{{ currency($item->amount,'') }}</td>
                                        <td>{{ $item->amount_btc }}</td>

                                    </tr>


                                    @endforeach



                                </tbody>
                            </table>
                        </div>

                        <hr>


                        <div class="form-group">
                            <input type="submit" value="Enviar" id=""
                                onclick="this.disabled=true;this.value='Submitting...'; this.form.submit();"
                                class="btn btn-light btn-block rounded-0">


                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal-edit"></div>
@endsection
@section('scripts')


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/moment.min.js"
    integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.js"
    integrity="sha256-iacRP5fv2z3yGk6gnwi/CjK8GRrr5MROIurU7iwYXRM=" crossorigin="anonymous"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.5.10/cleave.min.js"
    integrity="sha256-lqWAcasN+EP6bxH3+SBODfrydkyHQ7FDwcI44sZeff4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
     
    var comision = new Cleave('#comision', {
  numeral: true,
  numeralThousandsGroupStyle: 'thousand',
  autoUnmask: true
});



$('#banco').change(function(){
     
 ///   alert($(this).val())
    comisionBank = $('option:selected', this).data("comision");



    let monto = {{ $transaction->receiver_fiat_amount  }}
    comision.setRawValue( (monto  * comisionBank ).toFixed(2) );
 //  $('#fee_bank').val(amount.getRawValue()  * comision /100);
   console.log(comisionBank);
})
$('#emision').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    "locale": {
        "format": "MM-DD-YYYY",
    }
    
  });
});
    
</script>

@endsection