@extends('emails.emails-admin-layout')

@section('content')

<div class="email-content" style="padding: 2rem 3rem;">
    <h4 class="text-primary text-heading" style="margin: 0 0 1rem 0;color: #303392;font-weight: 400;font-size: 1.5rem;">Transaction status</h4>
    <p>Hola, el estado de su transacción ha sido cambiado: <strong> @if($data['status'] == 1)
                                                                En espera
                                                            @elseif($data['status'] == 2)
                                                                Aprobada
                                                            @elseif($data['status'] == 3)
                                                                Fallida
                                                            @elseif($data['status'] == 4)
                                                                Negada
                                                            @elseif($data['status'] == 5)
                                                                Reembolso
                                                            @elseif($data['status'] == 6)
                                                                En proceso
                                                            @endif</strong>
    </p>
    <p><strong>El ID de su transacción es: </strong> {{$data['transaction_id']}}.</p>
    <p><strong>También tiene un mensaje:</strong> {{ $data['msg'] }}</p>
    <p><strong>Click en el botón</strong> para saber más detalles.</p>

    <a href="{{$data['url']}}" class="btn-primary" style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #303392;border-radius: 10rem;font-weight: 500;">View Transaction</a>
    <p>O copia y pega el siguiente link en tu navegador:<br><a href="{{$data['url']}}" target="_blank" style="text-decoration: none;color: #346bff;">{{$data['url']}}</a></p>

    <hr/>

    <p>Puedes contar tu experencia con AKB haciendo click en el siguiente enlace:</p>
    <a href="https://www.trustpilot.com/review/americankryptosbank.com" class="btn-primary" style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #00b67a;border-radius: 10rem;font-weight: 500;"> Telling us about your experience with us</a>
    <p>O copia y pega en tu navegador:<br><a href="https://www.trustpilot.com/review/americankryptosbank.com" style="text-decoration: none;color: #346bff;">https://www.trustpilot.com/review/americankryptosbank.com</a></p>
</div>

@endsection
