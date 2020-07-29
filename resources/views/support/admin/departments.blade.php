@php
use App\Departments;
    $total_departments = Departments::all()->count();
    $total_active = Departments::where('is_active','=',1)->count();
    $total_inactive = Departments::where('is_active','=',0)->count();
    $total_public = Departments::where('is_public','=',1)->count();
    $total_intern = Departments::where('is_public','=',0)->count();
@endphp
<a href="{{URL::to('/departments')}}">
    <div class="card rounded-lg shadow-none mb-3">
        <div class="card-header pt-lg-4">
            <div class="row">
                <div class="col-md-12">
                    <div align="center">
                        <h6 class="card-title font-weight-bold mb-0">Departamentos</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body d-flex justify-content-between border-bottom">
            <div align="center">
                <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Total</div>
                <h5 class="text-primary mb-0"> {{ $total_departments }} </h5>
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
        </div>
    </div>
</a>