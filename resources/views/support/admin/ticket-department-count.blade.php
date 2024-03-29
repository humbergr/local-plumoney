@php
use App\Tickets;
use App\Departments;
    $tickets = Tickets::leftjoin('support_agents','support_agents.user_id','tickets.user_id')
        ->leftjoin('departments','departments.id','support_agents.dept_id');
    $total_tickets = $tickets->count();
    $total_open = $tickets->where('tickets.status','=',1)->count();
    $total_closed = $tickets->where('tickets.status','=',2)->count();
    $total_resolved = $tickets->where('tickets.status','=',3)->count();
    $total_deleted = $tickets->where('tickets.is_deleted','=',1)->count();
@endphp
<div class="card rounded-lg shadow-none mb-3">
    <a href="{{URL::to('/ticket-list')}}">
        <div class="card-header pt-lg-4">
            <div class="row">
                <div class="col-md-12">
                    <div align="center">
                        <h6 class="card-title font-weight-bold mb-0">Tickets</h6>
                        <div class="text-muted font-13">Soporte</div>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <div class="card-body d-flex justify-content-between border-bottom">
        <div align="center">
            <a href="{{URL::to('/ticket-list')}}">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Total</div>
                <h5 class="text-primary mb-0"> {{ $total_tickets }} </h5>
            </a>
        </div>
        <div align="center">
            <a href="{{URL::to('/ticket-list?type=1')}}">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Abiertos</div>
                <h5 class="text-primary mb-0"> {{ $total_open }} </h5>
            </a>
        </div>
        <div align="center">
            <a href="{{URL::to('/ticket-list?type=2')}}">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Cerrados</div>
                <h5 class="text-primary mb-0"> {{ $total_closed }} </h5>
            </a>
        </div>
        <div align="center">
            <a href="{{URL::to('/ticket-list?type=3')}}">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Resueltos</div>
                <h5 class="text-primary mb-0"> {{ $total_resolved }} </h5>
            </a>
        </div>
        <div align="center">
            <a href="{{URL::to('/ticket-list?type=4')}}">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Eliminados</div>
                <h5 class="text-primary mb-0"> {{ $total_deleted }} </h5>
            </a>
        </div>
        <div align="center">
            <a href="{{URL::to('/ticket-list?type=5')}}">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Pendientes</div>
                <h5 class="text-primary mb-0"> {{ $total_deleted }} </h5>
            </a>
        </div>
        <div align="center">
            <a href="{{URL::to('/ticket-list?type=6')}}">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Atendidos</div>
                <h5 class="text-primary mb-0"> {{ $total_deleted }} </h5>
            </a>
        </div>
    </div>
</div>