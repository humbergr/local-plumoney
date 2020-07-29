@extends('layouts.mvp-layout-internal')

@section('content')

<main class="dashboard__main"
style="padding: 24px; background: #f4f4f9">
<div class="container">






  <div class="card shadow-none rounded-lg mb-4">

    <div class="card-body">

<div class="text-center">
<img width="200" src="{{ asset('img/landing/cohete.svg') }}" alt="">
</div>
      <h2 class="text-primary">Felicitaciones <b>{{Auth::user()->name}}</b> </h2>

      <p>Haz completado todos los datos para verificar tu perfil, estas mas cerca de obtener acceso a tu billetera Mía.</p>

      <p>El proceso de verificación puede tomar de 24 a 72 horas hábiles.</p>

      <p>Usted recibirá un correo electrónico, al momento de cambiar de estatus su solicitud.</p>

      <p>Le recordamos que este departamento trabaja de Lunes a Viernes de 9:00am a 7:00pm, en horario del Este de los Estados Unidos.</p>

    </div>

  </div>


</div>
</main>

@endsection
