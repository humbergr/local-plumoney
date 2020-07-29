@extends('layouts.app')

@section('content')

<div class="container">
    <form class="" action="{{URL::to('/filter-transactions')}}" method="get">

      <div class="row" style="margin-bottom:10px">
        <div class="col-md-1">

        </div>
        <div class="col-md-3">
          <select class="form-control" name="currency" required>
              <option value="All Currencies">All Currencies</option>
              <option value="USD">USD</option>
              <option value="VES">VES</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn">Filter by currency</button>
        </div>
      </div>
    </form>
  <div class="row justify-content-center">
      <div class="col-md-10">
          <div class="card card-default">
            <div class="card-header">Invite User</div>
              <div class="card-body">

                <div class="row">

                  <table style="width:100%">

                    <tr class="text-center">
                      <th>Amount</th>
                      <th>Currency</th>
                      <th>Transaction ID</th>
                      <th>Transaction type</th>
                      <th>Bank name</th>
                      <th>Created at</th>
                      <th></th>
                    </tr>

                    @foreach($transactions as $transaction)

                      <tr class="text-center">
                        <td>{{number_format($transaction->amount, 2)}}</td>
                        <td>{{$transaction->currency}}</td>
                        <td>{{$transaction->transaction_id}}</td>
                        <td>{{$transaction->type}}</td>
                        <td>{{$transaction->bank_name}}</td>
                        <td>{{ date('d M Y', strtotime($transaction->created_at)) }}</td>
                        <td>
                          <th><a href="{{URL::to('/transactions/edit/' . $transaction->id)}}" class="btn btn-default grey" style="background-color:buttonface;color:black">Edit</a></th>
                        </td>
                      </tr>

                    @endforeach

                  </table>

                </div>

              </div>
          </div>

        <div class="text-center">
          <nav aria-label="Page navigation" style="margin-top:10px;">
          	{{ $transactions->links() }}
          </nav>
        </div>

        <div style="text-align:right">
          <a href="{{ URL::to('/create-transaction') }}" class="btn btn-default" style="background-color:buttonface;color:black">Create transaction</a>
          <a href="{{ URL::to('/app') }}" class="btn" style="color:black">Return</a>
        </div>

      </div>
  </div>

</div>

@endsection
