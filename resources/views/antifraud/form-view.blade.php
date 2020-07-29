@extends('layouts.antifraud-form-layout')

@section('content')

<div class="container">

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-default">
          <div class="card-header">Customer Information</div>
            <div class="card-body">

              <p>Full name: {{$form->fullname}}</p>

              <p>Email: {{$form->email}}</p>

              <p>Location: {{$form->location}}</p>

              <p>Phone number: {{$form->phone}}</p>

              @if(isset($form->contact_id))
                <p>Contact ID: {{$form->contact_id}}</p>
              @endif

              <div class="row" style="margin-bottom:20px">
                <div class="col-md-2">
                  <p>ID Document:</p>
                </div>
                <div class="col-md-8">
                  <a href="{{ asset('assets/id_documents/'.$form->id_document) }}">
                    <img src="{{ asset('assets/id_documents/'.$form->id_document) }}" style="max-width:250px" alt="">
                  </a><br>
                </div>
              </div>

              <div class="row" style="margin-bottom:20px">
                <div class="col-md-2">
                  <p>Verification Photo:</p>
                </div>
                <div class="col-md-8">
                  <a href="{{ asset('assets/id_document_selfies/'.$form->id_document_selfie) }}">
                    <img src="{{ asset('assets/id_document_selfies/'.$form->id_document_selfie) }}" style="max-width:250px" alt="">
                  </a><br>
                </div>
              </div>

            </div>
        </div>
    </div>
</div><br>

@if($form->type != 'SIMPLE_FORM')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-default">
          <div class="card-header">Payment Information</div>
            <div class="card-body">
              @foreach($form->form_data as $key => $data)
                @if($key == 'authorization_document' || $key == 'gift_card_photo' || $key == 'invoice_picture' || $key == 'teller_business_card' || $key == 'bank_deposit_photo' || $key == 'varo_money_photo')
                  <div class="row" style="margin-bottom:20px">
                    <div class="col-md-2">
                      <p>{{ucwords(str_replace('_', ' ', $key))}}:</p>
                    </div>
                    <div class="col-md-8">
                      <a href="{{ asset('assets/'.$key.'s'.'/'.$data) }}">
                        <img src="{{ asset('assets/'.$key.'s'.'/'.$data) }}" style="max-width:250px" alt="">
                      </a><br>
                    </div>
                  </div>
                @else
                  <p>{{ucwords(str_replace('_', ' ', $key))}}: {{$data}}</p>
                @endif
              @endforeach
            </div>
        </div>
    </div>
</div><br>
@endif

<div class="text-center">
  <a href="{{URL::to('/antifraud-forms')}}" class="btn btn-default" style="color:black">Back</a>
</div>

</div>

@endsection
