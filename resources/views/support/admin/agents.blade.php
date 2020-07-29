@php
use App\SupportAgents;
    $total = SupportAgents::all()->count();
    $total_active = SupportAgents::where('is_active','=',1)->count();
@endphp
<a href="{{URL::to('/agents-admin')}}">
<div class="card rounded-lg shadow-none mb-3">
    <div class="card-header pt-lg-4">
        <div class="row">
            <div class="col-md-12">
                <div align="center">
                    <h6 class="card-title font-weight-bold mb-0">Agentes</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body d-flex justify-content-between border-bottom">
        <div align="center">
            <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Total</div>
            <h5 class="text-primary mb-0"> {{ $total }} </h5>
        </div>
        <div align="center">
            <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Activos</div>
            <h5 class="text-primary mb-0"> {{ $total_active }} </h5>
        </div>
    </div>
</a>