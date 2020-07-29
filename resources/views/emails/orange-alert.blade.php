@extends('emails.emails-admin-layout')

@section('content')

<div class="email-content" style="padding: 2rem 3rem;">
    <h4 class="text-primary text-heading" style="margin: 0 0 1rem 0;color: #303392;font-weight: 400;font-size: 1.5rem;">Alerta de Precios</h4>

    <p>Alerta naranja, los BTCs para la venta estan mas del 1% por encima del precio sugerido.</p>
    <p><strong>Click en el boton de abajo</strong> para ir a transacciones.</p>

    <a href="{{URL::to('/transactions')}}" class="btn-primary" style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #303392;border-radius: 10rem;font-weight: 500;">Ver Transacciones</a>
    <p>O copia y pega el siguiente link en el navegador:<br><a href="{{URL::to('/transactions')}}" style="text-decoration: none;color: #346bff;">{{URL::to('/transactions')}}</a></p>

</div>

@endsection
