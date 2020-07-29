@php
use App\TicketPriority;
    $total = TicketPriority::all()->count();
    $total_active = TicketPriority::where('status','=',1)->count();
    $total_inactive = TicketPriority::where('status','=',0)->count();
    $total_public = TicketPriority::where('is_public','=',1)->count();
    $total_intern = TicketPriority::where('is_public','=',0)->count();
    $default = TicketPriority::where('is_default','=',1)->first()->priority_desc;
@endphp
<a href="{{URL::to('/ticket-status')}}">
    <div class="card rounded-lg shadow-none mb-3">
        <div class="card-header pt-lg-4">
            <div class="row">
                <div class="col-md-12">
                    <div align="center">
                        <h6 class="card-title font-weight-bold mb-0">Prioridades</h6>
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
            <div align="center">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Inactivos</div>
                <h5 class="text-primary mb-0"> {{ $total_inactive }} </h5>
            </div>
            <div align="center">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Públicos</div>
                <h5 class="text-primary mb-0"> {{ $total_public }} </h5>
            </div>
            <div align="center">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Internos</div>
                <h5 class="text-primary mb-0"> {{ $total_intern }} </h5>
            </div>
            <div align="center">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Predeterminado</div>
                <h5 class="text-primary mb-0"> {{ $default }} </h5>
            </div>
        </div>
    </div>
</a>