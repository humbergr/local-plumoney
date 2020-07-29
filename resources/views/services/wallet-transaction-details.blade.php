@extends('layouts.mvp-layout-internal')
{{--  <script>
    fbq('track', 'SubmitApplication');
</script>  --}}

@section('content')
<main>
    <div class="container mt-md-n5">
        <div class="row">
            <div class="col-12 mx-auto px-0 px-md-3">
                <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                    <div class="card-body py-4 py-lg-4">
                        <!--
                            <div class="row mb-4 mb-3 px-md-3 px-xl-4">
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 text-right">
                                    <a href="{{URL::to('/transaction-success-image/'.$transaction->id)}}"
                                       class="btn btn-dark"
                                       target="_blank">
                                        Descargar como imagen
                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                    </a>
                                    <a href="/tools/gen-t-pdf?id={{$transaction->id}}"
                                       target="_blank"
                                       type="button"
                                       class="btn btn-dark __btn_toPdf">
                                        Descargar como PDF
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            -->
                        <section class="__receipt_to_image receipt mb-3 px-md-3 px-xl-4" id="__receipt_to_image">
                            <h5 class="text-center mb-3">
                                Resumen de la operación
                            </h5>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Estado:
                                    </div>
                                </div>
                                @if ($transaction->status === 1 && $transaction->type !== 3)
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-success p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff;">
                                        Completado
                                    </div>
                                </div>
                                @endif
                                @if ($transaction->status === 1 && $transaction->type === 3 &&
                                ($transaction->payment_way !== 'Stripe' && $transaction->payment_way !== 'ath_prepaid'))
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-warning p-3">
                                    <div class="font-weight-bold font-14">
                                        En Revisión
                                    </div>
                                </div>
                                @endif
                                @if ($transaction->status === 1 && $transaction->type === 3 &&
                                ($transaction->payment_way === 'Stripe' || $transaction->payment_way === 'ath_prepaid'))
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-info p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff">
                                        Pagada
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if ($transaction->type !== 1 &&
                            $transaction->type !== 4 &&
                            $transaction->status === 1 &&
                            $transaction->payment_way !== 'Stripe' &&
                            $transaction->payment_way !== 'ath_prepaid' &&
                            $transaction->payment_support === null &&
                            $genericPaymentObject !== null)
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    Datos para hacer tu pago:
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <h6 class="text-primary">
                                        <img src="{{$genericPaymentObject->metadata['logo']}}"
                                             alt="{{$genericPaymentObject->title}}"
                                             class="img-fluid mx-auto"
                                             style="max-height: 55px"> - {{$genericPaymentObject->title}}
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
                                        <b>{{$transaction->tracking_id}}</b>
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
                            @if ($transaction->type !== 1 &&
                            $transaction->type !== 4 &&
                            $transaction->status === 1 &&
                            $transaction->payment_way === 'amaz_prepaid' &&
                            $transaction->payment_support === null)
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    Datos para hacer tu pago:
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            <li>
                                                Éste método de pago consiste en el uso de Tarjetas Visa Gift Cards adquiridas en
                                                Amazon
                                            </li>
                                            <li>
                                                Recuerde que las tarjetas se presentan en los siguientes montos:
                                                <ul>
                                                    <li>25 USD</li>
                                                    <li>50 USD</li>
                                                    <li>100 USD</li>
                                                    <li>200 USD</li>
                                                </ul>
                                            </li>
                                            <li>
                                                Importante: Del total recargado se descontará un monto equivalente al 4% de la
                                                recarga.
                                                Ejemplo: De una intención de recarga de 100 USD se recargarán efectivamente 96 USD,
                                                siendo que 4 USD representa el 4% de 100 USD y se descontarán del total
                                            </li>
                                            <li>
                                                <strong>
                                                    ¡Importante!
                                                    <br>
                                                    Las tarjetas prepagadas deben enviarse a la siguiente dirección:
                                                    <br>
                                                    8444 NW 58 ST Doral FL. 33166.
                                                </strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if ($transaction->type !== 1 &&
                            $transaction->type !== 4 &&
                            $transaction->status === 1 &&
                            $transaction->payment_way !== 'Stripe' &&
                            $transaction->payment_way !== 'ath_prepaid' &&
                            $transaction->payment_way !== 'userWallet')
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    Reportar Pago:
                                </div>
                                @if ($transaction->payment_support === null)
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3 text-center">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id"
                                               value="{{$transaction->id}}">
                                        <label>
                                            @php
                                                $endDate = new Carbon\Carbon(
                                                $transaction->created_at
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
                                        </label>
                                        <br><br>
                                        @if ($transaction->payment_way === 'amaz_prepaid')
                                            <label for="amaz_tracking_n">Número de Tracking Amazon</label>
                                            <br>
                                            <input type="text"
                                                   id="amaz_tracking_n"
                                                   name="amaz_tracking_n">
                                        @endif
                                        <br>
                                        @if ($transaction->payment_way === 'amaz_prepaid')
                                            <label>Captura de pantalla de la orden final en Amazon</label>
                                        @else
                                            <label>Captura de pantalla de la operación</label>
                                        @endif
                                        <br>
                                        <label>
                                            <img src="/img/landing/repPago.png" alt="">
                                            <input type="file" style="display: none"
                                                   accept="image/*,application/pdf"
                                                   name="paymentConfirmation">
                                        </label>
                                        <br>
                                        <input type="submit" class="btn btn-danger" value="Enviar">
                                    </form>
                                </div>
                                @else
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-success p-3">
                                    <div class="font-weight-bold font-14" style="color: #fff;">
                                        Pago reportado.
                                        <a href="{{$transaction->payment_support}}"
                                           style="color: #004eff;"
                                           target="_blank">
                                            -- Ver archivo --
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            @if ($transaction->status === 1 && $transaction->type === 3 &&
                            ($transaction->payment_way === 'Stripe' || $transaction->payment_way === 'ath_prepaid') && $transaction->purpose === 1)
                            <div class="row border-bottom">
                                <div class="col-12 px-1 mb-3 mb-md-0 bg-light p-3">
                                    Su pago <strong>se ha registrado con éxito</strong> en nuestro sistema.
                                    {{--Le recordamos que las recargas a la billetera pueden tomar hasta 72 horas para
                                    completarse y poder disponer libremente de su saldo adquirido.--}}
                                    <strong>Gracias por confiar en nosotros.</strong>
                                </div>
                            </div>
                            @endif
                            @if ($transaction->type === 4)
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Tipo:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        Transferencia entre billeteras American Kryptos Bank.
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Monto de la transferencia:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{number_format($transaction->amount, 2)}} {{$transaction->currency}}
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Nombre del
                                        {{$transaction->purpose === 1 ? 'remitente:' : 'receptor:'}}
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$transaction->related_user->name}}
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Email del
                                        {{$transaction->purpose === 1 ? 'remitente:' : 'receptor:'}}
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$transaction->metadata['related_email']}}
                                    </div>
                                </div>
                            </div>
                                @if (isset($transaction->metadata['description']))
                                <div class="row border-bottom">
                                    <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                        <div class="font-14">
                                            Descripción:
                                        </div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                        <div class="font-weight-bold font-14">
                                            {{$transaction->metadata['description']}}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @else
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Tipo:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        Recarga a billetera American Kryptos Bank.
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Monto de recarga:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$transaction->receiver_fiat}}
                                        {{number_format(
                                        floor(($transaction->receiver_fiat_amount*100))/100,
                                        2
                                        )}}
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Monto pagado:
                                    </div>
                                </div>
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$transaction->sender_fiat}}
                                        {{number_format($transaction->sender_fiat_amount, 2)}}
                                    </div>
                                </div>
                            </div>
                            <div class="row border-bottom">
                                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                                    <div class="font-14">
                                        Método de pago:
                                    </div>
                                </div>
                                @if ($transaction->payment_way === 'Stripe')
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        Tarjeta de Crédito o Débito
                                    </div>
                                </div>
                                @elseif ($transaction->payment_way === 'ath_prepaid')
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        Tarjeta de Regalo: American Time Holding
                                    </div>
                                </div>
                                @else
                                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3">
                                    <div class="font-weight-bold font-14">
                                        {{$transaction->payment_source}}
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
                                        {{$transaction->tracking_id}}
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
                                        @if($note->transaction_id === $transaction->id)
                                        <div class="container p-4 text-justify" id="{{$note->id}}" style="background: #fafafa;">
                                            <span class="text-secondary font-weight-bold"> Estado de la transacción:
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
                                            </span>
                                            <h6> <span class="font-weight-bold">Motivo:</span> {{$note->subject->subject}}</h6>
                                            <h6>
                                                {{$note->msg}}
                                            </h6>
                                            <span class="text-muted">{{ $note->created_at->format('d/m/Y g:i A') }}</span>
                                            <hr>
                                            @if($note->status == 6)
                                            @if($note->reply == null)
                                            <a href="#" data-toggle="collapse" data-target="#responder-{{$note->id}}" class="btn-transparent">
                                                Responder
                                            </a>
                                            <div id="responder-{{$note->id}}" class="collapse col-12 col-md-12 px-1 text-center my-auto">
                                                <form action="{{URL::to('reply-note-incoming/'.$note->id)}}" method="post" enctype="multipart/form-data">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="form-group col-sm-10">
                                                            <input type="text" name="reply" class="form-control" placeholder="Agregue una respuesta" required />
                                                        </div>
                                                        <div class="form-group col-sm-10">
                                                            <input type="file" name="reply_file" class="form-control" placeholder="Agregue una respuesta" />
                                                        </div>
                                                        <button type="submit" class="col-sm-2 btn btn-light">Enviar</button>
                                                    </div>

                                                </form>
                                            </div>
                                            @else
                                            <h6><span class="text-primary">{{$transaction->merchant->name}} - {{$note->created_at}}</span></h6>
                                            <h6>{{$note->reply}}</h6>
                                            @php
                                            $fileArray = explode('.', $note->reply_file);
                                            $endType = end($fileArray);
                                            @endphp

                                            @if ($note->reply_file !== null)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="text-primary">(Click para ampliar o
                                                            abrir)</label>
                                                        <br>
                                                        @if ($endType !== 'pdf')
                                                        <a href="{{$note->reply_file}}" title="Ver archivo" target="_blank">
                                                            <img src="{{$note->reply_file}}" class="img-fluid" style="height: 200px !important" alt="Soporte de pago">
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
                                            <span class="text-muted">{{ $note->updated_at->format('d/m/Y g:i A') }}</span>
                                            @endif
                                            @endif
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </section>

                        <div class="text-center pb-4">
                            <a href="/wallets" class="btn btn-secondary btn-pill">
                                <img class="d-none d-md-inline-block mr-2"
                                     src="/img/landing/simpleWallet-secondary.svg"
                                     height="32">Volver a las billeteras
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
