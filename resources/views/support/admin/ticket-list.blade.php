@extends('layouts.coinbank-layout')
@section('content')
<section id="tickets" class="py-section-3">
    <div class="container" style="min-width: 80%">
        @include('support.admin.navigator')
        @include('support.admin.ticket-count')
        <div class="card">
            <table class="table table-striped table-borderless mb-0">
                <thead>
                    <tr>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Ticket #</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Usuario</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Departamento</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Detalles</h5>
                        </th>
                        <th class="text-center">
                            <h5 class="text-muted font-weight-bold mb-0">Fecha</h5>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                    <tr>
                        <th class="text-center">                            
                            <a target="_blank" href="{{URL::to('ticket-details/'.$ticket->id)}}">{{$ticket->ticket_number}}</a>
                            </th>
                        <th class="text-center">
                            <a href="{{URL::to('user-profile/'.$ticket->user_id)}}" target="_blank">{{$ticket->name}}</a>
                        </th>
                        <td class="text-center">{{$ticket->department}}</td>
                        <td class="text-center">                        
                        @if ($ticket->status_id==1)
                            <span class="badge badge-info">
                        @elseif($ticket->status_id==2)
                            <span class="badge badge-primary">
                        @elseif($ticket->status_id==3)
                            <span class="badge badge-success">
                        @endif
                            {{$ticket->status}}</span>
                            <span class="badge" style="background-color: {{$ticket->color}}">{{$ticket->priority}}</span>
                            @if ($ticket->is_answered==0)
                            <span class="badge badge-secondary">Pendiente</span>
                            @endif
                            @if($ticket->is_deleted==1)
                            <span class="badge badge-danger">Eliminado</span>
                            @endif
                        </td>
                        <td class="text-center">{{$ticket->created_at->format('D d M Y')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection