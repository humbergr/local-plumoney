@extends('layouts.coinbank-layout')
@php
use App\Akb\Banker;

$destinationAccount = isset($transaction->destinationAccount) ? $transaction->destinationAccount->bank_name : 'Borrado';
$title = $transaction->merchant->name . '. Envía: ' . number_format($transaction->sender_fiat_amount, 2). ' ' . $transaction->sender_fiat.
' => '.number_format($transaction->receiver_fiat_amount, 2). ' ' . $transaction->receiver_fiat . ' - ' . $destinationAccount;
@endphp
@section('content')

<div class="container">
    <div class="row">
        <div class="{{$transaction->exchange_rate_id ? 'col-lg-8' : 'col-lg-12'}} px-0 px-md-3">
            <div class="card shadow-none mb-4 wow fadeInUp">
                <div class="card-body py-4 py-lg-4">
                    <h5 class="text-primary font-weight-bold mb-1">Datos Personales del Cliente</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Nombre</label>
                                <input type="text" class="form-control"
                                       value="{{$transaction->merchant->name}}"
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
                                <input type="text" class="form-control"
                                       value="{{$transaction->merchant->email}}"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-primary font-weight-bold mb-1">Datos de la transaccion</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Tracking ID</label>
                                <input type="text" class="form-control"
                                       value="{{$transaction->tracking_id}}"
                                       readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Tipo de Pago</label>
                                <input type="text" class="form-control"
                                       value="{{$transaction->payment_way}}"
                                       readonly>
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
                                <label for="nombres" class="text-primary">Saldo a retirar:</label>
                                <input type="text" class="form-control"
                                       value="{{number_format($transaction->sender_fiat_amount, 2)}} {{$transaction->sender_fiat}}"
                                       readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Monto a Transferir:</label>
                                <input type="text" class="form-control"
                                       value="{{number_format($transaction->receiver_fiat_amount, 2)}} {{$transaction->receiver_fiat}}"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @if ($transaction->exchange_rate_id && isset($exchangeRateArray))
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombres" class="text-primary">Tasa de Cambio Convenida</label>
                                <input type="text" class="form-control"
                                       value="{{number_format($exchangeRateArray[2], 2)}} {{$transaction->receiver_fiat}}"
                                       readonly>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($exchangeTransaction->status !== 0)
                        @if($user->role_id === 3 || $user->role_id === 6 || $user->role_id === 1)
                        <form class="" action="{{URL::to('/wallets/update-transaction/'.$transaction->id)}}"
                              method="post">
                            <hr>
                            <div class="row">
                                {{ csrf_field() }}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="change-t-status" class="text-primary">Status</label>
                                        <select class="custom-select"
                                                id="change-t-status"
                                                value="{{$transaction->status}}"
                                                name="status"
                                                @if($transaction->status !== 1 || $exchangeTransaction->status === 2 ||
                                            $exchangeTransaction->status === 3)
                                            disabled
                                            @endif>
                                            <!--
                                                    <option value="0"
                                                    @if($transaction->status == 0) selected @endif>
                                                        Waiting
                                                    </option>
                                                    <option value="4"
                                                            @if($transaction->status == 4) selected @endif>
                                                        Rejected
                                                    </option>
                                                    -->
                                            <option value="1"
                                                    @if($transaction->status == 1) selected @endif>
                                                Waiting
                                            </option>
                                            <!-- <option value="5"
                                                            @if($transaction->status === 5) selected @endif>
                                                        Refund
                                                    </option> -->
                                            <option value="2"
                                                    @if($transaction->status == 2) selected @endif>
                                                Approved
                                            </option>
                                            @if ($exchangeTransaction->status !== 1)
                                            <option value="3"
                                                    @if($transaction->status == 3) selected @endif>
                                                Failed
                                            </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if($transaction->status !== 3 && $transaction->status !== 2 && (Auth::user()->role_id === 3
                                || Auth::user()->role_id === 1 || Auth::user()->role_id === 6))
                                <div class="col-md-4">
                                    <label for="">&nbsp;</label>
                                    <button type="submit"
                                            id=""
                                            class="btn btn-light btn-block rounded-0">
                                        Actualizar
                                    </button>
                                </div>
                                @endif
                            </div>
                        </form>
                        @endif
                    @else
                    <div class="row">
                        <div class="col-md-12 text-center font-14 font-italic">
                            Aún no ha sido procesado por el departamento de Cambios
                        </div>
                    </div>
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
                                    <img src="{{asset('img/cb-img/avatar.png')}}"
                                         class="object-cover rounded-circle">
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
                                    <img src="{{asset('img/cb-img/avatar.png')}}"
                                         class="object-cover rounded-circle">
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
                                    <img src="{{asset('img/cb-img/avatar.png')}}"
                                         class="object-cover rounded-circle">
                                </div>
                                @endif
                                <div class="oChat__item__msgText">
                                    <a href="{{URL::to('/img/orders-imgs/'.$transaction->id.'/'.$msg->attachment_name)}}"
                                       target="_blank"
                                       class="font-weight-bold"
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
                                    <img src="{{asset('img/cb-img/avatar.png')}}"
                                         class="object-cover rounded-circle">
                                </div>
                                @endif
                                <div class="oChat__item__msgText">
                                    <a href="{{URL::to('/img/orders-imgs/'.$transaction->id.'/'.$msg->attachment_name)}}"
                                       target="_blank"
                                       class="font-weight-bold">{{$msg->attachment_name}} <i
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

@endsection
