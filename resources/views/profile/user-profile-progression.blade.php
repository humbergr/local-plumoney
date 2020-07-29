<div class="px-3">
    <div class="alert alert-warning rounded-lg p-3 mb-2 mb-md-3">
        <div class="media font-14 text-primary mb-3">
            <i class="fa fa-bell mr-2 mt-1"></i>
            <div class="media-body">
                Completa tu perfil para poder enviar más de <strong>$100 USD</strong> en una transacción
            </div>
        </div>
         <div class="progress" style="height: 15px">
            <div class="progress-bar"
                 role="progressbar"
                 style="width: {{$personProfile->progression()}}%;"
                 aria-valuenow="{{$personProfile->progression()}}"
                 aria-valuemin="0"
                 aria-valuemax="100">
                  {{$personProfile->progression()}}% completado
            </div>
        </div>
    </div>
    <ul class="list-unstyled d-none d-md-block font-13">
        <li class="profileGoals @php if($personProfile->selfie) echo '--done'; @endphp">Sube tu fotografía</li>
        <li class="profileGoals @php if($personProfile->selfie || $personProfile->street) echo '--done'; @endphp">Completar más de la mitad del perfil</li>
        <li class="profileGoals @php if($personProfile->mobile) echo '--done'; @endphp">Agrega número de teléfono</li>
        <li class="profileGoals @php if($personProfile->id_confirmation) echo '--done'; @endphp">Sube un documento de identidad</li>
    </ul>

    <!-- goals mobile -->
    <div class="d-md-none mb-4">
        <button class="btn btn-light rounded-0 btn-sm btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Ver objetivos <i class="fa fa-angle-down"></i>
        </button>
        <div class="collapse" id="collapseExample">
            <ul class="list-unstyled font-13 mt-3">
              <li class="profileGoals @php if($personProfile->selfie) echo '--done'; @endphp">Sube tu fotografía</li>
              <li class="profileGoals @php if($personProfile->selfie || $personProfile->street) echo '--done'; @endphp">Completar más de la mitad del perfil</li>
              <li class="profileGoals @php if($personProfile->mobile) echo '--done'; @endphp">Agrega número de teléfono</li>
              <li class="profileGoals @php if($personProfile->id_confirmation) echo '--done'; @endphp">Sube un documento de identidad</li>
            </ul>
        </div>
    </div>

    <hr class="my-4 d-md-none">
</div>
