@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card card-default">
            <div class="card-header">Create transaction</div>
              <div class="card-body">

                <form class="form-cotrol" action="{{ URL('/create-incoming-transaction') }}" method="post">

                  {{ csrf_field() }}

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Bank Name or ID code</label>
                    </div>
                    <div class="col-md-5">
                      <div class="input-group">
                        <input type="text"  class="form-control" value="" name="bank_name" required></label>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="input-group">
                        <p>Required. The name or identification code of the bank of the transaction.</p>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Transaction ID code</label>
                    </div>
                    <div class="col-md-5">
                      <div class="input-group">
                        <input type="text"  class="form-control" value="" name="transaction_id" required></label>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="input-group">
                        <p>Required. The identification of the transaction.</p>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Amount</label>
                    </div>
                    <div class="col-md-5">
                      <input type="number" step="0.01" class="form-control" name="amount" value="">
                    </div>
                    <div class="col-md-5">
                      <p>Required. The amount of the transaction.</p>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Transaction type</label>
                    </div>
                    <div class="col-md-5">
                      <select class="form-control" name="type" required>
                          <option value="Incoming">Incoming transaction</option>
                          <option value="Outgoing">Outgoing transaction</option>
                      </select>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Currency to work</label>
                    </div>
                    <div class="col-md-5">
                      <select class="form-control" name="currency" required>
                          <option value="USD">USD</option>
                          <option value="VES">VES</option>
                      </select>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Additional information</label>
                    </div>
                    <div class="col-md-5">
                      <textarea name="msg" class="form-control" rows="8" cols="80" value=""></textarea>
                    </div>
                  </div><br>

                  <div class="text-center">
                    <button type="submit" class="btn btn-default" name="button">Create transaction</button>
                    <a href="{{ URL::to('/transactions') }}" class="btn" style="color:black">Cancel</a>
                  </div>

                </form>

              </div>
          </div>
      </div>
  </div>
</div>

@endsection
