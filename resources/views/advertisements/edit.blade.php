@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card card-default">
            <div class="card-header">Edit advertisement</div>
              <div class="card-body">
                <form class="form-cotrol" action="{{ URL('/edit-advertisement') . '/' . $advertisement['ad_id'] }}" method="post">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Active</label>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <input type="checkbox" value="" name="ad-visible" @if($advertisement['visible']) checked @endif></label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <p>Is this advertisement currently displayed? Uncheck for hiding your advertisement temporarily.</p>
                    </div>
                  </div>

                  <google-places-component :def_lat="{{json_encode($advertisement['lat'])}}" :def_lon="{{json_encode($advertisement['lon'])}}" :def_city="{{json_encode($advertisement['city'])}}" :def_location="{{json_encode($advertisement['location_string'])}}" :def_country="{{json_encode($advertisement['countrycode'])}}"></google-places-component>

                  @if($advertisement['bank_name'] != '')
                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">Payment service / bank name</label>
                      </div>
                      <div class="col-md-4">
                        <div class="input-group">
                          <input type="text" value="{{$advertisement['bank_name']}}" class="form-control" name="ad-bank_name" required></label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <p>Optional. Bank/payment provider name/code. For international wire transfers, please specify bank SWIFT / BIC code</p>
                      </div>
                    </div>
                  @else
                    <input type="hidden" name="ad-bank_name" value="{{$advertisement['bank_name']}}">
                  @endif

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Currency</label>
                    </div>
                    <div class="col-md-4">
                      <select class="form-control" name="currency" required>
                        @foreach($paymen_currencies as $currency)
                          <option value="{{$currency}}" @if($advertisement['currency'] == $currency) selected @endif>{{$currency}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-6">

                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Min. transaction limit</label>
                    </div>
                    <div class="col-md-4">
                      <input type="number" name="ad-min_amount" class="form-control" value="{{$advertisement['min_amount']}}">
                    </div>
                    <div class="col-md-6">
                      Optional. Minimum transaction limit in one trade.
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Max. transaction limit</label>
                    </div>
                    <div class="col-md-4">
                      <input type="number" name="ad-max_amount" class="form-control" value="{{$advertisement['max_amount']}}">
                    </div>
                    <div class="col-md-6">
                      Optional. Maximum transaction limit in one trade. For online sells, your LocalBitcoins.com wallet balance may limit the maximum fundable trade also.
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Restrict amounts to</label>
                    </div>
                    <div class="col-md-4">
                      <input type="number" name="ad-limit_to_fiat_amounts" class="form-control" value="{{$advertisement['limit_to_fiat_amounts']}}">
                    </div>
                    <div class="col-md-6">
                      Optional. Restrict trading amounts to specific comma-separated integers, for example 20,50,100. In fiat currency (USD/EUR/etc). Handy for coupons, gift cards, etc.
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Price equation</label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" name="ad-price_equation" class="form-control" value="{{$advertisement['price_equation']}}" required>
                    </div>
                    <div class="col-md-6">
                      How the trade price is determined from the hourly market price. Please note that the advertiser is always responsible for all payment processing fees.
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Terms of trades</label>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <textarea name="ad-msg" class="form-control" rows="8" cols="80" value="{{$advertisement['msg']}}">{{$advertisement['msg']}}</textarea required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <p>Other information you wish to tell about your trade. </p>
                    </div>
                  </div><br>

                  <h3>Online buying options</h3><hr>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Payment window</label>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <input type="number" class="form-control" name="ad-payment_window_minutes" value="{{$advertisement['payment_window_minutes']}}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <p>Other information you wish to tell about your trade. </p>
                    </div>
                  </div><br>

                  <h3>Liquidity options</h3><hr>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Track liquidity</label>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <input type="checkbox" value="" name="ad-track_max_amount" @if($advertisement['track_max_amount']) checked @endif></label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <p>This option limits the liquidity of this advertisement to the max. transaction limit. Buyers cannot open and complete trades for more than this amount.</p>
                    </div>
                  </div><br>

                  <h3>Security options</h3><hr>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Identified people only</label>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <input type="checkbox" value="" name="ad-require_identification" @if($advertisement['require_identification']) checked @endif></label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <p>To contact your advertisement, users need to verify their identity by sending IDs, driver's licence or passport.</p>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">SMS verification required</label>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <input type="checkbox" value="" name="ad-sms_verification_required" @if($advertisement['sms_verification_required']) checked @endif></label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <p>Only contacts with a verified mobile phone number can contact you from the advertisement.</p>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Trusted people only</label>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <input type="checkbox" value="" name="ad-require_trusted_by_advertiser" @if($advertisement['require_trusted_by_advertiser']) checked @endif></label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <p>Restrict your advertisement to be shown only to users that you have marked as Trusted.</p>
                    </div>
                  </div><br>

                    <div class="text-center">
                      <button type="submit" class="btn btn-default" name="button">Edit Advertisement</button>
                      <a href="{{ URL::to('/dashboard') }}" class="btn" style="color:black">Cancel</a>
                    </div>

                </form>

              </div>
          </div>
      </div>
  </div>
</div>

@endsection
