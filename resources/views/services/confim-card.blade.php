@extends('layouts.mvp-layout-internal')


@section('css')
<style>
    .demo-container {
        width: 100%;
        background-color: rgb(247, 246, 244);

        padding: 30px;


    }
</style>

@endsection
@section('content')





@if (session('success'))


<main class="dashboard__main" style="padding: 24px; background: #f4f4f9">
    <div class="container">


        <div class="card shadow-none rounded-lg mb-4">

            <div class="card-body">

                <div class="text-center">
                    <img width="200" src="{{ asset('img/landing/cohete.svg') }}" alt="">
                </div>
                <h2 class="text-primary">Felicitaciones <b>{{Auth::user()->name}}</b> </h2>

                <p>La tarjeta esta en proceso de verificación le notificaremos por correo su activación.</p>


                <p>Le recordamos que este departamento trabaja de Lunes a Viernes de 9:00am a 7:00pm, en horario del
                    Este de los Estados Unidos.</p>
                    <a class="btn btn-primary" href="{{ url('home', []) }}">Ir al dashboard</a>

            </div>

        </div>


    </div>
</main>
@endif

<main style="display: {{ session('success') ? 'none' : 'block' }}">
    <div class="container mt-md-n5">
        <div class="row">

            <div class="col-12 mx-auto px-0 px-md-3">
                <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                    <div class="card-body">

                        @if (tarjetaPending())


                        <div class="alert alert-warning" role="alert">
                            <strong>¡ {{ Auth::user()->name }}, tienes un proceso pendiente por verificar !</strong>
                        </div>

                        @endif


                        <h5 class="text-primary font-weight-bold mb-0">Antes de utilizar su tarjeta de crédito o débito  debemos confirmar que seas el propietario de la misma.</h5>

                        <div class="cc">
                            <p class="text-color-secondary font-14">Por razones de seguridad y para evitar futuros inconvenientes es necesario
                                 que subas una fotografía donde tengas en tu mano tu documento de identidad y la tarjeta de crédito o débito,
                                  escribiendo a mano en una hoja de papel lo siguiente:   {{Auth::user()->name ?? ''}} {{Auth::user()->last_name ?? ''}} 
                                  certifico que soy propietario de la tarjeta aceptando los términos y condiciones de esta operación realizada  con AKB y renuncio a realizar 
                                cualquier tipo de disputa o charge back ante la institución financiera que emite mi tarjeta de crédito  o débito.</p>
                                <p>La fotografía debe ser de buena calidad y no se debe ver pixelada. Esto es para proteger y evitar que personas inescrupulosas puedan utilizar su información financiera para realizar una 
                                    operación sin su consentimiento dentro de la plataforma AKB</p>
                                    
                        </div>


                        @foreach ($errors->all() as $element)
                        <br>
                        <hr>

                        <div><span class="text-danger">  {{ $element }}</span></div>
                    
                        @endforeach

                        <form action="{{ url('confirm-card', []) }}" method="POST" enctype="multipart/form-data">

                            @csrf
                            <div class="card-wrapper"></div>
                            <div class="demo-container p-4">
                               



                                <div class="d-flex justify-content-center mt-2">

                                   
                                    
                                
                                    <div class="form-container active">


                                        <div class="">
                                            <div class="small-6 columns">
                                                <input class="form-control" required placeholder="Número de trajeta"
                                                    type="text" name="number" class="discover identified">
                                                    
                                            </div>
                                            <div class="small-6 columns">
                                                <input class="form-control" required placeholder="Nombre impreso de la tarjeta"
                                                    type="text" name="name">
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="small-3 columns">
                                                <input class="form-control" required placeholder="MM/YYYY"      type="text"
                                                    name="expiry" class="jp-card-invalid">
                                                  

                                                       
                                                    
                                                                               
                                            </div>

                                            <div class="small-3 columns">
                                                <input class="form-control" required placeholder="CVC" type="text"
                                                    name="cvc">
                                            </div>

                                            {{--    <div class="small-6 columns">
                                            <input type="submit" value="Submit" class="button postfix">
                                        </div> --}}
                                        </div>

                                    </div>
                                </div>




                                <div class="text-center mt-4">

                                    <div class="media-body my-auto">


                                        <input onchange="loadFile(event)" class="btn btn-primary " type="file" required
                                            id="foto" name="photo_text" accept="image/*">
                                        4Mb Max
                                    </div>

                                    <div class="mt-3">
                                        <h4 class="text-primary">Fotografia ejemplo que debes cargar <i class="fa fa-arrow-down"></i></h4>
                                        <img id="output" src="{{ asset('images/card-confirm.jpg') }}"
                                            width="250" alt="" class="img-fluid mb-4 mb-md-0 mx-md-4 img-thumbnail">
                                    </div>

                                    <div class="form-group mt-4">
                                        <button class="btn btn-primary btn-lg" type="submit">Confirmar</button>
                                    </div>
                                </div>


                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/card/2.4.0/card.min.js"
    integrity="sha256-AefnXef338CwR+vCJ1NO944qF4dy6OxZVAmWHWnYljs=" crossorigin="anonymous"></script>

<script>
    new Card({
            form: document.querySelector('form'),
            container: '.card-wrapper',

          
            messages: {
        validDate: 'valid\ndate', // optional - default 'valid\nthru'
        monthYear: 'mm/yyyy', // optional - default 'month/year'
    },
            
        });

        var loadFile = function(event) {
    var uploadField = document.getElementById("foto");

    if(uploadField.files[0].size  > 2000000){
       alert("Archivo muy grande!");
       uploadField.value = "";
       document.getElementById('output');
       output.src = '';
       return false;
    };
 

    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('output');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
</script>
@endsection