@extends('support.layout.support-layout')

@section('content')
    {{-- <h1>@lang('support.tickets')</h1> --}}
<div class="container">
    <div class="row flex-column-reverse flex-md-row">
        <div class="col-md-12">
            <div class="card shadow-none rounded-lg mb-4">
                <div class="card-header pt-md-4 pb-1">
                    <div class="row align-items-center">
                        <div class="col-8">
                            {{-- <h3 class="card-title text-primary font-weight-bold mb-0">Tickets</h3> --}}
                        </div>
                        {{-- @if ($data != 'no-data')
                        <div class="col-4">
                            <div class="text-right">
                                <a href="{{URL::to('/my-tickets')}}" class="font-14 ws-nowrap lh-125">Ver todos <i
                                    class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endif --}}
                    </div>
                    <div class="card-body">
                        @if(!isset($tickets))
                        <div class="py-4 py-lg-5">
                            <div class="row">
                                <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                    <img src="img/landing/empty-transactions.svg" alt="Transactions Empty"
                                    class="img-fluid mb-4" style="max-height: 100px">
                                    <h5 class="text-primary">Actualmente tu historial de soporte esta vacío</h5>
                                    <h6 class="text-muted">Todas los tickets se mostrarán aquí
                                </div>
                            </div>
                        </div>
                        @else
                        <ul class="walletHistory list-unstyled">
                            @foreach($tickets as $ticket)
                            <li class="walletHistory__item mb-2">
                                <a href="{{URL::to('/ticket/'.$ticket->ticket_number)}}">
                                    <div class="walletHistory__item__body">
                                        <div class="media">
                                            <div class="media-body">
                                                <div class="d-flex justify-content-between">
                                                <h5 class="walletHistory__item__amount">{{$ticket->title}}#{{$ticket->ticket_number}}</h5>
                                                    <div class="walletHistory__item__date">
                                                        {{date('m/d/Y H:i',strtotime($ticket->created_at))}}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-wrap justify-content-between">
                                                    <div class="">
                                                        {{$ticket->department}}
                                                    </div>
                                                    <div>
                                                        {{$ticket->status}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
