@extends('emails.emails-admin-layout')

@section('content')

    <div class="email-content" style="padding: 2rem 3rem;">
        <h4 class="text-primary text-heading"
            style="margin: 0 0 1rem 0;color: #303392;font-weight: 400;font-size: 1.5rem;">Tu perfil ha sido
            rechazado.</h4>
        <p>Hola {{$data['user_name']}}, nuestro departamento de cumplimiento de políticas AML y KYC han revisado tus
            datos y han decidido rechazar tu perfil.</p>
        <p><strong>Motivos:</strong></p>

        <p style="white-space: pre-line;">{{ $data['message'] }}</p>

        @foreach ($data['details'] as $item)
            <p>{{ $item }}</p>
        @endforeach
        
        <p>Para más ayuda: <a href="https://youtu.be/E5NB0HSc6TM" target="_blank">https://youtu.be/E5NB0HSc6TM</a></p>
        <p>AKB #CreciendoContigo</p>
        
        <p><strong>Haz click en el botón de abajo</strong> para revisar y editar tu perfil nuevamente.</p>

        <a href="{{$data['url']}}" class="btn-primary"
           style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #303392;border-radius: 10rem;font-weight: 500;">Ir
            a AKB</a>
        <p>O copia y pega este link en tu navegador:<br><a href="{{$data['url']}}"
                                                           style="text-decoration: none;color: #346bff;">{{$data['url']}}</a>
        </p>

    </div>

@endsection
