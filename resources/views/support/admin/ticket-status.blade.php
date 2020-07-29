@php
use App\TicketStatus;
    $total = TicketStatus::all()->count();    
@endphp
<a href="{{URL::to('/ticket-status')}}">
    <div class="card rounded-lg shadow-none mb-3">
        <div class="card-header pt-lg-4">
            <div class="row">
                <div class="col-md-12">
                    <div align="center">
                        <h6 class="card-title font-weight-bold mb-0">Status</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border-bottom">
            <div align="center">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Total</div>
                <h5 class="text-primary mb-0"> {{ $total }} </h5>
            </div>
        </div>
    </div>
</a>