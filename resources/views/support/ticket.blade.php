@extends('support.layout.support-layout')

@section('content')
<div class="container">
    <div class="row flex-column-reverse flex-md-row">
        <div class="col-md-12">
            <div class="card shadow-none rounded-lg mb-4">
                <div class="card-header pt-md-4 pb-1">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="card-title text-primary font-weight-bold mb-0">#{{$number}}</h4><h4>{{$threads[0]->title}}</h4>
                        </div>
                        <div class="col-1">
                            <span class="badge badge-info">
                            @if ($status==1)
                                @lang('support.status1')
                            @elseif($status==2)
                                @lang('support.status2')
                            @elseif($status==3)
                                @lang('support.status3')
                            @endif</span>
                        </div>
                        @if ($status==1)
                        <div class="col-2">
                            <div class="text-right">
                                <a href="#" id="replyButton" class="font-14 ws-nowrap lh-125"><span id="replyText">@lang('support.reply')</span><span id="replyCancel">@lang('support.cancel')</span></a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if ($status==1)
                    <div id="reply" class="container">
                        <form action="{{URL::to('/reply-ticket')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$ticket_id}}">
                        <input type="hidden" name="title" value="{{$threads[0]->title}}">
                        <input type="hidden" name="ip" value="{{Request::ip()}}">
                        <input type="hidden" name="number" value="{{$number}}">
                            <div class="r-border">
                                <div align="center">
                                    <h4 class="card-title text-primary font-weight-bold mb-0">@lang('support.reply')</h4>
                                </div>
                                <br>
                                <div class="form-row">
                                    <div class="col-md-3 text-md-right pr-md-3">
                                        <label for="message">@lang('support.ticketMsg')</label>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control" style="min-height: 100px" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a href="#" id="attachmentsButton" class="font-14 ws-nowrap lh-125">@lang('support.attachmentsButton')</a>
                                </div>
                                <div id="attachments">
                                    <div class="form-row">
                                        <div class="col-md-3 text-md-right pr-md-3">
                                            <label for="file">@lang('support.uploadFile')</label>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <input type="file" name="file[]" multiple="multiple" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary  btn-pill px-4">@lang('support.sendButton')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                    <div class="card-body">
                    @if($threads->isNotEmpty())
                        <ul class="walletHistory list-unstyled">
                            @foreach($threads as $thread)
                            @if ($thread->is_internal == 0)
                            @if (Auth::user()->email != $thread->email)
                            <div align="right">
                            @else
                            <div align="left">
                            @endif
                                <li class="walletHistory__item mb-2" style="max-width: 90%;">
                                    <div class="walletHistory__item__body">
                                        <div class="media">
                                            @if (Auth::user()->email != $thread->email)
                                            <div data-toggle="tooltip" title="" class="walletHistory__item__type my-auto mr-2 mr-md-3" data-original-title="Recibido"><svg width="25" height="21" viewBox="0 0 25 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.6364 2.36362L1.36362 17.6364M1.36362 17.6364H10.0909M1.36362 17.6364V9.99999" stroke="#1DBA44" stroke-width="2" stroke-linecap="square"></path></svg></div>
                                            @else
                                            <div data-toggle="tooltip" title="" class="walletHistory__item__type my-auto mr-2 mr-md-3" data-original-title="Recibido"><svg width="25" height="21" viewBox="0 0 25 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 16.2727L17.2727 1M17.2727 1H8.54545M17.2727 1V8.63636" stroke="#1DBA44" stroke-width="2" stroke-linecap="square"></path></svg></div>
                                            @endif
                                            <div class="media-body">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="walletHistory__item__amount mb-0">{{$thread->name}}</h6>
                                                    <div class="walletHistory__item__date">
                                                    {{date('H:i m/d/Y',strtotime($thread->created_at))}}
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-wrap justify-content-between">
                                                    <div>
                                                        {{$thread->body}}
                                                        @if (isset($thread->file))
                                                        <?php
                                                            $files = explode(',',$thread->file);
                                                            ?>
                                                        <br><br>
                                                        @lang('support.attachments')
                                                        @foreach ($files as $file)
                                                        <br>
                                                        <a href="{{URL::to(str_replace(',', '', $file))}}" target="_blank">{{str_replace('/support_attachments/'.$number.'/','',$file)}}</a>
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                    <div>
                                                        campo
                                                        {{$thread->department}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </div>
                            @endif
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
<style>
    .r-border{
        border: 15px solid #f4f4f9!important;
        border-radius: 20px;
        background-color: #f4f4f9;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(function(){
        $('#reply,#replyCancel,#attachments').hide();
        $('#replyText').click(function() {
            $('#reply,#replyCancel,#attachmentsButton').show();
            $('#replyText').hide();
        });
        $('#attachmentsButton').click(function() {
            $('#attachments').show();
            $('#attachmentsButton').hide();
        });
        $('#replyCancel').click(function() {
            $('#reply,#replyCancel,#attachments').hide();
            $('#replyText').show();
        });
    });
</script>