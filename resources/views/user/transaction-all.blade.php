@extends('layouts.mvp-layout-internal')

@section('css')
<link rel="stylesheet" href="{{ asset('css/main.css') }}">
@endsection
@section('content')
<script>
    fbq('track', 'SubmitApplication');
</script>
<main class="dashboard__main">
        <div class="container mt-5 mb-5">
            <section class="mb-4 mb-md-5">

<h2 class="text-primary">Mis transacciónes
</h2>
                 <div id="history-table" class="mb-5">
                                @foreach($pagos as $transaction)
                                <div class="row border-bottom p-3">
                                    <div class="col-6 col-md-2 px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">En la fecha</small>
                                        <div class="font-14">
                                            {{$transaction->getHumanDate($transaction->created_at)}}
                                        </div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Enviaste a</small>
                                        <div class="font-weight-bold font-14">
                                            @if (isset($transaction->destinationAccount))
                                            {{$transaction->destinationAccount->name}}
                                            {{$transaction->destinationAccount->lastname}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">En el país</small>
                                        <div class="font-14">
                                            @if (isset($transaction->destinationAccount))
                                            {{$transaction->destinationAccount->getCountry()[1]}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Método</small>
                                        <div class="font-14">{{$transaction->paymentMethod()}}</div>
                                    </div>
                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Enviados</small>
                                        <div class="text-secondary font-weight-bold h5 mb-0">
                                            <small>{{$transaction->sender_fiat}}</small>
                                            {{number_format($transaction->sender_fiat_amount,2)}}
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-1 px-1 text-center my-auto">
                                        <a href="/tools/gen-t-pdf?id={{$transaction->id}}" target="_blank" class="btn-transparent">
                                            <img src="/img/landing/pdfDown.svg" class="img-fluid">
                                        </a>
                                    </div>
                                </div>
                                @endforeach

                                {{$pagos->links()}}
                            </div>
                 
            </section>

        </div>
    </main>


           
            @endsection
            @section('js')

            <script>
    
            </script>
            
 
@endsection