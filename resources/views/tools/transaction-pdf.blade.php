<!DOCTYPE html>
<html lang="en">
<head>
    <title>Transaction ID: {{$transaction->tracking_id}}</title>
    <style type="text/css">
        @page {
            margin: 0;
        }

        section {
            padding: 10mm;
        }

        header {
            padding: 10mm;
            background: #272975;
        }

        .logo-image {
            height: 50px;
        }

        table {
            width: 100%;
            border: 1px solid #8d8d8d;
        }

        h2 {
            color: #272975;
        }

        td {
            margin: 0;
            padding: 8px;
        }
    </style>
</head>
<body>
<div>
    <header>
        <img src="https://americankryptosbank.com/img/cb-img/coinbank-logo-light.png"
             class="logo-image"
             alt="American Kryptos Bank">
    </header>
    <section class="body">
        <h2>Resumen de la transacción</h2>
        <h3>Número de la transacción: {{$transaction->tracking_id}}</h3>
        <table class="tabla1">
            <tr>
                <td style="width: 30%; background: #eaeaea">Nombre del Receptor</td>
                <td>
                    @if (isset($transaction->destinationAccount))
                        {{$transaction->destinationAccount->name}}
                        {{$transaction->destinationAccount->lastname}}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 30%; background: #eaeaea">País</td>
                <td>
                    @if (isset($transaction->destinationAccount))
                        {{$transaction->destinationAccount->getCountry()[1]}}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 30%; background: #eaeaea">Recibe</td>
                <td>
                    {{$transaction->receiver_fiat}}
                    {{number_format($transaction->receiver_fiat_amount,2)}}
                </td>
            </tr>
            <tr>
                <td style="width: 30%; background: #eaeaea">Datos de la cuenta receptora</td>
                <td>
                    @if (isset($transaction->destinationAccount))
                        Banco: {{$transaction->destinationAccount->bank_name}}
                        <br>
                        Número de cuenta:
                        {{$transaction->destinationAccount->account_number}}
                        <br>
                        Tipo de cuenta:
                        {{$transaction->destinationAccount->getAccountType()[1]}}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 30%; background: #eaeaea">Nombre del remitente</td>
                <td>
                    {{$userPersonProfile->first_name}}
                    {{$userPersonProfile->last_name}}
                </td>
            </tr>
            <tr>
                <td style="width: 30%; background: #eaeaea">Monto envíado</td>
                <td>
                    {{$transaction->sender_fiat}}
                    {{number_format($transaction->sender_fiat_amount, 2)}}
                </td>
            </tr>
            <tr>
                <td style="width: 30%; background: #eaeaea">Método de pago</td>
                <td>
                    @if ($transaction->payment_way === 'Pago123')
                        Tarjeta de Crédito o Débito
                    @elseif ($transaction->payment_source === 'Cash Deposit')
                        Depósito en efectivo o Transferencia
                    @else
                        {{$transaction->payment_source}}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 30%; background: #eaeaea">Estado</td>
                @if ($transaction->status === 1)
                    <td style="background: #28a745; color: #fff;">
                        Completado
                    </td>
                @endif
                @if ($transaction->status === 0 &&
                $transaction->payment_way !== 'Pago123')
                    <td style="background: #ffc107; color: #fff;">
                        En Revisión
                    </td>
                @endif
                @if ($transaction->status === 0 &&
                $transaction->payment_way === 'Pago123')
                    <td style="background: #17a2b8; color: #fff;">
                        Pagada
                    </td>
                @endif
                @if ($transaction->status === 2)
                    <td style="background: #b8210b; color: #fff;">
                        Rechazada
                    </td>
                @endif
                @if ($transaction->status === 3)
                    <td style="background: #b86513; color: #fff;">
                        Fallida
                    </td>
                @endif
                @if ($transaction->status === 5)
                    <td style="background: #3c3c3c; color: #fff;">
                        Reembolsada
                    </td>
                @endif
            </tr>
        </table>
        @if (count($transaction->payment))
            <h2>El dinero han sido enviado</h2>
            <table class="tabla1">
                <tr>
                    <td style="width: 30%; background: #eaeaea">Banco</td>
                    <td>
                        {{$transaction->payment[0]->bank_name}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; background: #eaeaea">Número del depósito</td>
                    <td>
                        {{$transaction->payment[0]->deposit_number}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; background: #eaeaea">Número de cuenta</td>
                    <td>
                        {{$transaction->payment[0]->account_number}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; background: #eaeaea">Fecha del depósito</td>
                    <td>
                        @php
                            $depositDate = new Carbon\Carbon(
                                $transaction->payment[0]->deposit_date
                            );
                            $depositDate = $depositDate
                            ->setTimezone('EDT')
                            ->format('Y-m-d');
                        @endphp
                        {{$depositDate}}
                    </td>
                </tr>
            </table>
        @endif
    </section>
</div>
</body>
</html>
