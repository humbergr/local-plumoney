@extends('support.layout.support-layout')
<title>@lang('support.title')</title>
@section('content')
    <section class="py-section-3">
        <div class="container">
            <div class="row no-gutters shadow">
            	<div class="col-md-8">
                    <div class="body-bg-color px-3 py-4 p-md-5 h-100">
                        <form class="" action="{{URL::to('/create-ticket')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="ip" value="{{Request::ip()}}">
                            <div class="form-row">
                                <div class="col-md offset-md-2">
                                    <h3 class="text-primary font-weight-bold mb-4">@lang('support.createTicketTitle')</h3>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 text-md-right pr-md-3">
                                    <label for="name">@lang('support.name')*</label>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        @if (Auth::user())
                                        <label>{{Auth::user()->name}}</label>
                                        @else
                                        <input name="name" type="name" class="form-control" required>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 text-md-right pr-md-3">
                                    <label for="email">@lang('support.email')*</label>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        @if (Auth::user())
                                        <label>{{Auth::user()->email}}</label>
                                        @else
                                        <input name="email" type="email" class="form-control" required>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 text-md-right pr-md-3">
                                    <label for="subject">@lang('support.subject')*</label>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <input name="subject"  type="text" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 text-md-right pr-md-3">
                                    <label for="department">@lang('support.department')*</label>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <select required="required" name="department" class="custom-select">
                                        	<option value="">@lang('support.departmentSelect')</option>
                                        @foreach ($departments as $department)
                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 text-md-right pr-md-3">
                                    <label for="message">@lang('support.ticketMsg')*</label>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control" style="min-height: 100px" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 text-md-right pr-md-3">
                                    <label for="file">@lang('support.uploadFile')</label>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <input type="file" name="file[]" multiple="multiple" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-row">
                                <div class="col-md-3 text-md-right pr-md-3">
                                    <label for="url">URL</label>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <input name="url"  type="text" class="form-control">
                                    </div>
                                </div>
                            </div> -->
                            <div class="text-right">
                                <button type="submit" class="btn btn-secondary btn-pill px-4">@lang('support.sendButton')</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-primary text-white px-3 px-md-5 py-5 py-md-5 h-100">
                        <div class="text-center">
                            <img src="mstile-150x150.png" class="img-fluid mb-4 mb-md-5" style="max-width: 195px">
                            <h3 class="font-weight-bold" style="margin-top: -80px;margin-bottom: 40px;">@lang('support.titleSupportCenter')</h3>
                        </div>
                        <ul class="list-unstyled mb-5 font-14">
                            <li class="mb-3">
                                <div class="media">
                                    <div class="text-center">
                                        <i class="fa fa-phone fa-2x mr-2 va-middle" style="width: 2rem"></i>
                                    </div>
                                    <div class="media-body">
                                        <a href="tel:+1 (786) 245-8123" class="text-light">+1 (786) 245-8123</a>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="media">
                                    <div class="text-center">
                                        <i class="fa fa-envelope fa-2x mr-2 va-middle" style="width: 2rem"></i>
                                    </div>
                                    <div class="media-body"><a href="mailto:customercare@americankryptosbank.com" class="text-light">customercare@americankryptosbank.com</a></div>
                                </div>
                            </li>
                        </ul>
                        <h6 class="text-secondary font-weight-bold text-center mb-4">@lang('support.socialTitle')</h6>
                        <ul class="list-inline mb-0 text-center">
                            <li class="list-inline-item">
                                <a href="" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-facebook fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-instagram fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-twitter fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-linkedin fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>                
            </div>
        </div>
    </section>
@endsection