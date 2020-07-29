<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- STYLES -->
    <link rel="stylesheet" href="{{ asset('css/coinbank.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">

    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <title>American Kryptos Bank</title>
</head>
<body>
<div id="app">

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
            $userExchangeTransaction->payment_way !== 'Stripe')
                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-warning p-3">
                    <div class="font-weight-bold font-14">
                        En Revisión
                    </div>
                </div>
            @endif
            @if ($userExchangeTransaction->status === 0 &&
            $userExchangeTransaction->payment_way === 'Stripe')
                <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-info p-3">
                    <div class="font-weight-bold font-14" style="color: #fff">
                        Pagada
                    </div>
                </div>
            @endif
        </div>
        @if ($userExchangeTransaction->status === 0 &&
            $userExchangeTransaction->payment_way !== 'Stripe' &&
            $userExchangeTransaction->payment_support === null &&
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
            $userExchangeTransaction->payment_way !== 'Stripe')
            <div class="row border-bottom">
                <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                    Reportar Pago:
                </div>
                @if ($userExchangeTransaction->payment_support === null)
                    <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-light p-3 text-center">
                        <form action="" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id"
                                   value="{{$userExchangeTransaction->id}}">
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
                                <br> <br>
                                <label>
                                    <img src="/img/landing/repPago.png" alt="">
                                    <input type="file" style="display: none"
                                           accept="image/*,application/pdf"
                                           name="paymentConfirmation">
                                </label>
                                <br>
                                <input type="submit" class="btn btn-danger" value="Enviar">
                            </label>
                        </form>
                    </div>
                @else
                    <div class="col-6 col-md px-1 mb-3 mb-md-0 bg-success p-3">
                        <div class="font-weight-bold font-14" style="color: #fff;">
                            Pago reportado.
                            <a href="{{$userExchangeTransaction->payment_support}}"
                               style="color: #004eff;"
                               target="_blank">
                                -- Ver archivo --
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        @if ($userExchangeTransaction->status === 0 &&
            $userExchangeTransaction->payment_way === 'Stripe')
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
                    {{number_format($userExchangeTransaction->receiver_fiat_amount,2)}}
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
                    {{number_format($userExchangeTransaction->sender_fiat_amount,2)}}
                </div>
            </div>
        </div>
        <div class="row border-bottom">
            <div class="col-6 col-md-4 px-1 mb-3 mb-md-0 p-3">
                <div class="font-14">
                    Método de pago:
                </div>
            </div>
            @if ($userExchangeTransaction->payment_way === 'Stripe')
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
    </section>

</div>

<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            html2canvas(document.querySelector("#__receipt_to_image")).then(canvas => {
                window.open(canvas.toDataURL("image/png"), '_self');
            });
        }, 100);
    });
</script>
</body>
</html>
