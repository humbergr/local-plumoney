@extends('layouts.coinbank-layout')
@php
use App\Akb\Banker;

$destinationAccount = isset($transaction->destinationAccount) ? $transaction->destinationAccount->bank_name : 'Borrado';
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
        <div class="{{$transaction->exchange_rate_id ? 'col-lg-8' : 'col-lg-12'}} px-0 px-md-3">
            <div class="card shadow-none mb-4 wow fadeInUp">
                <div class="card-body py-4 py-lg-4">
                    <h5 class="text-primary font-weight-bold mb-1">Datos Personales del Cliente</h5>


                    <h6 class="text-primary">Número de transaciones del dia <span class="badge badge-primary">
                            {{$transactionOfToday}}</span> </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Nombre</label>
                                <input type="text" class="form-control" value="{{$transaction->merchant->name}}"
                                    readonly>
                            </div>
                            @if(isset($transaction->destinationAccount) &&
                            $transaction->destinationAccount->pago_movil === 1)
                            <label for="" style="color:red">*Cuenta con Pago Movil.</label>
                            @endif
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
                                <input type="text" class="form-control" value="{{$transaction->tracking_id}}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Tipo de Pago</label>
                                <input type="text" class="form-control" value="{{$transaction->payment_way}}" readonly>
                            </div>
                        </div>
                    </div>
                    @if ($transaction->exchange_rate_id &&
                    Banker::getExchangeRateByID($transaction->exchange_rate_id))
                    @php
                    $exchangeRateArray = Banker::getWalletsExchangeRateByID(
                    $transaction->exchange_rate_id,
                    true,
                    $transaction->fee_at_moment
                    );
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
                                <label for="merchant-select-country" class="text-primary">Cargado desde:</label>
                                @if (isset($exchangeRateArray))
                                <input type="text" value="{{$exchangeRateArray[0]->sender_country}}" readonly="readonly"
                                    class="form-control">
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
                                <label for="merchant-select-country" class="text-primary">Cargado a:</label>
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
                                <label for="nombres" class="text-primary">Monto a pagar.</label>
                                <input type="text" class="form-control"
                                    value="{{number_format($transaction->sender_fiat_amount, 2)}} {{$transaction->sender_fiat}}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Monto a Cargar.</label>
                                <input type="text" class="form-control"
                                    value="{{number_format($transaction->receiver_fiat_amount, 2)}} " readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Referencia/Notes.</label>
                                <input type="text" class="form-control" value="{{$transaction->notes}}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @if ($transaction->exchange_rate_id && isset($exchangeRateArray))
                        @php
                        $exchangeRatePrice = $exchangeRateArray[2];
                        if ($exchangeRateArray[2] < 1) { $exchangeRatePrice=1 /$exchangeRateArray[2]; } @endphp <div
                            class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Tasa de Cambio Convenida </label>
                                <input type="text" class="form-control"
                                    value="{{number_format($exchangeRatePrice, 2)}} {{$transaction->receiver_fiat}}"
                                    readonly>
                            </div>
                    </div>
                    @endif
                </div>

                @php
                $fileArray = explode('.', $transaction->payment_support);
                $endType = end($fileArray);
                @endphp

                @if ($transaction->payment_way === 'amaz_prepaid')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="text-primary">
                                Número de Tracking de Amazon
                            </label>
                            <br>
                            @if (isset($transaction->metadata['amaz_tracking_n']) &&
                            $transaction->metadata['amaz_tracking_n'] !== '')
                            <input type="text" class="form-control" readonly
                                value="{{ $transaction->metadata['amaz_tracking_n'] }}">
                            @else
                            <input type="text" class="form-control" readonly
                                value="No hay número de tracking de Amazon registrado">
                            @endif
                        </div>
                    </div>
                </div>
                @endif
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

                <div class="row mt-3">
                    @if($transaction->payment_way === 'cash')
                    <div class="col-md-6">
                        <button type="button" data-toggle="modal" data-target="#get-messages-modal" id=""
                            class="btn btn-light btn-block rounded-0">
                            Ver Mensajes
                        </button>
                    </div>
                    @endif
                    @if (!$transaction->bank_move)
                    <div class="col-md-6">

                        <button type="button" data-toggle="modal" data-target="#get-mov-bank" id=""
                            class="btn btn-light btn-block rounded-0">
                            Crear movimiento bancario
                        </button>
                    </div>


                    <div class="col-md-6">
                        
                        <button type="button" data-toggle="modal" data-target="#cryptocurrency-transactions" id=""
                            class="btn btn-light btn-block rounded-0">
                            Cryptocurrency Transactions
                        </button>
                    </div>

                    @else



                    @endif
                    @if ($transaction->is_payed === 0 || $transaction->is_payed === null)
                    <div class="col-md-6">
                        <button type="submit" id="" class="btn btn-light btn-block rounded-0" data-toggle="modal"
                            data-target="#mark-as-payed-modal">
                            Marcar como pagado
                        </button>
                    </div>
                    @elseif ($transaction->is_payed === 1)
                    <div class="col-md-6">
                        <button type="submit" id="" class="btn btn-light btn-block rounded-0" data-toggle="modal"
                            data-target="#unmark-as-payed-modal">
                            Desmarcar como pagado
                        </button>
                    </div>
                    @endif

                </div>

                @if ($transaction->bank_move)
                @if ( docBank('Incoming Wallet Transaction',$transaction->id) != null)

                <div class="container p-4 text-justify" style="background: #fafafa;">
                  <h6><span class="text-primary font-weight-bold">Info Movements Bank</span></h6>
                    <h5 class="text-secondary font-weight-bold"> Bank:
                        {{ docBank('Incoming Wallet Transaction',$transaction->id)->banco->name }}</h5>
                    <h6><span class="font-weight-bold">Numero:</span>
                        {{ docBank('Incoming Wallet Transaction',$transaction->id)->banco->numero }}</h6>
                    <span class="text-muted">
                        {{ docBank('Incoming Wallet Transaction',$transaction->id)->created_at->format('d/m/Y g:i A') }}</span>


                </div>
                @endif
                @endif

                @foreach($notes as $note)
                @if($note->transaction_id === $transaction->id)
                <hr>

                <div class="container p-4 text-justify" style="background: #fafafa;">
                    <h6><span class="text-primary font-weight-bold">{{$note->traderProfile->name}}</span></h6>
                    <h5 class="text-secondary font-weight-bold"> Estado de la transacción:
                        @if($note->status == 1)
                        Waiting
                        @elseif($note->status == 2)
                        Approved
                        @elseif($note->status == 3)
                        Failed
                        @elseif($note->status == 4)
                        Rejected
                        @elseif($note->status == 5)
                        Refund
                        @elseif($note->status == 6)
                        In Process
                        @endif
                    </h5>
                    <h6><span class="font-weight-bold">Motivo:</span> {{$note->subject->subject}}</h6>
                    <h6>
                        {{$note->msg}}
                    </h6>
                    <span class="text-muted">{{ $note->created_at->format('d/m/Y g:i A') }} - {{ $note->ip }} </span>
                    <hr>
                    @if($note->status == 6)
                    @if($note->reply === null)
                    <p>No reply yet</p>
                    @else
                    <h6><span class="text-primary font-weight-bold">{{$transaction->merchant->name}}</span></h6>
                    <h6>{{$note->reply}}</h6><span class="text-muted">{{ $note->updated_at->format('d/m/Y g:i A') }} -
                        {{ $note->ip_reply }} </span>
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

                @if($transaction->is_revised === 0 || $transaction->is_revised === null)
                <form class="" action="{{URL::to('/wallets/update-transaction/'.$transaction->id)}}" method="post">
                    <hr>
                    <div class="row">
                        {{ csrf_field() }}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="change-t2-status" class="text-primary">Status</label>
                                <select class="custom-select" id="change-t2-status" value="{{$transaction->status}}"
                                    name="status" @if($transaction->status !== 1 && $transaction->status !== 6)
                                    disabled
                                    @endif>
                                    @if ($transaction->is_payed === 0 || $transaction->is_payed === null)
                                    <option value="4" @if($transaction->status === 4) selected @endif>
                                        Rejected
                                    </option>
                                    <option value="3" @if($transaction->status === 3) selected @endif>
                                        Failed
                                    </option>
                                    @elseif ($user->role_id === 3 || $user->role_id === 6 || $user->role_id === 1)
                                    <option value="2" @if($transaction->status === 2) selected @endif>
                                        Approved
                                    </option>
                                    @if ($transaction->payment_way === 'Stripe')
                                    <option value="5" @if($transaction->status === 5) selected @endif>
                                        Refund
                                    </option>
                                    @endif
                                    @endif
                                    <option value="1" @if($transaction->status === 1) selected @endif>
                                        Waiting
                                    </option>
                                    <option value="6" @if($transaction->status === 6) selected @endif>
                                        In Process
                                    </option>
                                </select>
                            </div>
                        </div>
                        @if($transaction->status === 1 || $transaction->status === 6)
                        <div class="col-md-4">
                            <label for="">&nbsp;</label>
                            <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                                Actualizar
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>


    @if ($transaction->exchange_rate_id &&
    Banker::getExchangeRateByID($transaction->exchange_rate_id))
    @php
    $exchangeRateArray = Banker::getExchangeRateByID(
    $transaction->exchange_rate_id,
    true,
    $transaction->fee_at_moment
    );
    @endphp
    @endif

    @if ($transaction->exchange_rate_id && Banker::getExchangeRateByID($transaction->exchange_rate_id))
    <div class="col-lg-4">
        <div class="card shadow-none mb-4 wow fadeInUp">
            <div class="card-body py-4 py-lg-4">
                <div class="col-md-13">
                    <h5 class="text-primary font-weight-bold mb-1">Anuncios asociados a esta tasa.</h5>
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

