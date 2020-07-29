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
                <div class="row justify-content-center">
                    <div class="col-md-4 col-lg-5 col-xl-4">
                        <div class="position-relative d-none d-md-block">
                            <img src="{{ url('img/') }}/landing/cc-success-photo.jpg" alt="Go to cc website" class="img-fluid rounded-lg shadow-lg mb-4 mb-md-0">
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-6 offset-lg-1 col-xl-5 offset-xl-1">
                        <h5 class="text-primary font-weight-bold mb-4"><img src="{{ url('img/') }}/landing/search-icon-2.svg" class="mr-2" style="max-height: 50px">Su operación ha sido aprobada con éxito</h5>
                        <div class="alert alert-success my-5">
                            <div class="media">
                                
                                <div class="media-body">En su estado de cuenta de la tarjeta de crédito o debito vera un consumo a nombre de AKB</div>
                                
                            </div>
                        </div>
                        <a target="_blank" href="{{ url('tools/gen-t-pdf?id='.$pago->id) }}" class="btn btn-light btn-block"><img src="{{ url('img/') }}/landing/trans-resume.svg" class="mr-3" style="max-height: 32px">Descarga el resumen de tu transacción</a>
                    </div>
                </div>
            </section>

            <section>
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card card-body shadow-none text-primary rounded-lg">
                            <div class="  mb-5">
                                <h6 class="card-title font-weight-bold mb-0">Detalles de la transacción</h6>
                                <div class="text-muted  font-14">Fecha {{$pago->created_at->format('d-m-Y')  }}</div>
                            </div>


                            @if ($pago->sender_fiat === 'USD')
                                
                           
  
                            <div class="row align-items-center mb-5">
                                <div class="col-md-4 mb-4 mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Monto a transferir</div>
                                        <div class="font-14  text-truncate"> {{$pago->sender_fiat}}
                                    {{number_format($pago->sender_fiat_amount, 2)}} </div>
                                    </div>

                                    @php
                                    $pa = json_decode($pago['stripe_data'],true) 
                                  @endphp
                                  <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                      <div class="small text-truncate mr-2">Comisión</div>
                                      <div class="font-14   text-truncate"> USD {{     $pa['ms_monto'] - $pago->sender_fiat_amount }}</div>
                                  </div>

                                  <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                    <div class="small text-truncate mr-2">Total cobrado</div>
                                    <div class="font-14 font-weight-bold text-truncate"> USD {{     $pa['ms_monto']   }}</div>
                                </div>

                                      <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Tasa</div>
                                        <div class="font-14 font-weight-bold text-truncate">1 {{$pago->sender_fiat}}  = {{$pago->receiver_fiat}}  {{number_format($pago->exchange_rate)}} </div>
                                    </div>

                                 
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Recibe</div>
                                        <div class="font-14 font-weight-bold text-truncate"> 
                                            {{$pago->receiver_fiat}} {{number_format($pago->receiver_fiat_amount, 2)}} </div>
                                    </div>


                                </div>

                               
                                <div class="col-md-4 mb-4 mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Enviado a</div>
                                        <div class="font-14 font-weight-bold text-truncate">{{$pago->destination_account_json['name']}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Banco</div>
                                        <div class="font-14 font-weight-bold text-truncate">{{$pago->destination_account_json['bank_name']}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Número</div>
                                        <div class="font-14 font-weight-bold text-truncate">{{$pago->destination_account_json['account_number']}}</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4 mb-md-0">
                                    <div class="text-center body-bg-color rounded-lg p-3">
                                        <div class="text-primary font-14">Total de transferencia</div>
                                        <h3 class="text-primary font-weight-bold mb-0"> {{$pago->receiver_fiat}} {{number_format($pago->receiver_fiat_amount, 2)}} </h3>
                                    </div>
                                </div>
                            </div>

                            @else

                            <div class="row align-items-center mb-5">
                                <div class="col-md-4 mb-4 mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Monto a transferir</div>
                                        <div class="font-14  text-truncate"> {{$pago->sender_fiat}}
                                    {{number_format($pago->sender_fiat_amount, 2)}} </div>
                                    </div>

                                    @php
                                    $pa = json_decode($pago['stripe_data'],true) 
                                  @endphp
                                

                                  <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                    <div class="small text-truncate mr-2">Total cobrado</div>
                                    <div class="font-14 font-weight-bold text-truncate"> USD {{     $pa['ms_monto']   }}</div>
                                </div>

                                      <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Tasa</div>
                                        <div class="font-14 font-weight-bold text-truncate">1 {{$pago->sender_fiat}}  = {{$pago->receiver_fiat}}  {{number_format($pago->exchange_rate)}} </div>
                                    </div>

                                 
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Recibe</div>
                                        <div class="font-14 font-weight-bold text-truncate"> 
                                            {{$pago->receiver_fiat}} {{number_format($pago->receiver_fiat_amount, 2)}} </div>
                                    </div>


                                </div>

                               
                                <div class="col-md-4 mb-4 mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Enviado a</div>
                                        <div class="font-14 font-weight-bold text-truncate">{{$pago->destination_account_json['name']}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Banco</div>
                                        <div class="font-14 font-weight-bold text-truncate">{{$pago->destination_account_json['bank_name']}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center px-md-4 mb-1">
                                        <div class="small text-truncate mr-2">Número</div>
                                        <div class="font-14 font-weight-bold text-truncate">{{$pago->destination_account_json['account_number']}}</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4 mb-md-0">
                                    <div class="text-center body-bg-color rounded-lg p-3">
                                        <div class="text-primary font-14">Total de transferencia</div>
                                        <h3 class="text-primary font-weight-bold mb-0"> {{$pago->receiver_fiat}} {{number_format($pago->receiver_fiat_amount, 2)}} </h3>
                                    </div>
                                </div>
                            </div>
                                
                            @endif


                            <div class="text-center">
                                <div class="text-primary font-14">Código único de seguimiento</div>
                                <div class="copyToClipboard__container">
                                    <div class="copyToClipboard__btnContainer">
                                        <button type="button" class="btn btn-light copyToClipboard btn-clipboard--float" data-toggle="tooltip" data-placement="bottom" title="Copiar"><i class="fa fa-clipboard"></i></button>
                                    </div>
                                    <h3 class="d-inline mb-3"><input type="text" class="form-control custom-readonly text-center text-secondary font-weight-bold toCopy" readonly value="{{$pago->tracking_id}}"></h3>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ url('/transactions-history') }}" class="btn btn-secondary  btn-pill px-4 px-md-5 mb-2 mx-2">Ver mis transacciones</a>
                            <a href="{{ url('send-money') }}" class="btn btn-secondary btn-pill px-4 px-md-5 mb-2 mx-2">Realizar otra operación</a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>


           
            @endsection
            @section('js')

            <script>
                $(function() {
    var btn = $('.copyToClipboard');

    $(btn).on("click", function() {

        /* Get the text field */
        var toCopy = $(this).closest('.copyToClipboard__container').find('.toCopy');
        var toCopyValue = toCopy.val();

        /* Select the text field */
        toCopy.select();
        // toCopy.setSelectionRange(0, 99999); /*For mobile devices*/

        // /* Copy the text inside the text field */
        document.execCommand("copy");

        // chage bootstrap4 tooltip text
        $(this).attr("title", "Copiado: " + toCopyValue).tooltip("_fixTitle").tooltip("show").attr("title", "Copiar").tooltip("_fixTitle");
    })
})
            </script>
            
 
@endsection