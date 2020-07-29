@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Error Transactions</div>
                    <div class="card-body">
                        <div class="row">
                            <table style="width:100%">
                                <tr class="text-center">
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>BTC Amount</th>
                                    <th>Released At</th>
                                    <th></th>
                                </tr>
                                @if (count($errors) > 0)
                                    @foreach ($errors as $error)
                                        <tr class="text-center">
                                            <td>{{$error->transaction_id}}</td>
                                            <td>Unsolved</td>
                                            <td>{{$error->amount_btc}}</td>
                                            <td>{{substr($error->released_date, 0, 10)}}</td>
                                            <td><a href="{{URL::to('/solve-error/') . '/'. $error->id}}">Solve</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="5">No errrors</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="text-center">
            <a href="{{ URL::to('/transactions') }}" class="btn" style="color:black">Cancel</a>
        </div>
    </div>

@endsection
