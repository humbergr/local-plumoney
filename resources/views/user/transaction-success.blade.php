@extends('layouts.mvp-layout-internal')

@section('content')
<script>
    fbq('track', 'SubmitApplication');
</script>
<main>
    <div class="container mt-md-n5">
        <div class="row">
            <div class="col-12 mx-auto px-0 px-md-3">
                <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                    <div class="card-body py-4 py-lg-4">
                        @if ($userExchangeTransaction->status === 1)
                        <div class="text-center pb-4">
                            <img src="/img/landing/success-primary.svg" class="img-fluid mb-3">
                            <h3 class="text-primary mb-4">¡Tu envio fue exitoso!</h3>
                        </div>

                        <div class="row mb-4 mb-3 px-md-3 px-xl-4">
                            <div class="col-12 px-1 mb-3 mb-md-0 text-right">
                                <a href="{{URL::to('/transaction-success-image/'.$userExchangeTransaction->id)}}" class="btn btn-dark" target="_blank">
                                    Descargar imagen
                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                </a>
                                <a href="/tools/gen-t-pdf?id={{$userExchangeTransaction->id}}" target="_blank" type="button" class="btn btn-dark __btn_toPdf">
                                    Descargar PDF
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if ($userExchangeTransaction->status === 0)
                        <div class="text-center pb-4">
                            <img src="/img/landing/waiting-primary.svg" class="img-fluid mb-3">
                            <h3 class="text-primary mb-4">
                                Tu envío esta en revisión
                            </h3>
                        </div>

                        <div class="row mb-4 mb-3 px-md-3 px-xl-4">
                            <div class="col-6 col-md px-1 mb-3 mb-md-0 text-right">
                                <a href="{{URL::to('/transaction-success-image/'.$userExchangeTransaction->id)}}" class="btn btn-dark" target="_blank">
                                    Descargar como imagen
                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                </a>
                                <a href="/tools/gen-t-pdf?id={{$userExchangeTransaction->id}}" target="_blank" type="button" class="btn btn-dark __btn_toPdf">
                                    Descargar como PDF
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        @endif

                        <section class="__receipt_to_image receipt mb-3 px-md-3 px-xl-4" id="__receipt_to_image">
                            <h5 class="text-center mb-3">
                                Resumen de tu último envío
                            </h5>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Estado:
                                    </div>
                                </div>
                                @if ($userExchangeTransaction->status === 1)
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-success p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff;">
                                        Completado
                                    </div>
                                </div>
                                @endif
                                @if ($userExchangeTransaction->status === 0 &&
                                $userExchangeTransaction->payment_way !== 'Stripe' &&
                                $userExchangeTransaction->payment_way !== 'QuickBook')
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-warning p-3">
                                    <div class="font-weight-bold font-14">
                                        En Revisión
                                    </div>
                                </div>
                                @endif
                                @if ($userExchangeTransaction->status === 0 &&
                                ($userExchangeTransaction->payment_way === 'Stripe' ||
                                $userExchangeTransaction->payment_way === 'QuickBook'))
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-info p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff">
                                        Pagada
                                    </div>
                                </div>
                                @endif
                                @if ($userExchangeTransaction->status === 2)
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-danger p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff">
                                        Rechazada
                                    </div>
                                </div>
                                @endif
                                @if ($userExchangeTransaction->status === 3)
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-danger p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff">
                                        Fallida
                                    </div>
                                </div>
                                @endif
                                @if ($userExchangeTransaction->status === 4)
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-info p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff">
                                        En Proceso
                                    </div>
                                </div>
                                @endif
                                @if ($userExchangeTransaction->status === 5)
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-info p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff">
                                        Reembolsada
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if ($userExchangeTransaction->status === 0 &&
                            $userExchangeTransaction->payment_way !== 'Stripe' &&
                            $userExchangeTransaction->payment_way !== 'QuickBook' &&
                            $userExchangeTransaction->payment_support === null &&
                            $genericPaymentObject !== null)
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    Datos para hacer tu pago:
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <h6 class="text-primary">
                                        <img src="{{$genericPaymentObject->metadata['logo']}}" alt="{{$genericPaymentObject->title}}" class="img-fluid mx-auto" style="max-height: 55px"> - {{$genericPaymentObject->title}}
                                    </h6>
                                    <p class="font-14" style="color: #979797;">
                                        Cuando hagas tu operacion usando Venmo, por favor incluye el siguiente
                                        código de seguimiento
                                        en la descripción del pago. <b>Es muy importante</b>.
                                    </p>
                                    <p style="color: #979797;">
                                        <b>Código de seguimiento único:</b>
                                    </p>
                                    <p class="text-secondary" style="font-size: 24px">
                                        <b>{{$userExchangeTransaction->tracking_id}}</b>
                                    </p>
                                    <p class="font-14" style="color: #979797;">
                                        Estos son los datos que debes conocer para tu pago:
                                    </p>
                                    @foreach($genericPaymentObject->metadata as $key => $field)
                                    @if ($key !== 'logo' && $key !== 'slug')
                                    <div class="row __info_row px-3">
                                        <div class="col-md px-0 py-0">
                                            <span class="title"><strong>{{ucfirst($key)}}:</strong></span>
                                            <div class="info">
                                                {{ucfirst($field)}}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if ($userExchangeTransaction->status === 0 &&
                            $userExchangeTransaction->payment_way !== 'Stripe' &&
                            $userExchangeTransaction->payment_way !== 'QuickBook' &&
                            $userExchangeTransaction->payment_way !== 'userWallet')
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    Reportar Pago:
                                </div>
                                @if ($userExchangeTransaction->payment_support === null)
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 p-3 text-center" style="background: #ffe99e">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$userExchangeTransaction->id}}">
                                        <label>
                                            @php
                                            $endDate = new Carbon\Carbon(
                                            $userExchangeTransaction->created_at
                                            );
                                            $endDate = $endDate
                                            ->setTimezone('EDT')
                                            ->addMinutes(30)
                                            ->format('Y-m-d H:i:s');
                                            @endphp
                                            <span class="text-danger">
                                                Recomendamos reportar su pago antes de:
                                                {{$endDate}} (EST)
                                                <br>
                                                Acá podrás subir una Screenshoot de tu pago.
                                            </span>
                                            <br>
                                            <br>
                                            <label id="__lb_payment_report">
                                                <img src="/img/landing/repPago.png" alt="">
                                                <input type="file" style="display: none" accept="image/*,application/pdf" id="__ip_payment_report" name="paymentConfirmation">
                                            </label>
                                            <div class="__image_charged text-center" style="display: none;">
                                                <img src="/img/pay_file_success.png" alt="">
                                                <br>
                                                <input type="submit" class="btn btn-success" style="display: inline-block; border-radius: 0; width: 300px" value="Enviar Reporte">
                                            </div>
                                        </label>
                                    </form>
                                </div>
                                @else
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-success p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff;">
                                        Pago reportado.
                                        <a href="{{$userExchangeTransaction->payment_support}}" style="color: #004eff;" target="_blank">
                                            -- Ver archivo --
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            @if ($userExchangeTransaction->status === 0 &&
                            ($userExchangeTransaction->payment_way === 'Stripe' ||
                            $userExchangeTransaction->payment_way === 'QuickBook'))
                            <div class="row border-bottom">
                                <div class="col-12 px-1 mb-3 mb-md-0 bg-light p-3">
                                    Su pago <strong>se ha registrado con éxito</strong> en nuestro sistema.
                                    En minutos nuestro personal administrativo hará la revisión del mismo y
                                    procederá a enviar el dinero a la cuenta destino indicada.
                                    <strong>Gracias por confiar en nosotros.</strong>
                                </div>
                            </div>
                            @endif
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Nombre del receptor:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        @if (isset($userExchangeTransaction->destinationAccount))
                                        {{$userExchangeTransaction->destinationAccount->name}}
                                        {{$userExchangeTransaction->destinationAccount->lastname}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        País:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        @if (isset($userExchangeTransaction->destinationAccount))
                                        {{$userExchangeTransaction->destinationAccount->getCountry()[1]}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Recibe:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$userExchangeTransaction->receiver_fiat}}
                                        {{number_format(
                                            floor(($userExchangeTransaction->receiver_fiat_amount*100))/100,
                                            2
                                            )}}
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Datos de la cuenta receptora:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        @if (isset($userExchangeTransaction->destinationAccount))
                                        Banco: {{$userExchangeTransaction->destinationAccount->bank_name}}
                                        <br>
                                        Número de cuenta:
                                        {{$userExchangeTransaction->destinationAccount->account_number}}
                                        <br>
                                        Tipo de cuenta:
                                        {{$userExchangeTransaction->destinationAccount->getAccountType()[1]}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Nombre del remitente:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$userPersonProfile->first_name}}
                                        {{$userPersonProfile->last_name}}
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Monto enviado:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$userExchangeTransaction->sender_fiat}}
                                        {{number_format($userExchangeTransaction->sender_fiat_amount, 2)}}
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Método de pago:
                                    </div>
                                </div>
                                @if ($userExchangeTransaction->payment_way === 'Stripe' ||
                                $userExchangeTransaction->payment_way === 'QuickBook')
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        Tarjeta de Crédito o Débito
                                    </div>
                                </div>
                                @else
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$userExchangeTransaction->payment_source}}
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Tracking Number:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$userExchangeTransaction->tracking_id}}
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Notes:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        @foreach($notes as $note)
                                        @if($note->transaction_id === $userExchangeTransaction->id)
                                        <div class="container p-4 text-justify" id="{{$note->id}}" style="background: #fafafa;">
                                            <span class="text-secondary font-weight-bold"> Estado de la transacción:
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
                                            </span>
                                            <h6> <span class="font-weight-bold">Motivo:</span> {{$note->subject->subject}}</h6>
                                            <h6>
                                                {{$note->msg}}
                                            </h6>
                                            <span class="text-muted">{{ $note->created_at->format('d/m/Y g:i A') }}</span>
                                            <hr>
                                            @if($note->status == 4)
                                            @if($note->reply == null)
                                            <a href="#" data-toggle="collapse" data-target="#responder-{{$note->id}}" class="btn-transparent">
                                                Responder
                                            </a>
                                            <div id="responder-{{$note->id}}" class="collapse col-12 col-md-12 px-1 text-center my-auto">
                                                <form action="{{URL::to('reply-note/'.$note->id)}}" method="post" enctype="multipart/form-data">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="form-group col-sm-10">
                                                            <input type="text" name="reply" class="form-control" placeholder="Agregue una respuesta" required />
                                                        </div>
                                                        <div class="form-group col-sm-10">
                                                            <input type="file" name="reply_file[]" multiple class="form-control" placeholder="Agregue una respuesta" />
                                                        </div>
                                                        <button type="submit" class="col-sm-2 btn btn-light">Enviar</button>
                                                    </div>

                                                </form>
                                            </div>
                                            @else
                                            <h6><span class="text-primary">{{$userExchangeTransaction->merchant->name}} - {{$note->created_at}}</span></h6>
                                            <h6>{{$note->reply}}</h6>
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
                                            <hr/>
                                            @endif
                                            @endif
                                            @endif
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="text-center pb-4">
                            <a href="/send-money" class="btn btn-secondary btn-pill mr-3">
                                <img class="d-none d-md-inline-block mr-2" src="/img/landing/enviar-dinero.svg" height="32">Realizar nuevo envío
                            </a>
                            <a href="/wallets" class="btn btn-secondary btn-pill">
                                <img class="d-none d-md-inline-block mr-2" src="/img/landing/simpleWallet-secondary.svg" height="32">Volver a las billeteras
                            </a>
                        </div>

                        <section class="mt-5 px-md-3 px-xl-4">
                            <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                <h6 class="text-primary font-weight-bold text-truncate mb-md-0">Historial de tus
                                    envíos</h6>
                                <form action="/transactions-history" method="get" class="form-inline flex-md-nowrap ml-md-3">
                                    <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="dest-name" value="{{request()['dest-name']}}" placeholder="Buscar por nombre">
                                    </div>
                                    <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="dest-lastname" value="{{request()['dest-lastname']}}" placeholder="Buscar por Apellido">
                                    </div>
                                    <div class="input-group mb-3 mb-md-0">
                                        <input type="date" id="creation-date-filter" class="form-control" aria-label="Creation date filter" name="transaction-date" value="{{request()['transaction-date']}}" aria-describedby="creation-date-filter">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white text-muted" id="creation-date-filter"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3 mb-md-0">
                                        <input type="submit" class="ml-3 btn btn-primary" value="Filtrar">
                                    </div>
                                    @if (request()['dest-name'] || request()['dest-lastname'] ||
                                    request()['transaction-date'])
                                    <div class="input-group mb-3 mb-md-0">
                                        <a href="/transactions-history" class="ml-3 btn btn-primary">Limpiar</a>
                                    </div>
                                    @endif
                                </form>
                            </div>
                            <div id="history-table" class="mb-5">
                                @foreach($userExchangeTransactions as $transaction)
                                <div class="row border-bottom p-3">
                                    <div class="col-6 col-md-2 px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">En la fecha</small>
                                        <div class="font-14">
                                            {{$transaction->getHumanDate($transaction->created_at)}}
                                        </div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Enviaste a</small>
                                        <div class="font-weight-bold font-14">
                                            @if (isset($transaction->destinationAccount))
                                            {{$transaction->destinationAccount->name}}
                                            {{$transaction->destinationAccount->lastname}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">En el país</small>
                                        <div class="font-14">
                                            @if (isset($transaction->destinationAccount))
                                            {{$transaction->destinationAccount->getCountry()[1]}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Método</small>
                                        <div class="font-14">{{$transaction->paymentMethod()}}</div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Enviados</small>
                                        <div class="text-secondary font-weight-bold h5 mb-0">
                                            <small>{{$transaction->sender_fiat}}</small>
                                            {{number_format($transaction->sender_fiat_amount,2)}}
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-1 px-1 text-center my-auto">
                                        <a href="/tools/gen-t-pdf?id={{$transaction->id}}" target="_blank" class="btn-transparent">
                                            <img src="/img/landing/pdfDown.svg" class="img-fluid">
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    {{$userExchangeTransactions->links()}}
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="/send-money" class="btn btn-secondary btn-pill">
                                    <img class="d-none d-md-inline-block mr-2" src="/img/landing/enviar-dinero.svg" height="32">Realizar nuevo envío
                                </a>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade __print_receipt_modal" id="__print_receipt_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 950px !important;">
        <div class="modal-content">
            <div id="__receipt_image_container" style=" border: 1px #333 dotted; text-align: center; margin: 10px; padding: 10px"></div>
            <div class="text-center mb-2">
                Puedes descargar la imagen superior.
            </div>
        </div>
    </div>
</div>
@endsection