@extends('layouts.antifraud-form-layout')

@section('content')

<div class="container">

  <form class="" action="{{URL::to('/edit-form').'/'.$form->id}}" method="post" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">Customer Data</div>
                <div class="card-body">

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">Full name</label>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group">
                          <input type="text" class="form-control" value="" name="full-name" required></label>
                        </div>
                      </div>
                    </div><br>

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">Email</label>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group">
                          <input type="email" class="form-control" value="" name="email" required></label>
                        </div>
                      </div>
                    </div><br>

                    <google-places-component2></google-places-component2><br>

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">Phone Number</label>
                      </div>
                      <div class="col-md-2">
                        <div class="input-group">
                          <input type="number" class="form-control" value="" name="phone-code" placeholder="code" required></label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group">
                          <input type="number" class="form-control" value="" name="phone-number" placeholder="number" required></label>
                        </div>
                      </div>
                    </div><br>

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">ID Document</label>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group">
                          <input type="file" class="" value="" name="id-document" required></label>
                        </div>
                      </div>
                    </div><br>

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">Selfie with ID Document</label>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group">
                          <input type="file" class="" value="" name="id-selfie" required></label>
                        </div>
                      </div>
                    </div><br>

                </div>
            </div>
        </div>
    </div><br>

    @if($form->type == 'INTERNATIONAL_TRANSFER')

      <international-transfer-component></international-transfer-component>

    @elseif($form->type == 'NATIONAL_TRANSFER')

      <national-transfer-component></national-transfer-component>

    @elseif($form->type == 'CASH_DEPOSIT')

      <cash-deposit-component></cash-deposit-component>

    @elseif($form->type == 'GIFT_CARD')

      <gift-card-component></gift-card-component>

    @elseif($form->type == 'VARO_MONEY')

      <varo-money-component></varo-money-component>

    @endif

    <div class="text-center">
      <button type="submit" class="btn btn-default">Send</button>
    </div>

  </form>

</div>

@endsection
