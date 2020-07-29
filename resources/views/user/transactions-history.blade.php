@extends('layouts.mvp-layout-internal')

@section('content')
<main>
    <div class="container mt-md-n5">
        <div class="row">
            <div class="col-12 mx-auto px-0 px-md-3">
                <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                    <div class="card-body py-4 py-lg-4">
                        <section class="mt-5 px-md-3 px-xl-4">
                            <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                <h6 class="text-primary font-weight-bold text-truncate mb-md-0">Historial de tus
                                    envíos</h6>
                                <form action="" method="get" class="form-inline flex-md-nowrap ml-md-3">
                                    <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="dest-name" value="{{request()['dest-name']}}" placeholder="Buscar por nombre">
                                    </div>
                                    <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="dest-lastname" value="{{request()['dest-lastname']}}" placeholder="Buscar por Apellido">
                                    </div>
                                    <div class="input-group mb-3 mb-md-0">
                                        <input type="date" id="creation-date-filter" class="form-control" aria-label="Creation date filter" name="transaction-date" value="{{request()['transaction-date']}}" aria-describedby="creation-date-filter">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white text-muted" id="creation-date-filter"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3 mb-md-0">
                                        <input type="submit" class="ml-3 btn btn-primary" value="Filtrar">
                                    </div>
                                    @if (request()['dest-name'] || request()['dest-lastname'] ||
                                    request()['transaction-date'])
                                    <div class="input-group mb-3 mb-md-0">
                                        <a href="/transactions-history" class="ml-3 btn btn-primary">Limpiar</a>
                                    </div>
                                    @endif
                                </form>
                            </div>
                            <div id="history-table" class="mb-5">
                                @foreach($userExchangeTransactions as $transaction)
                                <div class="row border-bottom p-3">
                                    <div class="col-6 col-md-1 px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">En la fecha</small>
                                        <div class="font-14">
                                            {{$transaction->getHumanDate($transaction->created_at)}}
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-2 px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Enviaste a</small>
                                        <div class="font-weight-bold font-14">
                                            @if (isset($transaction->destinationAccount))
                                            {{$transaction->destinationAccount->name}}
                                            {{$transaction->destinationAccount->lastname}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-2 px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">En el país</small>
                                        <div class="font-14">
                                            @if (isset($transaction->destinationAccount))
                                            {{$transaction->destinationAccount->getCountry()[1]}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-2 px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Método</small>
                                        <div class="font-14">{{$transaction->paymentMethod()}}</div>
                                    </div>
                                    <div class="col-6 col-md-2 px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Enviados</small>
                                        <div class="text-secondary font-weight-bold h5 mb-0">
                                            <small>{{$transaction->sender_fiat}}</small>
                                            {{number_format($transaction->sender_fiat_amount, 2)}}
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-1 px-1 mb-3 mb-md-0">
                                        <small class="d-block text-muted">Estado</small>
                                        @if ($transaction->status === 1)
                                        <div class="font-14">
                                            Completado
                                        </div>
                                        @endif
                                        @if ($transaction->status === 0 &&
                                        $transaction->payment_way !== 'Stripe')
                                        <div class="font-14">
                                            En Revisión
                                        </div>
                                        @endif
                                        @if ($transaction->status === 0 &&
                                        ($transaction->payment_way === 'Stripe' || $transaction->payment_way ===
                                        'QuickBook'))
                                        <div class="font-14">
                                            Pagada
                                        </div>
                                        @endif
                                        @if ($transaction->status === 2)
                                        <div class="font-14">
                                            Rechazada
                                        </div>
                                        @endif
                                        @if ($transaction->status === 3)
                                        <div class="font-14">
                                            Fallida
                                        </div>
                                        @endif
                                        @if ($transaction->status === 4)
                                        <div class="font-14">
                                            En proceso
                                        </div>
                                        @endif
                                        @if ($transaction->status === 5)
                                        <div class="font-14">
                                            Reembolsada
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-6 col-md-1 px-1 text-center my-auto">
                                        <a href="/tools/gen-t-pdf?id={{$transaction->id}}" target="_blank" class="btn-transparent">
                                            <img src="/img/landing/pdfDown.svg" class="img-fluid">
                                        </a>
                                        @if ($transaction->is_payed === null &&
                                        $transaction->payment_way !== 'cash_deposit')
                                        <a href="/transaction-success/{{$transaction->id}}" class="btn-transparent">
                                            <img src="/img/landing/btnReportPayment.svg" class="img-fluid">
                                        </a>
                                        @endif
                                    </div>
                                    <div class="col-6 col-md-1 px-1 text-center my-auto">
                                        <a href="#" data-toggle="collapse" data-target="#t-{{$transaction->id}}" class="btn-transparent text-primary font-weight-bold">
                                            @php
                                                $msgs = DB::table('status_notes')
                                                    ->where('transaction_id', '=', $transaction->id)
                                                    ->count();

                                                $to_answer = DB::table('status_notes')
                                                    ->where('transaction_id', '=', $transaction->id)
                                                    ->where('status', '=', 4)
                                                    ->where('is_reply', '=', 0)
                                                    ->count();                                                
                                            @endphp
                                            @if($to_answer > 0)
                                                <span class="spinner-grow text-danger"></span>
                                                <br/>
                                                Responder
                                            @elseif($msgs == 0)
                                                <h6 data-toggle="tooltip" title="" data-original-title="No tiene mensajes aun" class="btn-transparent text-primary font-weight-bold">Mensajes</h6>
                                            @else
                                                Mensajes
                                            @endif
                                        </a>
                                    </div>
                                    <div id="t-{{$transaction->id}}" class="collapse col-12 col-md-12 px-1 text-center my-auto">
                                        @foreach($notes as $note)
                                        @if($note->transaction_id === $transaction->id)
                                            <hr>
                                            <div class="container p-4 text-justify" style="background: #fafafa;">
                                                <span class="text-secondary font-weight-bold"> Estado de la transacción:
                                                    @if($note->status == 0)
                                                        Open
                                                    @elseif($note->status == 1)
                                                        Approved
                                                    @elseif($note->status == 2)
                                                        Rejected
                                                    @elseif($note->status == 5)
                                                        Refund
                                                    @elseif($note->status == 4)
                                                        In Process
                                                    @elseif($note->status == 3)
                                                        Failed
                                                    @endif
                                                </span>
                                                <h6> <span class="font-weight-bold">Motivo:</span> {{$note->subject->subject}}</h6>
                                                <h6>
                                                    {{$note->msg}}
                                                </h6>
                                                <span class="text-muted">{{ $note->created_at->format('d/m/Y g:i A') }}</span>
                                                <hr>
                                                @if($note->status == 4)
                                                @if($note->reply == null)
                                                <a href="#" data-toggle="collapse" data-target="#responder-{{$note->id}}" class="btn-transparent text-primary font-weight-bold">
                                                    Responder Aquí
                                                </a>
                                                <div id="responder-{{$note->id}}" class="collapse col-12 col-md-12 px-1 text-center my-auto">
                                                    <form action="{{URL::to('reply-note/'.$note->id)}}" method="post" enctype="multipart/form-data">
                                                        {{ csrf_field() }}
                                                        <div class="row">
                                                            <div class="form-group col-sm-10">
                                                                <input type="text" name="reply" class="form-control" placeholder="Agregue una respuesta" required />
                                                            </div>
                                                            <div class="form-group col-sm-10">
                                                                <input type="file" class="form-control" id="miarchivo[]" name="reply_file[]" multiple placeholder="Agregue una respuesta" />
                                                            </div>
                                                            <button type="submit" class="col-sm-2 btn btn-light">Enviar</button>
                                                        </div>

                                                    </form>
                                                </div>
                                                @else
                                                <h6><span class="text-primary">{{$transaction->merchant->name}}</span></h6>
                                                <h6>{{$note->reply}}</h6>
                                                <span class="text-muted">{{ $note->updated_at->format('d/m/Y g:i A') }}</span>
                                                @php
                                                $fileArray = explode('.', $note->reply_file);
                                                $endType = end($fileArray);
                                                @endphp

                                                @if ($note->reply_file !== null)
                                                @php
                                                    $files = explode(" , ", $note->reply_file);
                                                    foreach ($files as $key) {
                                                        $fileArray = explode('.', $note->reply_file);
                                                        $endType = end($fileArray);
                                                    }
                                                    $cont = 1;
                                                @endphp
                                                <label class="text-primary">(Click para ampliar o
                                                    abrir)</label>
                                                <br>

                                                <h6><span class="text-primary font-weight-bold">Archivos: {{ count($files)-1 }}</span></h6>
                                                @foreach ($files as $item)
                                                    @php
                                                        $fileArray = explode('.', $item);
                                                        $endType = end($fileArray);
                                                    @endphp
                                                    @if($item != '')
                                                        @if ($endType === 'jpeg' || $endType === 'gif' || $endType === 'png' || $endType === 'jpg')
                                                            <a href="{{$item}}" title="Ver archivo" target="_blank">
                                                                <img src="{{$item}}" class="img-fluid" style="height: 200px !important" alt="Imagen">
                                                            </a>
                                                        @else
                                                            <a href="{{$item}}" title="Ver archivo {{$cont}}" target="_blank">
                                                                Ver Archivo {{ $cont }}
                                                            </a>
                                                        @endif
                                                    @endif
                                                    @php 
                                                        $cont++
                                                    @endphp
                                                @endforeach
                                                @endif
                                                @endif
                                                @endif

                                            </div>
                                        @endif
                                        @endforeach
                                    </div>

                                </div>

                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    {{$userExchangeTransactions->links()}}
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="/send-money" class="btn btn-secondary btn-pill">
                                    <img class="d-none d-md-inline-block mr-2" src="/img/landing/enviar-dinero.svg" height="32">Realizar nuevo envío
                                </a>
                            </div>
                        </section>

                    </div>
                </div>

                {{--
                <div class="d-none d-md-block text-primary small px-3 px-md-0 wow fadeInUp">
                    <h6 class="text-center">Información importante</h6>
                    <p>1 Date available will be displayed on receipt for international transfers over $15. Service
                        and funds may be delayed or unavailable depending on certain factors including the Service
                        selected, the selection of delayed delivery options, special terms applicable to each
                        Service, amount sent, destination country, currency availability, regulatory issues,
                        consumer protection issues, identification requirements, delivery restrictions, agent
                        location hours, and differences in time zones (collectively, “Restrictions”). Additional
                        restrictions may apply; see our terms and conditions for details.</p>
                </div>
                --}}
            </div>
        </div>
    </div>
</main>

<div class="modal fade __print_receipt_modal" id="__print_receipt_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 950px !important;">
        <div class="modal-content">
            <div id="__receipt_image_container" style=" border: 1px #333 dotted; text-align: center; margin: 10px; padding: 10px"></div>
            <div class="text-center mb-2">
                Puedes descargar la imagen superior.
            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="new-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary font-weight-bold">Nuevo Asunto</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="container align-content-center">
                    <h5>Agregar un nuevo asunto</h5>
                    <div class="container py-3">
                        <form action="{{URL::to('new-subject')}}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input class="form-control" type="text" name="subject" placeholder="Nuevo Asunto" />
                            </div>
                            <button type="submit" class="btn btn-success rounded-0">Guardar</button>
                            <button class="btn btn-light rounded-0" data-dismiss="modal">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection