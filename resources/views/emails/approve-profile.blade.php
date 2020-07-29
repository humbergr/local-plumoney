@extends('emails.emails-admin-layout')

@section('content')

<div class="email-content" style="padding: 2rem 3rem;">
    <h4 class="text-primary text-heading" style="margin: 0 0 1rem 0;color: #303392;font-weight: 400;font-size: 1.5rem;">Tu perfil ha sido aprobado.</h4>
    <p>Felicidades {{$data['user_name']}}, ahora puedes enviar dinero a través de nuestra plataforma.</p>
    <p><strong>Haz click en el boton de abajo</strong> para ir a American Kryptos Bank.</p>

    <a href="{{$data['url']}}" class="btn-primary" style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #303392;border-radius: 10rem;font-weight: 500;">Ir a AKB</a>
    <p>O copia y pega este link en tu navegador:<br><a href="{{$data['url']}}" style="text-decoration: none;color: #346bff;">{{$data['url']}}</a></p>

</div>

@endsection
