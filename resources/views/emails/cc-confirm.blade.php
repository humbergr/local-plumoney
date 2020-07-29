@extends('emails.emails-admin-layout')

@section('content')

    <div class="email-content" style="padding: 2rem 3rem;">
        <h4 class="text-primary text-heading"
            style="margin: 0 0 1rem 0;color: #303392;font-weight: 400;font-size: 1.5rem;"> Proceso de confirmación de tarjeta.</h4>
        <p>Hola {{$data['user_name']}}, nuestro departamento de cumplimiento a revisado tus
            datos y foto enviada decidiendo <strong> {{ $data['subjects'] }}.<strong></p>
        
        
      
        
        <p style="white-space: pre-line;">{{ $data['message'] }}</p>
        

        <a href="{{$data['url']}}" class="btn-primary"
           style="text-decoration: none;color: white;display: inline-block;padding: 0.75rem 1rem;background-color: #303392;border-radius: 10rem;font-weight: 500;">Ir
            a AKB</a>
     

    </div>

@endsection
