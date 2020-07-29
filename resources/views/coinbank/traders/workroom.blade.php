@extends('layouts.coinbank-layout')

@section('content')

<section id="page-title" class="pt-5 pb-4">
      <div class="container">
          <div class="row">
              <div class="col-lg-6 mx-auto">
                  <div class="text-center text-white">
                      <img src="{{ asset('img/cb-img/cb-trans-icon.png') }}" alt="Transactions" class="img-fluid">
                      <h1 class="page__title text-secondary mb-1">Advertisements</h1>
                      <h5 class="page__subtitle">--------------------------------------</h5>
                  </div>
              </div>
          </div>
      </div>
</section>

<section id="transactions" class="py-section-3">
  <div class="container">
      <div class="card">
          <table class="table table-striped table-borderless mb-0">
            <thead>
              <tr>
                <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">ID</h5></th>
                <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Currency</h5></th>
                <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Amount</h5></th>
                <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Rate</h5></th>
                <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Operation</h5></th>
                <th class="text-right"></th>
              </tr>
            </thead>
            <tbody>
            @foreach ($ads as $ad)
              <tr>
                <td class="text-center"><a href="https://localbitcoins.com/ad/{{$ad->ad_id}}">{{$ad->ad_id}}</a></td>
                <td class="text-center">{{number_format($ad->amount,2)}}</td>
                <td class="text-center">{{$ad->currency}}</td>
                <td class="text-center">{{number_format($ad->rate,2)}}</td>
                <td class="text-center">
                  @if($ad->trade_type == 'ONLINE_BUY')
                    SELL
                  @else
                    BUY
                  @endif
                </td>
                @if(is_null($ad->contact_id))
                <td class="text-center"> <a href="{{URL::to('/create-contact'.'/'.$ad->id)}}" class="btn btn-secondary btn-pill btn-sm">Open contact</a> </td>
                @else
                <td class="text-center"> <a href="{{URL::to('/contact/chat'.'/'.$ad->id)}}" class="btn btn-secondary btn-pill btn-sm">Chat</a> </td>
                @endif
              </tr>
            @endforeach
            </tbody>
          </table>
      </div>
  </div>
</section>

@endsection
