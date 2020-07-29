@extends('emails.emails-admin-layout')

@section('content')

<div class="email-content" style="padding: 2rem 3rem;">
    <h4 class="text-primary text-heading" style="margin: 0 0 1rem 0;color: #303392;font-weight: 400;font-size: 1.5rem;">Transferencia Exitosa</h4>
    <p>Hola <strong>{{$data['sender']}}</strong>, has enviado de manera exitosa <strong>{{number_format($data['amount'], 2)}} {{$data['currency']}}</strong> a <strong>{{$data['receiver']}}</strong> a través de nuestra plataforma.</p>
    <p>Si desconoces esta operación no dudes en contactar nuestro servicio de soporte.</p>
    <p><strong>Haz click en el boton de abajo</strong> para ir a American Kryptos Bank.</p>

    <a href="{{$data['url']}}" class="btn-primary" style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #303392;border-radius: 10rem;font-weight: 500;">Ir a AKB</a>
    <p>O copia y pega este link en tu navegador:<br><a href="{{$data['url']}}" style="text-decoration: none;color: #346bff;">{{$data['url']}}</a></p>

    <hr/>

    <p>Puedes contar tu experencia con AKB haciendo click en el siguiente enlace:</p>
    <a href="https://www.trustpilot.com/review/americankryptosbank.com" class="btn-primary" style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #00b67a;border-radius: 10rem;font-weight: 500;"> Telling us about your experience with us</a>
    <p>O copia y pega en tu navegador:<br><a href="https://www.trustpilot.com/review/americankryptosbank.com" style="text-decoration: none;color: #346bff;">https://www.trustpilot.com/review/americankryptosbank.com</a></p>
</div>

@endsection
