@extends('layouts.coinbank-layout')

@section('content')

<section id="page-title" class="pt-5 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="text-center text-white">
                    <h1 class="page__title text-primary mb-1">{{$trader->name}} Details</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="transactions" class="py-section-3">
      <div class="container">
          <div class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
              <h6 class="text-primary font-weight-bold text-truncate mb-md-0">Trader Transactions</h6>
              <form action="" method="get" class="form-inline flex-md-nowrap ml-md-3">
                  <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                      <div class="input-group-prepend">
                          <span class="input-group-text">
                              <i class="fa fa-search text-muted"></i>
                          </span>
                      </div>
                      <select class="form-control" name="trans-status">
                        <option value="all" selected>All Statuses</option>
                        <option value="1">Approved</option>
                        <option value="2">Rejected</option>
                        <option value="3">Failed</option>
                        <option value="4">In Process</option>
                        <option value="5">Refunded</option>
                        <option value="0">Open</option>
                      </select>
                  </div>
                  <div class="input-group mb-3 mb-md-0">
                      <input type="date" id="creation-date-filter" class="form-control"
                             aria-label="Creation date filter"
                             name="transaction-date"
                             value="{{request()['transaction-date']}}"
                             aria-describedby="creation-date-filter">
                      <div class="input-group-append">
                          <span class="input-group-text bg-white text-muted"
                                id="creation-date-filter"><i class="fa fa-calendar"></i></span>
                      </div>
                  </div>
                  <div class="input-group mb-3 mb-md-0">
                      <input type="submit" class="ml-3 btn btn-primary" value="Filtrar">
                  </div>
              </form>
          </div>
          <div class="card">
              <table class="table table-striped table-borderless mb-0">
                <thead>
                  <tr>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Sent Amount</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Received Amount</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Status</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Payment Medthod</h5></th>
                      <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Profit</h5></th>
                    <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Date</th>
                    <th class="text-center"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($transactions as $transaction)
                  <tr>
                    <th class="text-center">
                      {{$transaction->sender_fiat_amount}} {{$transaction->sender_fiat}}
                    </th>
                    <th class="text-center">
                      {{$transaction->receiver_fiat_amount}} {{$transaction->receiver_fiat}}
                    </th>
                    <td class="text-center">
                      @if($transaction->status == 1)
                          <span class="text-success">Approved</span>
                      @elseif($transaction->status == 2)
                          <span class="text-danger">Rejected</span>
                      @elseif($transaction->status == 3)
                          <span class="text-warning">Failed</span>
                      @elseif($transaction->status == 4)
                          <span class="text-warning">In Process</span>
                      @elseif($transaction->status == 5)
                          <span class="text-warning">Refunded</span>
                      @else
                          <span class="text-info">Open</span>
                      @endif
                    </td>
                    <td class="text-center">
                      {{$transaction->paymentMethod()}}
                    </td>

                    @if(!is_null($transaction->contact_id) && !is_null($transaction->outgoing) && $transaction->outgoing->profit !== 0)
                    <td class="text-center">
                       {{number_format($transaction->getProfit(), 2)}} USD
                    </td>
                    @else
                    <td class="text-center">
                        Not calculated.
                    </td>
                    @endif

                    <td class="text-center">
                      {{$transaction->created_at->format('d M Y')}}
                    </td>
                    <td class="text-center"><a href="{{URL::to('exchange-transaction/'.$transaction->id)}}">View Transactions</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
          </div>

          <div class="d-flex justify-content-center mt-3">
              {{ $transactions->links() }}
          </div>
      </div>
  </section>

@endsection