<div class="modal modal-status-incoming fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold" id="modal-title-status"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <form action="{{URL::to('wallet/new-note-status/'.$transaction->id)}}" method="post"
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
                        <form action="{{URL::to('wallet/new-subject')}}" method="post" enctype="multipart/form-data">
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

<div class="modal fade" id="mark-as-payed-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Marcar como pagado</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row container">

                    <div class="col-md-6">
                        <form class="" action="{{URL::to('/api/wallets/mark-as-payed/'.$transaction->id)}}"
                            method="post">
                            {{ csrf_field() }}
                            <div class="container">
                                <div class="form-group">
                                    <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                                        Marcar como pagado
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <button data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-block rounded-0">
                            Cancelar
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="unmark-as-payed-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Marcar como pagado</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row container">

                    <div class="col-md-6">
                        <form class="" action="{{URL::to('/api/wallets/mark-as-not-payed/'.$transaction->id)}}"
                            method="post">
                            {{ csrf_field() }}
                            <div class="container">
                                <div class="form-group">
                                    <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                                        Desrcar como pagado
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <button data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-block rounded-0">
                            Cancelar
                        </button>
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
                <form class="" action="{{URL::to('/payment-bank-incoming/'.$transaction->id)}}" method="post"
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

                                        @foreach ( bancosMoney($transaction->sender_fiat) as $element)
                                        {{-- expr --}}

                                        <option data-moneda="{{$element->moneda}}"
                                            data-saldo2={{currency($element->saldo,'')}} data-saldo={{$element->saldo}}
                                            value="{{$element->id}}"
                                            {{ old('banco_id') == $element->id ? 'selected' : '' }}>{{$element->name}}
                                            {{$element->numero}}
                                            {{currency($element->saldo,$element->moneda)}}
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

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombres" class="text-primary">Tasa de Cambio Convenida</label>
                                    @if ($transaction->receiver_fiat !== 'USD')
                                    <input type="text" class="form-control"
                                        value="{{number_format($exchangeRatePrice ?? 1, 2)}} {{$transaction->receiver_fiat}}"
                                        readonly>
                                    @else
                                    <input type="text" class="form-control"
                                        value="{{number_format($exchangeRatePrice ?? 1, 2)}} {{$transaction->sender_fiat}}"
                                        readonly>
                                    @endif
                                </div>

                                @if ($transaction->receiver_fiat !== 'USD')
                                <input type="hidden" name="exchange_rate" class="form-control"
                                    value="{{$exchangeRatePrice?? 1}}" readonly>
                                @else
                                <input type="hidden" name="exchange_rate" class="form-control"
                                    value="{{$exchangeRatePrice?? 1}}" readonly>
                                @endif
                            </div>


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
                <form class="" action="{{URL::to('/cryptocurrency-transactions2/'.$transaction->id)}}" method="post"
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
                                        $currenciesISO[] = $transaction->sender_fiat;
                                        if ($transaction->sender_fiat === 'PAB') {
                                            $currenciesISO[] = 'USD';
                                        } elseif ($transaction->sender_fiat === 'USD') {
                                            $currenciesISO[] = 'PAB';
                                        }
                                    @endphp

                                    @foreach (transactionForCurrency('Outgoing', $currenciesISO) as $item)

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
<script>
    $(document).ready(function () {
     

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