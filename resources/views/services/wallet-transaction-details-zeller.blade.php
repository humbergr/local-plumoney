@extends('layouts.mvp-layout-internal')

@section('css')

<link rel="stylesheet" href="{{ asset('css/main.css') }}">
<style>
.copyToClipboard__container .btn-clipboard--float {
   display: block;  
   margin-left: 60px;
}    
</style>
@endsection
@section('content')
<script>
   {{--   fbq('track', 'SubmitApplication'); --}}
</script>
<main class="dashboard__main my-5">
    <div class="container">

        <form action="" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$transaction->id}}">
            <section class="mb-5 mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-4 col-lg-5 col-xl-4">
                        <div class="position-relative d-none d-md-block">
                            <img src="{{ url('img') }}/landing/zelle-photo.png" alt="Go to Zelle website" class="img-fluid rounded-lg shadow-lg mb-4 mb-md-0">
                            <a href="https://www.zellepay.com/" target="_blank" class="btn btn-secondary btn-sm btn-pill px-4 position-absolute" style="bottom: 3rem; left: -1rem">Visitar sitio web</a>
                        </div>
                        <a href="https://www.zellepay.com/" target="_blank" class="text-reset d-inline-block d-md-none mb-4">
                            <i class="fa fa-external-link text-muted align-middle mr-2"></i>Visitar sitio web <img src="{{ url('img') }}/landing/zelle-logo.png" alt="Zelle logo" class="img-fluid" style="max-height: 22px">
                        </a>
                    </div>
                    <div class="col-md-8 col-lg-6 offset-lg-1 col-xl-6 offset-xl-0">
                        <h5 class="text-primary font-weight-bold mb-4"><img src="{{ url('img') }}/landing/search-icon-2.svg" class="mr-2" style="max-height: 50px">Estamos revisando tu operación </h5>
                        <h6 class="text-primary font-weight-bold mb-3">Antes de seguir con la operación lee la siguiente información:</h6>
                        <p class="mb-3">No realizar un envío utilizando la aplicación de Zelle si no eres el propietario de la cuenta bancaria, afiliada a la aplicación, American Kryptos Bank, no acepta el envío de Terceros para Recargar su billetera digital.</p>
                        <p class="mb-4">Si quieres recibir dinero mediante Zelle, Debes pedirle a la persona propietaria de la cuenta que posee la aplicación Zelle afiliada, que se registre primero en American Kryptos Bank, que recargue su billetera digital y luego podrá hacerte un envío a tu billetera digital mediante el sistema <img src="{{ url('img') }}/landing/mia-logo.svg" height="16px">.</p>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="terminos" class="custom-control-input" id="accept-terms">
                            <label class="custom-control-label text-primary h5" for="accept-terms" style="cursor: pointer;">Debes aceptar que entendiste esta información</label>
                            @if ($errors->has('terminos'))
                            <div class="text-danger"  >
                                <strong>{{ $errors->first('terminos') }}</strong>
                            </div>
                            @endif


                        </div>
                    </div>
                </div>
            </section>

            <!-- remove "section-disabled" class when terms checkbox is checked -->

            <div class="alert alert-danger text-center" style="font-size: 20px" role="alert">
                ¡Atención!
                <br>
                Hemos cambiado nuestro correo electrónico de Zelle. Por favor, actualiza nuestros datos y verifica
                el correo electrónico.
                <br>
                <br>
                Nuevo Correo: <strong>zelle.ath01@gmail.com</strong>
            </div>

            <section>
                <div id="upload-files-section" class="section-disabled">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-xl-3 mb-3">
                            <div class="card card-body body-bg-color rounded-lg text-primary py-4 h-100 position-relative">
                                <h6 class="font-weight-bold text-center mb-4 mb-md-5">Detalles de la transacción</h6>


                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="small text-truncate mr-2">Monto a transferir</div>
                                    <div class="font-14 font-weight-bold text-truncate">    {{$transaction->sender_fiat}}
                                        {{number_format($transaction->sender_fiat_amount, 2)}} {{$transaction->sender_fiat}}</div>
                                    </div>


                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="small text-truncate mr-2">Tasa</div>
                                        <div class="font-14 font-weight-bold text-truncate">1 USD = {{$transaction->exchange_rate}}  </div>
                                    </div>


                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="small text-truncate mr-2">Comisión</div>
                                        <div class="font-14 font-weight-bold text-truncate">0 USD</div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="small text-truncate mr-2">Recibe</div>
                                        <div class="font-14 font-weight-bold text-truncate">{{$transaction->sender_fiat}}
                                            {{number_format($transaction->receiver_fiat_amount, 2)}} {{$transaction->receiver_fiat}}</div> 
                                        </div>
                                        <div class="text-center mt-4">
                                            <div class="text-primary font-14">Total de transferencia</div>
                                            <h3 class="text-primary font-weight-bold mb-0">{{$transaction->sender_fiat}}
                                                {{number_format($transaction->sender_fiat_amount, 2)}} {{$transaction->sender_fiat}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xl-3 mb-3">
                                        <div class="card card-body rounded-lg py-4 text-center shadow-none h-100 position-relative">
                                            <h6 class="card-title text-primary font-weight-bold mb-4 mb-md-5">Incluye estos datos de envío en tu cuenta Zelle</h6>
                                            <div class="text-center">




                                                <div class="d-flex justify-content-center align-items-center mt-3">

                                                    <div class="text-primary font-14 text-right">Nombre:</div>


                                                    <div class="copyToClipboard__container">
                                                        <div class="copyToClipboard__btnContainer">
                                                            <button type="button" class="btn btn-light copyToClipboard btn-clipboard--float" data-toggle="tooltip" data-placement="bottom" title="Copiar"><i class="fa fa-clipboard"></i></button>
                                                        </div>
                                                        <h3 class="d-inline mb-3"><input type="text" class="form-control custom-readonly text-center text-primary font-weight-bold toCopy" style="font-size: 15px !important" readonly value="{{$genericPaymentObject->metadata['name'] }}"></h3>
                                                    </div>

                                                </div>

                                               <div class="d-flex justify-content-center align-items-center mt-3">
                                                    
                                                    <div class="text-primary font-14 text-right">Apellido:</div>
                                                <div class="copyToClipboard__container">
                                                    <div class="copyToClipboard__btnContainer">
                                                        <button type="button" class="btn btn-light copyToClipboard btn-clipboard--float" data-toggle="tooltip" data-placement="bottom" title="Copiar"><i class="fa fa-clipboard"></i></button>
                                                    </div>
                                                    <h3 class="d-inline mb-3"><input type="text" class="form-control custom-readonly text-center text-primary font-weight-bold toCopy" style="font-size: 15px !important" readonly value="{{$genericPaymentObject->metadata['lastname'] }}"></h3>
                                                </div>
                                                </div>


                                                <div class="d-flex justify-content-center align-items-center mt-3">
                                                    
                                                    <div class="text-primary font-14 text-right">Email:</div>
                                                <div class="copyToClipboard__container">
                                                    <div class="copyToClipboard__btnContainer">
                                                        <button type="button" class="btn btn-light copyToClipboard btn-clipboard--float" data-toggle="tooltip" data-placement="bottom" title="Copiar"><i class="fa fa-clipboard"></i></button>
                                                    </div>
                                                    <h3 class="d-inline mb-3"><input type="text" class="form-control custom-readonly text-center text-primary font-weight-bold toCopy" style="font-size: 15px !important" readonly value="zelle.ath01@gmail.com"></h3>
                                                </div>
                                                </div>




                                                <div class="text-primary font-14 mt-2">Código único de seguimiento</div>
                                                <div class="copyToClipboard__container">


                                                    <div class="copyToClipboard__btnContainer">
                                                        <button type="button" class="btn btn-light copyToClipboard btn-clipboard--float" data-toggle="tooltip" data-placement="bottom" title="Copiar"><i class="fa fa-clipboard"></i></button>
                                                    </div>
                                                    <h3 class="d-inline mb-3"><input type="text" class="form-control custom-readonly text-center text-secondary font-weight-bold toCopy" readonly value="{{$transaction->tracking_id}}"></h3>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-xl-3 mb-3">
                                        <div class="card card-body rounded-lg py-4 text-center shadow-none h-100 position-relative">
                                            <h6 class="card-title text-primary font-weight-bold mb-4 mb-md-5">Carga tu comprobante y reporta tu pago</h6>
                                            <div class="text-center">
                                                <div class="mb-3">
                                                    <img id="capture-temp" src="{{ url('img') }}/landing/company_reg.svg" style="max-height: 100px" class="img-fluid">
                                                </div>



                                                <input id="capture" type="file" style="display: none" accept="image/*,application/pdf"   name="comprobante_de_pago">

                                                @if ($errors->has('comprobante_de_pago'))
                                                <div class="text-danger"  >
                                                    <strong>{{ $errors->first('comprobante_de_pago') }}</strong>
                                                </div>
                                                @endif

                                                <a class="btn btn-light btn-sm" onclick="cargar()"  >Cargar imágen o PDF</a>
                                            </div>


                                                      <div class="form-group{{ $errors->has('numero_de_confirmacion') ? ' has-danger' : '' }}">
                                                                                <h6 class="card-title text-primary font-weight-bold  mt-5">{{ __('Número de confirmación de su recibo zelle') }}</h6>
                                                                                <input type="text" name="numero_de_confirmacion" id="input-numero_de_confirmacion" class="form-control  form-control-alternative{{ $errors->has('numero_de_confirmacion') ? ' is-invalid' : '' }}" placeholder="{{ __('Numero de confirmacion') }}" value="{{ old('numero_de_confirmacion') }}" required   >
                                            
                                                                                @if ($errors->has('numero_de_confirmacion'))
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $errors->first('numero_de_confirmacion') }}</strong>
                                                                                </span>
                                                                                @endif
                                                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-secondary btn-pill px-5">Reportar pago</button>
                                @php
                                $endDate = new Carbon\Carbon(
                                    $transaction->created_at
                                );
                                $endDate = $endDate
                                ->setTimezone('EDT')
                                ->addMinutes(30)
                                ->format('Y-m-d H:i:s');
                                @endphp
                                <div class="text-danger">
                                    Recomendamos reportar su pago antes de:
                                    {{$endDate}} (EST)
                                    <br>
                                </div>

                                @if ($errors->has('comprobante_de_pago'))
                                <div class="text-danger"  >
                                    <strong>{{ $errors->first('comprobante_de_pago') }}</strong>
                                </div>
                                @endif
                                @if ($errors->has('terminos'))
                                <div class="text-danger"  >
                                    <strong>{{ $errors->first('terminos') }}</strong>
                                </div>
                                @endif
                            </div>
                        </section>

                    </form>
                </div>
            </main>


            <div class="modal fade __print_receipt_modal" id="__print_receipt_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 950px !important;">
                    <div class="modal-content">
                        <div id="__receipt_image_container" style=" border: 1px #333 dotted; text-align: center; margin: 10px; padding: 10px"></div>
                        <div class="text-center mb-2">
                            Puedes descargar la imagen superior.
                        </div>
                    </div>
                </div>
            </div>
            @endsection
            @section('js')
            <script>

                function cargar() {


                    $('#capture').trigger('click'); 

    // body...
}

function changePreview(input, errorClass, imageClass) {

    if (input && input[0]) {

        let reader = new FileReader();

        if (input[0].size > 4000000) {

           $("#capture").val('');

           $('#capture-temp').attr('src', '{{ url('img/landing/company_reg.svg') }}');

           alert('Doc. Muy pesado. Intente con otro archivo')



           return false;

       }



       reader.onload = function (e) {
        console.log(e.target.result)

        $('#capture-temp').attr('src', '{{ url('img/landing/check.svg') }}');

    };


    reader.readAsDataURL(input[0]);



}

}



$("#capture").change(function () {

    changePreview(this.files, '#__capture_img_error', '#__capture_img');



});






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


$(function() {
    $('#accept-terms').on('change', function() {

        $('#upload-files-section').toggleClass('section-disabled', !this.checked);
    })
})
</script>
@endsection