@extends('layouts.coinbank-layout')

@section('content')

<section id="transactions" class="py-section-3">
    <div class="container">
        <div class="card rounded-lg shadow-none mb-3">
            <div class="card-header pt-lg-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="card-title font-weight-bold mb-0">Usuarios Registrados</h6>
                        <div class="text-muted font-13">Resumen de todos los usuadios registrados.</div>
                    </div>
                </div>
            </div>
            <div class="card-body border-bottom">
                <h3 class="text-primary mb-0">{{ $user_count }}</h3>
                <div class="text-muted text-uppercase font-14 mt-n1">Usuarios Registrados</div>
                <div class="row">
                </div>
            </div>

            <div class="card-body d-flex justify-content-between border-bottom">
                
                <div>
                    <h5 class="text-primary mb-0"> {{ $whitout_profile }} </h5>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles Incompletos</div>
                </div>

                <div>
                    <h5 class="text-primary mb-0"> {{ $waiting_profile }} </h5>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles por Verficar</div>
                </div>

                <div>
                    <h5 class="text-primary mb-0"> {{ $approve_profile }} </h5>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles Aprobados</div>
                </div>
                
                <div>
                    <h5 class="text-primary mb-0"> {{ $reject_profile }} </h5>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles Rechazados</div>
                </div>

                <div>
                    <h5 class="text-primary mb-0"> {{ $block_profile }} </h5>
                    <div class="text-muted text-uppercase lh-125 font-14 mt-n1">Perfiles bloqueados</div>
                </div>

            </div>


            <div class="card-body d-flex justify-content-between border-bottom">
                
            </div>
        </div>


        <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
            <h6 class="text-primary font-weight-bold text-truncate mb-md-0">Lista de Usuarios</h6>
            <form action="" method="get" class="form-inline flex-md-nowrap ml-md-3">
                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                    </div>
                    {{-- <input type="text" class="form-control" name="user-name" value="{{request()['user-name']}}" placeholder="Buscar por nombre"> --}}
                    <select name="status" id="status" class="form-control">
                        <option {{ !(request()['status']) ? 'selected':'' }} disabled>Seleccione un estado</option>
                        <option {{ request()['status'] == '0' ? 'selected':'' }} value="0">Sin Completar</option>
                        <option {{ request()['status'] == '1' ? 'selected':'' }} value="1">Perfiles En Espera</option>
                        <option {{ request()['status'] == '2' ? 'selected':'' }} value="2">Aprobados</option>
                        <option {{ request()['status'] == '3' ? 'selected':'' }} value="3">Rechazados</option>
                        <option {{ request()['status'] == '4' ? 'selected':'' }} value="4">En Verificación TIER</option>
                        <option {{ request()['status'] == 'block' ? 'selected':'' }} value="block">Bloqueados</option>
                    </select>
                </div>

                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="user-name" value="{{request()['user-name']}}" placeholder="Buscar por nombre">
                </div>
                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="user-lastname" value="{{request()['user-lastname']}}" placeholder="Buscar por Apellido">
                </div>
                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="user-email" value="{{request()['user-email']}}" placeholder="Buscar por Email">
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text bg-white text-muted">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                        <input type="text"
                               name="date-range"
                               id="creation-date-filteer"
                               class="form-control"
                               aria-label="Creation date filter"
                               aria-describedby="creation-date-filter"
                               value="{{request()['date-range']}}">
                    </div>
                </div>
                <div class="input-group mb-3 mb-md-0">
                    <input type="submit" class="ml-3 btn btn-primary" value="Filtrar">
                </div>
                
                @if (request()['user-name'] || request()['user-lastname'] || request()['user-email'] || request()['status']|| request()['date-range'] )
                <div class="input-group mb-3 mb-md-0">
                    <a href="/user-profiles" class="ml-3 btn btn-primary">Limpiar</a>
                </div>
                @endif
            </form>
        </div>
        <div class="input-group mt-3 mb-md-0">
            <a href="/download-csv/{{ isset($_GET['status']) ? $_GET['status'] : 'all' }}" class="ml-3 btn btn-primary">Descargar CSV</a>
        </div>
        <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap p-3">
            
        </div>
        <div class="card">
            <table class="table table-striped table-borderless mb-0">
                <thead>
                    <tr>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Name</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Email</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Profile</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Client since</h5>
                        </th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <th class="text-center">{{$user->name}}</th>
                        <td class="text-center">{{$user->email}}</td>
                        <td class="text-center">{{$user->userProfile()}}</td>
                        <td class="text-center">{{$user->created_at->format('d M Y')}}</td>
                        <td class="text-center"><a target="_blank" href="{{URL::to('user-profile/'.$user->id)}}">View Profile</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{-- {{ $users->appends(Request::all())->links() }} --}}

            {{ $users->appends(request()->all())->links() }}
        </div>
    </div>
</section>

@endsection