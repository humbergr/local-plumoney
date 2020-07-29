@extends('layouts.mvp-layout')

@section('content')
    <main>
        <div class="triangle--toRight">
            <section class="py-section-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 offset-md-1 my-auto">
                            <div class="text-center text-md-left wow fadeInUp">
                                <img src="/img/landing/statistics-secondary.svg" alt="" class="img-fluid ml-md-4 mb-3">
                                <h1 class="text-primary font-weight-bold mb-4 mb-md-5">Inversión</h1>
                                <p>Te ofrecemos nuestro servicio de Inversión con el cual podrás recibir los mayores
                                    beneficios y rentabilidad en el tiempo.</p>
                                <a href="/contact" class="link--arrowRight text-secondary text-left btn-lg p-0 mb-3">Contáctanos
                                    para contarte de qué se trata <img src="/img/landing/arrow-right-secondary.svg"
                                                                       class="arrow-right ml-2"></a>
                            </div>
                        </div>
                        <div class="col-md-7  my-auto px-0">
                            <img src="/img/landing/inversion.png" alt="" class="object-cover wow fadeIn"
                                 style="pointer-events: none; user-select: none; -webkit-user-select: none;">
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection