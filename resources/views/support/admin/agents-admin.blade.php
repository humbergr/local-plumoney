@extends('layouts.coinbank-layout')
@section('content')
<section id="agents" class="py-section-3">
    <div class="container">
        @include('support.admin.navigator')
        <div class="card">
            <div class="card-header pt-lg-4">
                <div class="row">
                    <div class="col-md-12">
                        <div align="center">
                            <h5 class="card-title font-weight-bold mb-0">Agentes</h5>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-borderless mb-0">
                <thead>
                    <tr>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Nombre</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Departamento</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Detalles</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Creación</h5>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agents as $agent)
                    <tr>
                        <th class="text-center">
                            <a target="_blank" href="{{URL::to('agent-details/'.$agent->id)}}">{{$agent->name}}</a>
                            </th>
                        <th class="text-center">
                            {{$agent->department}}
                        </th>
                        <td class="text-center">
                            @if ($agent->is_manager==1)
                                <span class="badge badge-info">Manager</span>
                            @endif
                            @if ($agent->is_active==1)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{$agent->created_at->format('D d M Y')}} por 
                            @if (Auth::user()->id==$agent->created_by)
                                Ti
                            @else
                                {{get_user_name_by_id($agent->created_by)}}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection