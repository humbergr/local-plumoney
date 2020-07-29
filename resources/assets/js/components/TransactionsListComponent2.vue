<script src="../transactions-list.js"></script>

<template lang="html">
    <div class="">
        <section id="analytics" class="pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="sections-titles">Graphics</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 px-1 mb-2 mb-md-0">
                        <div class="card">
                            <div class="card-header d-block bg-white">
                                <div class="dropdown float-right mr-n2">
                                    <a class="text-muted" href="#" role="button" title="Adjust date"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-sliders fa-rotate-90"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right py-0">
                                        <input type="text" class="graph-ranges" id="daterange-g-profit">
                                    </div>
                                </div>
                                <h6 class="text-primary font-weight-bold mb-0">Profit and Loss</h6>
                                <small class="text-muted">
                                    {{profitLossGraph.humanStartDate}} - {{profitLossGraph.humanEndDate}}
                                </small>
                            </div>
                            <div class="chart-wrapper pb-3 px-3">
                                <canvas id="profit-loss-graph" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 px-1 mb-2 mb-md-0">
                        <div class="card">
                            <div class="card-header d-block bg-white">
                                <div class="dropdown float-right mr-n2">
                                    <a class="text-muted" href="#" role="button" title="Adjust date"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-sliders fa-rotate-90"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right py-0">
                                        <input type="text" class="graph-ranges" id="daterange-g-no-profit">
                                    </div>
                                </div>
                                <h6 class="text-primary font-weight-bold mb-0">Additional no-profit sales</h6>
                                <small class="text-muted">
                                    {{noProfitGraph.humanStartDate}} - {{noProfitGraph.humanEndDate}}
                                </small>
                            </div>
                            <div class="chart-wrapper pb-3 px-3">
                                <canvas id="no-profit-graph" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 px-1 mb-2 mb-md-0">
                        <div class="card">
                            <div class="card-header bg-white">
                                <div class="dropdown float-right mr-n2">
                                    <a class="text-muted" href="#" role="button" title="Adjust date"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-sliders fa-rotate-90"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right py-0">
                                        <input type="text" class="graph-ranges" id="daterange-g-expenses">
                                    </div>
                                </div>
                                <h6 class="text-primary font-weight-bold mb-0">Purchases and Sales</h6>
                                <small class="text-muted">
                                    {{expensesGraph.humanStartDate}} - {{expensesGraph.humanEndDate}}
                                </small>
                            </div>
                            <div class="chart-wrapper pb-3 px-3">
                                <canvas id="cur-transactions" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 px-1 mb-2 mb-md-0">
                        <div class="card">
                            <div class="card-header bg-white">
                                <div class="dropdown float-right mr-n2">
                                    <a class="text-muted" href="#" role="button" title="Adjust date"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-sliders fa-rotate-90"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right py-0">
                                        <input type="text" class="graph-ranges" id="daterange-g-ssum">
                                    </div>
                                </div>
                                <h6 class="text-primary font-weight-bold mb-0">Sales by month</h6>
                                <small class="text-muted">
                                    {{salesSumGraph.humanStartDate}} - {{salesSumGraph.humanEndDate}}
                                </small>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="earn-transactions" height="370"></canvas>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-md-12 px-1 mb-2 mt-3 mb-md-0">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h6 class="text-primary font-weight-bold mb-0">USD Buy Prices</h6>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="usd-sell-prices" height="370"></canvas>
                            </div>
                        </div>
                    </div>-->
                    <div class="col-md-12 px-1 mb-2 mt-3 mb-md-0">
                        <div class="card">
                            <div class="card-header bg-white">
                                <!--<div class="dropdown float-right mr-n2">
                                    <a class="text-muted" href="#" role="button" title="Adjust date"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-sliders fa-rotate-90"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right py-0">
                                        <input type="text" class="graph-ranges" id="daterange-g-ssum">
                                    </div>
                                </div>-->
                                <h6 class="text-primary font-weight-bold mb-0">USD Sell Prices</h6>
                                <!--<small class="text-muted">
                                    {{salesSumGraph.humanStartDate}} - {{salesSumGraph.humanEndDate}}
                                </small>-->
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="usd-buy-prices" height="370"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="remainings" class="py-section-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="sections-titles">Remaining BTC to Spend</h3>
                    </div>
                </div>
                <div class="card">
                    <div class="loader--wrapper" :class="{ '--loading': loader }">
                        <table class="table table-striped table-borderless mb-0">
                            <thead>
                            <tr>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Purchased Amount</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Purchase Date</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Purchase Rate</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Purchase Price</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Remaining BTC</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Estimated (R) Value</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Account</h5>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="remainderBTC in remainder">
                                <td class="text-center">
                                    {{remainderBTC.amount_btc}} BTC
                                </td>
                                <td class="text-center">
                                    {{getDate(remainderBTC.released_date)}}
                                </td>
                                <td class="text-center">
                                    {{formatMoney(remainderBTC.usd_price/remainderBTC.amount_btc)}}
                                </td>
                                <td class="text-center">
                                    {{formatMoney(remainderBTC.usd_price)}}
                                </td>
                                <td class="text-center">
                                    {{remainderBTC.remaining}} BTC
                                </td>
                                <td class="text-center">
                                    {{formatMoney(remainderBTC.remaining * bitstampPriceNow)}}
                                </td>
                                <td class="text-center">
                                    {{remainderBTC.account_name}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="loader">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="sale-calculator" class="py-section-3 sale-calculator-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="sections-titles">Sale Calculator</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <form id="btc-sale" class="calculator-btc-sale">
                            <label>BTC amount to sell
                                <input type="number" step="0.00000001" id="btc-amount-to-sell">
                            </label>
                            <label>Estimated profit
                                <input type="number" step="1" id="estimated-profit-to-sell"> %
                            </label>
                            <label>Account
                                <select id="btc-account-selling">
                                    <option value="cristinadlr">cristinadlr</option>
                                    <option value="gdf12018">gdf12018</option>
                                    <option value="AKB01">AKB01</option>
                                </select>
                            </label>
                            <a href="javascript:void(0);"
                               @click="calculateBtcSale"
                               class="btn btn-primary">Calculate</a>
                        </form>
                    </div>
                </div>

                <div class="row calculator-results">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-no-btc" role="alert">
                                    There's no enough BTC for this sale or some fragment doesn't have any cost associated.
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6>Fragments to use</h6>
                                <table class="table table-striped table-borderless mb-0">
                                    <tbody>
                                    <tr v-for="toSale in fragmentsToSale">
                                        <td>{{getDate(toSale.released_date)}}</td>
                                        <td>{{toSale.remaining}} BTC</td>
                                        <td>{{formatMoney(toSale.usd_price/toSale.amount_btc)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4 suggestions">
                                <p class="suggested-rate">
                                    Suggested Sale Rate:
                                    <span>{{formatMoney(suggestedSaleRate)}} USD</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="filtering">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="sections-titles">Transactions</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-8 mx-auto">
                        <div class="card">
                            <div class="card-body pb-0 px-lg-5">
                                <div id="main-filtering" class="mb-3">
                                    <div class="row">
                                        <div class="col-5 px-1 px-md-2">
                                            <button class="btn btn-outline-secondary btn-lg btn-pill btn-block text-truncate"
                                                    v-on:click="selectTransactionType('Outgoing')"
                                                    :class="{ active: outgoing }">
                                                <img src="/img/cb-img/incoming-icon.png" class="button__img mr-2">Incoming
                                                transactions
                                            </button>
                                        </div>
                                        <div class="col-2 text-center px-1 px-md-2">
                                            <button class="btn btn-outline-secondary btn-lg btn-rounded"
                                                    v-on:click="selectTransactionType('all')"
                                                    :class="{ active: all_t }">All
                                            </button>
                                        </div>
                                        <div class="col-5 px-1 px-md-2">
                                            <button class="btn btn-outline-secondary btn-lg btn-pill btn-block text-truncate"
                                                    v-on:click="selectTransactionType('Incoming')"
                                                    :class="{ active: incoming }">
                                                Outgoing transactions<img src="/img/cb-img/outgoing-icon.png"
                                                                          class="button__img ml-2">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!--<div id="subfiltering">
                                    <ul class="filtering-tabs nav justify-content-center mb-0">
                                        <li class="nav-item">
                                            <a class="nav-link underline-reveal" href="javascript:void(0)" :class="{ active: all_c }" disabled>All</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link underline-reveal" href="javascript:void(0)" :class="{ active: ves }" disabled>Bolivares</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link underline-reveal" href="javascript:void(0)" :class="{ active: usd }" disabled>Dollars</a>
                                        </li>
                                    </ul>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="transactions" class="py-section-3">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div class="d-flex justify-content-between align-items-end flex-grow-1">
                        <form action="" class="form-inline mx-auto">
                            <div class="form-group d-block mb-0 mr-3">
                                <label for="creation-date-filter" class="mb-1">Creation date filter</label>
                                <div class="input-group">
                                    <input type="text"
                                           id="creation-date-filter"
                                           class="form-control"
                                           aria-label="Creation date filter"
                                           aria-describedby="creation-date-filter">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group d-block mb-0 mr-3">
                                <label for="earnings-filter" class="mb-1">Earnings filter</label>
                                <select name=""
                                        @change="changeProfitLoss"
                                        id="earnings-filter"
                                        class="custom-select">
                                    <option value="-1">All</option>
                                    <option value="1">Only loses</option>
                                    <option value="2">Only earnings</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div>
                        <a class="btn btn-secondary btn-pill px-md-3 ml-3" :href="getUrl('/create-transaction')">
                            Create transaction
                        </a>
                        <a class="btn btn-outline-secondary btn-pill px-md-3" :href="getUrl('/app')">Return</a>
                    </div>
                </div>
                <div class="card" id="list">
                    <div class="loader--wrapper" :class="{ '--loading': loader }">
                        <table class="table table-striped table-borderless mb-0">
                            <thead>
                            <tr>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Type</h5>
                                </th>
                                 <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Operation</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Transaction ID</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Amount</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Currency Amount</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">USD Price/Cost</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Rate</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Account Name</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Date</h5>
                                </th>
                                <th class="text-center">
                                    <h5 class="text-muted font-weight-bold mb-0">Profit / Loss</h5>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="transaction in transactions.data"
                                v-bind:class="{ 'table-danger' : (!transaction.usd_price && transaction.currency != 'USD')  }">
                                <th v-if="transaction.type == 'Outgoing'" class="text-center">
                                    <button class="btn btn-outline-secondary btn-sm btn-pill text-muted"
                                            data-toggle="modal" data-target="#transactions-details-modal"
                                            v-on:click="openModal2(transaction.json_data.data, transaction.usd_price, transaction.id, transaction)">
                                        <img src="/img/cb-img/incoming-icon.png" class="button__img mr-1" height="20px">Incoming
                                    </button>
                                </th>
                                <th v-else class="text-center">
                                    <button class="btn btn-outline-secondary btn-sm btn-pill text-muted"
                                            data-toggle="modal" data-target="#transactions-details-modal"
                                            v-on:click="openModal(transaction.json_data.data, transaction.usd_price)">
                                        <img src="/img/cb-img/outgoing-icon.png" class="button__img mr-1" height="20px">Outgoing
                                    </button>
                                </th>

                                <td>{{transaction.salida}}</td>
                                <td class="text-center"><a :href="getUrl('/transactions/edit/' + transaction.id)">{{transaction.transaction_id
                                    || 'Edit'}}</a></td>
                                <td class="text-center"><span class="text-secondary font-weight-bold">{{transaction.amount_btc}} BTC</span>
                                </td>
                                <td class="text-center">{{transaction.amount.toLocaleString('en')}}
                                    {{transaction.currency}}
                                </td>
                                <td class="text-center">
                                    {{formatMoney(transaction.usd_price)}}
                                </td>
                                <td class="text-center">
                                    {{formatMoney(transaction.usd_price/transaction.amount_btc)}}
                                </td>
                                <td class="text-center">{{transaction.account_name}}</td>
                                <td class="text-center">{{getDate(transaction.released_date)}}</td>
                                <td class="text-center"
                                    v-if="transaction.outgoing_btc">
                                    <span :class="transaction.outgoing_btc.profit.toString().slice(0, 1) === '-' ?
                                        'text-danger' : 'text-success'">
                                        {{formatMoney(transaction.outgoing_btc.profit)}}
                                    </span>
                                </td>
                                <td class="text-center" v-else><span class="text-secondary">BTC Purchase</span></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="loader">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <nav id="transactions-pagination" class="my-3" aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link px-3" href="javascript:void(0);" aria-label="Load more"
                               style="width: unset; height: unset; border-radius: 10rem !important;"
                               v-if="transactions.current_page !== transactions.last_page"
                               v-on:click="transactionsPagination(transactions.current_page + 1)">
                                <span aria-hidden="true">Load More</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="transactions-details-modal" tabindex="-1" role="dialog"
             aria-labelledby="transactionsDetailsModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-md-4">
                        <div class="row" v-if="current_modal_transaction.is_manual">
                            <div class="col-md-4 mb-4">
                                <h5 class="text-primary font-weight-bold mb-0">Closed at <span class="text-secondary">{{modal_close_date}}</span>
                                </h5>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h5 class="text-primary font-weight-bold mb-0">Transaction Partner</h5>
                                <h5 class="text-secondary font-weight-bold">{{modal_partner}}</h5>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h5 class="text-primary font-weight-bold mb-0">BTC Volume</h5>
                                <h5 class="text-secondary font-weight-bold">{{modal_volume}} BTC</h5>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h4 class="text-primary font-weight-bold mb-0">Contact ID</h4>
                                <h4 class="text-secondary font-weight-bold">{{modal_contact_id}}</h4>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h5 class="text-primary font-weight-bold mb-0">Transaction Status</h5>
                                <h5 class="text-secondary font-weight-bold">Bitcoins released</h5>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h5 class="text-primary font-weight-bold mb-0">Price</h5>
                                <h5 class="text-secondary font-weight-bold">{{modal_price.toLocaleString('en')}}
                                    {{modal_currency}}/BTC</h5>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h5 class="text-primary font-weight-bold mb-0">Trade Type</h5>
                                <h5 class="text-secondary font-weight-bold">{{modal_trade_type}}</h5>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h5 class="text-primary font-weight-bold mb-0">Amount</h5>
                                <h5 class="text-secondary font-weight-bold">{{modal_amount.toLocaleString('en')}}
                                    {{modal_currency}}</h5>
                            </div>
                            <div class="col-md-4 mb-4">
                                <h5 class="text-primary font-weight-bold mb-0">USD Price</h5>
                                <h5 v-if="modal_usd_price" class="text-secondary font-weight-bold">
                                    {{modal_usd_price.toLocaleString('en')}} USD</h5>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-primary font-weight-bold mb-0">Profit</h5>
                                <h5 v-if="profit" class="text-secondary font-weight-bold">{{modal_profit}} USD</h5>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <h5>This is an external transaction, there is no localbitcoins data to show.</h5>
                        </div>
                    </div>
                    <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">Cerrar
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<style lang="scss">
    .graph-ranges {
        font-size: 14px;
        color: #777;
        border-color: #303392;
        padding: 6px;
    }

    .sections-titles {
        font-size: 45px;
        text-align: center;
        color: #f7941d;
        font-weight: 700;
    }

    .calculator-btc-sale {
        background: #fff;
        padding: 1rem 12px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 1rem;

        input,
        select {
            margin: 0 10px;
        }

        label {
            margin: 0 1rem;
        }
    }

    .calculator-results {
        .row {
            margin: 0;
            background: #fff;
            padding: 1rem 12px;
            border-radius: 8px;
        }

        .alert-no-btc {
            display: none;
        }

        .suggestions {
            display: flex;
            align-items: center;
            justify-content: center;

            .suggested-rate {
                text-align: center;

                span {
                    display: block;
                    font-weight: 700;
                }
            }
        }

        h6 {
            text-align: center;
            margin-bottom: 1rem;
        }
    }
</style>
