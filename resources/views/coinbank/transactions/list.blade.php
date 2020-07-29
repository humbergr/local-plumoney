@extends('layouts.coinbank-layout')

@section('content')

    <section id="page-title" class="pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="text-center text-white">
                        <img src="{{ asset('img/cb-img/cb-trans-icon.png') }}" alt="Transactions" class="img-fluid">
                        <h1 class="page__title text-secondary mb-1">Transactions</h1>
                        <h5 class="page__subtitle">Income and Outcome BTC Transactions</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

{{--    <transactions-list2--}}
{{--            :transactions2="{{json_encode($transactions)}}"--}}
{{--            :profit-loss-pie="{{json_encode($profitLossPie)}}"--}}
{{--            :expenses-bars="{{json_encode($expensesBars)}}"--}}
{{--            :sales-sum="{{json_encode($salesSum)}}"--}}
{{--            :no-profit-pie="{{json_encode($noProfit)}}"--}}
{{--            :remainder="{{json_encode($remainder)}}"--}}
{{--            :sell-prices="{{json_encode($sellPrices)}}"--}}
{{--            :buy-prices="{{json_encode($buyPrices)}}"></transactions-list2>--}}
    <transactions-list2
            :transactions2="{{json_encode($transactions)}}"
            :profit-loss-pie="{{json_encode($profitLossPie)}}"
            :expenses-bars="{{json_encode($expensesBars)}}"
            :sales-sum="{{json_encode($salesSum)}}"
            :no-profit-pie="{{json_encode($noProfit)}}"
            :remainder="{{json_encode($remainder)}}"></transactions-list2>

    <!-- Modal -->
    <div class="modal fade" id="transactions-details-modal" tabindex="-1" role="dialog"
         aria-labelledby="transactionsDetailsModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-md-4">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <h5 class="text-primary font-weight-bold mb-0">Closed at <span class="text-secondary">2/4/2019</span>
                            </h5>
                        </div>
                        <div class="col-md-4 mb-4">
                            <h5 class="text-primary font-weight-bold mb-0">Transaction Partner</h5>
                            <h5 class="text-secondary font-weight-bold">zirdna (22; 100%)</h5>
                        </div>
                        <div class="col-md-4 mb-4">
                            <h5 class="text-primary font-weight-bold mb-0">BTC Volume</h5>
                            <h5 class="text-secondary font-weight-bold">0.04545224 BTC</h5>
                        </div>
                        <div class="col-md-4 mb-4">
                            <h4 class="text-primary font-weight-bold mb-0">Contact ID</h4>
                            <h4 class="text-secondary font-weight-bold">36738838</h4>
                        </div>
                        <div class="col-md-4 mb-4">
                            <h5 class="text-primary font-weight-bold mb-0">Transaction Status</h5>
                            <h5 class="text-secondary font-weight-bold">Bitcoins released</h5>
                        </div>
                        <div class="col-md-4 mb-4">
                            <h5 class="text-primary font-weight-bold mb-0">Price</h5>
                            <h5 class="text-secondary font-weight-bold">8,426,427.39 VES/BTC</h5>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-primary font-weight-bold mb-0">Trade Type</h5>
                            <h5 class="text-secondary font-weight-bold">ONLINE_SELL</h5>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-primary font-weight-bold mb-0">Amount</h5>
                            <h5 class="text-secondary font-weight-bold">383000.00 VES</h5>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-primary font-weight-bold mb-0">USD Price</h5>
                            <h5 class="text-secondary font-weight-bold">138.46</h5>
                        </div>
                    </div>
                </div>
                <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">Cerrar</button>
            </div>
        </div>
    </div>

@endsection
