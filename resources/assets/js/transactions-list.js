import * as numeral from "numeral";
import * as moment from "moment";
import * as datepicker from "bootstrap-datepicker";
import * as daterangepicker from "daterangepicker";
import * as Chart from "chart.js";
import * as $ from "jquery";

numeral.defaultFormat('$0,0.00');

let pieChart      = {},
    noProfitPie   = {},
    expensesChart = {},
    salesSum      = {};
    // usdSellPrices = {},
    // usdBuyPrices = {};

export default {
    props: ['transactions2', 'noProfitPie', 'profitLossPie', 'expensesBars', 'salesSum', 'remainder',
        // 'sellPrices', 'buyPrices'
    ],
    data() {
        return {
            bitstampPriceNow: 0,
            incoming: false,
            all_t: true,
            outgoing: false,
            ves: false,
            usd: false,
            all_c: true,
            fragmentsToSale: [],
            suggestedSaleRate: 0,
            transaction_currency: 'all',
            transaction_type: 'all',
            transactions: this.transactions2,
            modal_contact_id: '',
            modal_close_date: '',
            modal_trade_type: '',
            modal_partner: '',
            modal_amount: '',
            modal_volume: '',
            modal_currency: '',
            modal_price: '',
            modal_usd_price: '',
            loader: false,
            modal_profit: '',
            profit: false,
            current_modal_transaction: {},
            startDate: null,
            endDate: null,
            filterProfit: -1,
            profitLossGraph: {
                startDate: null,
                endDate: null,
                humanStartDate: null,
                humanEndDate: null,
                data: this.profitLossPie
            },
            noProfitGraph: {
                startDate: null,
                endDate: null,
                humanStartDate: null,
                humanEndDate: null,
                data: this.noProfitPie
            },
            expensesGraph: {
                startDate: null,
                endDate: null,
                humanStartDate: null,
                humanEndDate: null,
                data: this.expensesBars
            },
            salesSumGraph: {
                startDate: null,
                endDate: null,
                humanStartDate: null,
                humanEndDate: null,
                data: this.salesSum
            },
            // usdSellPricesGraph: {
            //     data: this.salesSum
            // }
        }
    },
    mounted: function () {
        this.$nextTick(function () {
            //Graphics
            let vueObject = this;

            pieChart = new Chart($('#profit-loss-graph'), {
                type: 'doughnut',
                data: {
                    labels: [
                        'Profit: ' + vueObject.formatMoney(vueObject.profitLossGraph.data[0]),
                        'Loss: ' + vueObject.formatMoney(vueObject.profitLossGraph.data[1])
                    ],
                    datasets: [{
                        data: vueObject.profitLossGraph.data,
                        backgroundColor: ['#2375f9', '#ff0000'],
                        hoverBackgroundColor: ['#2375f9', '#ff0000'],
                        borderWidth: 0
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return vueObject.formatMoney(
                                    data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]
                                );
                            }
                        }
                    },
                    responsive: true,
                    legend: {
                        labels: {
                            fontSize: 14,
                            fontColor: '#333',
                            fontStyle: 'normal',
                            boxWidth: 8,
                            padding: 8
                        }
                    }
                }
            });

            noProfitPie = new Chart($('#no-profit-graph'), {
                type: 'doughnut',
                data: {
                    labels: [
                        'Employee\'s Salary: ' + vueObject.formatMoney(vueObject.noProfitGraph.data[0]),
                        'Operational Expenses: ' + vueObject.formatMoney(vueObject.noProfitGraph.data[1]),
                        'Regular Payments: ' + vueObject.formatMoney(vueObject.noProfitGraph.data[2]),
                        'Internal Inventory Movement: ' + vueObject.formatMoney(vueObject.noProfitGraph.data[3]),
                        'Other Expenses: ' + vueObject.formatMoney(vueObject.noProfitGraph.data[4])
                    ],
                    datasets: [{
                        data: vueObject.noProfitGraph.data,
                        backgroundColor: ['#a7441f', '#ff3654', '#7056ff', '#efb513', '#000000'],
                        hoverBackgroundColor: ['#a7441f', '#ff3654', '#7056ff', '#efb513', '#000000'],
                        borderWidth: 0
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                // console.log(tooltipItem);
                                // console.log(data);
                                return data.labels[tooltipItem.index];
                            }
                        }
                    },
                    responsive: true,
                    legend: {
                        labels: {
                            fontSize: 14,
                            fontColor: '#333',
                            fontStyle: 'normal',
                            boxWidth: 8,
                            padding: 8
                        }
                    }
                }
            });

            expensesChart = new Chart($('#cur-transactions'), {
                type: 'horizontalBar',
                data: {
                    labels: ['Sales', 'Purchases'],
                    datasets: [
                        {
                            backgroundColor: [
                                '#49b86d',
                                '#ffc039'
                            ],
                            hoverBackgroundColor: ['#49b86d', '#ffc039'],
                            data: vueObject.expensesGraph.data,
                        },
                    ]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return vueObject.formatMoney(
                                    data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]
                                );
                            }
                        }
                    },
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: false,
                            },
                        }],
                        yAxes: [{
                            barThickness: 'flex',
                            minBarLength: 10,
                            gridLines: {
                                display: false,
                            }
                        }]
                    }
                }
            });

            salesSum = new Chart($('#earn-transactions'), {
                type: 'line',
                data: {
                    labels: [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December',
                    ],
                    datasets: [
                        {
                            backgroundColor: 'rgba(255,255,255,.1)',
                            borderColor: '#58d93a',
                            borderWidth: 2,
                            pointRadius: 0,
                            data: vueObject.salesSumGraph.data
                        }
                        //     {
                        //         backgroundColor: '#f07807',
                        //         borderColor: 'rgba(255,255,255,1)',
                        //         pointRadius: 0,
                        //         data: [random(), random(), random(), random(), random(), random(), random(),]
                        //     }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            display: false,
                            gridLines: {
                                display: false,
                                color: 'transparent',
                                zeroLineColor: 'transparent'
                            },
                            ticks: {
                                display: false,
                                fontSize: 2,
                                fontColor: 'transparent'
                            }
                        }],
                        yAxes: [{
                            display: false,
                            ticks: {
                                display: false,
                                // min: -4,
                                // max: 39
                            }
                        }]
                    },
                    elements: {
                        line: {
                            tension: 0.00001,
                            borderWidth: 1
                        },
                        point: {
                            radius: 4,
                            hitRadius: 10,
                            hoverRadius: 4
                        }
                    }
                }
            });

            // usdSellPrices = new Chart($('#usd-sell-prices'), {
            //     type: 'line',
            //     data: {
            //         datasets: [
            //             {
            //                 backgroundColor: 'rgba(255,255,255,.1)',
            //                 borderColor: '#58d93a',
            //                 borderWidth: 2,
            //                 pointRadius: 0,
            //                 data: vueObject.sellPrices
            //             }
            //         ]
            //     },
            //     options: {
            //         maintainAspectRatio: false,
            //         legend: {
            //             display: false
            //         },
            //         scales: {
            //             xAxes: [{
            //                 type: 'time'
            //             }]
            //         },
            //         elements: {
            //             line: {
            //                 tension: 0.00001,
            //                 borderWidth: 1
            //             },
            //             point: {
            //                 radius: 4,
            //                 hitRadius: 10,
            //                 hoverRadius: 4
            //             }
            //         }
            //     }
            // });
            //
            // usdBuyPrices = new Chart($('#usd-buy-prices'), {
            //     type: 'line',
            //     data: {
            //         datasets: [
            //             {
            //                 backgroundColor: 'rgba(255,255,255,.1)',
            //                 borderColor: '#58d93a',
            //                 borderWidth: 2,
            //                 pointRadius: 0,
            //                 data: vueObject.buyPrices
            //             }
            //         ]
            //     },
            //     options: {
            //         maintainAspectRatio: false,
            //         legend: {
            //             display: false
            //         },
            //         scales: {
            //             xAxes: [{
            //                 type: 'time'
            //             }]
            //         },
            //         elements: {
            //             line: {
            //                 tension: 0.00001,
            //                 borderWidth: 1
            //             },
            //             point: {
            //                 radius: 4,
            //                 hitRadius: 10,
            //                 hoverRadius: 4
            //             }
            //         }
            //     }
            // });

            //Dates
            // $('.analytic-datepicker').datepicker({
            //     format: "dd/mm/yyyy",
            //     weekStart: 1,
            //     maxViewMode: 2,
            //     todayBtn: "linked",
            //     language: "es",
            //     daysOfWeekHighlighted: "0,6",
            //     todayHighlight: true
            // });

            $.get('https://www.bitstamp.net/api/ticker/', function (data) {
                vueObject.bitstampPriceNow = Number.parseFloat(data.last);
            });

            let currentYear                     = new Date().getFullYear();
            let lastYear                        = currentYear - 1;
            let startDate                       = moment('2019-03-01');
            let endDate                         = moment();
            let yearStartDate                   = moment(currentYear + '-01-01');
            let yearEndDate                     = moment(currentYear + '-12-01');
            this.startDate                      = startDate.format('YYYY-MM-D HH:mm:ss');
            this.endDate                        = endDate.format('YYYY-MM-D HH:mm:ss');
            this.profitLossGraph.startDate      = startDate.format('YYYY-MM-D HH:mm:ss');
            this.profitLossGraph.endDate        = startDate.format('YYYY-MM-D HH:mm:ss');
            this.profitLossGraph.humanStartDate = startDate.format('D MMM YY');
            this.profitLossGraph.humanEndDate   = endDate.format('D MMM YY');
            this.noProfitGraph.startDate        = startDate.format('YYYY-MM-D HH:mm:ss');
            this.noProfitGraph.endDate          = startDate.format('YYYY-MM-D HH:mm:ss');
            this.noProfitGraph.humanStartDate   = startDate.format('D MMM YY');
            this.noProfitGraph.humanEndDate     = endDate.format('D MMM YY');
            this.expensesGraph.startDate        = startDate.format('YYYY-MM-D HH:mm:ss');
            this.expensesGraph.endDate          = startDate.format('YYYY-MM-D HH:mm:ss');
            this.expensesGraph.humanStartDate   = startDate.format('D MMM YY');
            this.expensesGraph.humanEndDate     = endDate.format('D MMM YY');
            this.salesSumGraph.startDate        = yearStartDate.format('YYYY-MM-D HH:mm:ss');
            this.salesSumGraph.endDate          = yearEndDate.format('YYYY-MM-D HH:mm:ss');
            this.salesSumGraph.humanStartDate   = yearStartDate.format('D MMM YY');
            this.salesSumGraph.humanEndDate     = yearEndDate.format('D MMM YY');

            function cb(start, end) {
                if (start !== vueObject.startDate || end !== vueObject.endDate) {
                    vueObject.changeTransactionsDate(start, end);
                }
            }

            $('#creation-date-filter').daterangepicker({
                opens: 'center',
                startDate: startDate,
                endDate: endDate,
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
            cb(startDate, endDate);

            function rangeProfitLossFunc(start, end) {
                if (start !== vueObject.profitLossGraph.startDate || end !== vueObject.profitLossGraph.endDate) {
                    vueObject.changeRangeProfitLoss(start, end);
                }
            }

            $('#daterange-g-profit').daterangepicker({
                opens: 'center',
                startDate: startDate,
                endDate: endDate,
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, rangeProfitLossFunc);
            rangeProfitLossFunc(startDate, endDate);

            function rangeNoProfitFunc(start, end) {
                if (start !== vueObject.noProfitGraph.startDate || end !== vueObject.noProfitGraph.endDate) {
                    vueObject.changeRangeNoProfit(start, end);
                }
            }

            $('#daterange-g-no-profit').daterangepicker({
                opens: 'center',
                startDate: startDate,
                endDate: endDate,
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, rangeNoProfitFunc);
            rangeNoProfitFunc(startDate, endDate);

            function rangeExpensesFunc(start, end) {
                if (start !== vueObject.expensesGraph.startDate || end !== vueObject.expensesGraph.endDate) {
                    vueObject.changeRangeExpenses(start, end);
                }
            }

            $('#daterange-g-expenses').daterangepicker({
                opens: 'center',
                startDate: startDate,
                endDate: endDate,
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, rangeExpensesFunc);
            rangeExpensesFunc(startDate, endDate);

            function salesSumFunc(start, end) {
                if (start !== vueObject.salesSumGraph.startDate || end !== vueObject.salesSumGraph.endDate) {
                    vueObject.changeRangeSalesSum(start, end);
                }
            }

            $('#daterange-g-ssum').daterangepicker({
                startDate: yearStartDate,
                endDate: yearEndDate,
                opens: 'left',
                showCustomRangeLabel: false,
                ranges: {
                    'Este año': [moment(currentYear + '-01-01'), moment(currentYear + '-12-01')],
                    'Año pasado': [moment(lastYear + '-01-01'), moment(lastYear + '-12-01')]
                }
            }, salesSumFunc);
            salesSumFunc(startDate, endDate);

            this.initWebsocket();
        })
    },
    methods: {
        calculateBtcSale() {
            let vueObject       = this,
                btcAmountToSell = $('#btc-amount-to-sell').val(),
                estimatedProfit = $('#estimated-profit-to-sell').val(),
                account         = $('#btc-account-selling').val();

            axios.get(window.location.origin + '/calculate-btc-sale', {
                params: {
                    btcAmountToSell: btcAmountToSell,
                    estimatedProfit: estimatedProfit,
                    account: account
                }
            }).then(re => {
                //console.log(re.data);
                if (re.data.error) {
                    $('.alert-no-btc').slideDown();
                    vueObject.fragmentsToSale   = [];
                    vueObject.suggestedSaleRate = 0;
                } else {
                    $('.alert-no-btc').slideUp();
                    vueObject.fragmentsToSale   = re.data.fragmentsToUse;
                    vueObject.suggestedSaleRate = re.data.suggestedRate;
                }
            })
        },
        getDate(date) {
            let newDate = new Date(date);

            return (newDate.getMonth() + 1)
                + '/' + newDate.getDate()
                + '/' + newDate.getFullYear()
                + ' - ' + (newDate.getHours() < 10 ? '0' : '') + newDate.getHours()
                + ':' + (newDate.getMinutes() < 10 ? '0' : '') + newDate.getMinutes();
        },
        formatMoney(value) {
            return numeral(value).format();
        },
        changeProfitLoss(domElement) {
            this.filterProfit = $(domElement.target).val();
            this.filterTransactions();
        },
        changeRangeProfitLoss(start, end) {
            // console.log(start.format('YYYY-MM-D HH:mm:ss'));
            // console.log(end.format('YYYY-MM-D HH:mm:ss'));
            this.profitLossGraph.startDate      = start.format('YYYY-MM-D HH:mm:ss');
            this.profitLossGraph.endDate        = end.format('YYYY-MM-D HH:mm:ss');
            this.profitLossGraph.humanStartDate = start.format('D MMM YY');
            this.profitLossGraph.humanEndDate   = end.format('D MMM YY');
            this.loader                         = true;

            axios.get(window.location.origin + '/filter-profit-range', {
                params: {
                    start: this.profitLossGraph.startDate,
                    end: this.profitLossGraph.endDate
                }
            }).then(re => {
                this.current_modal_transaction = {};
                this.loader                    = false;
                this.profitLossGraph.data      = re.data;

                pieChart.data.labels           = [
                    'Profit: ' + this.formatMoney(re.data[0]),
                    'Loss: ' + this.formatMoney(re.data[1])
                ];
                pieChart.data.datasets[0].data = re.data;
                pieChart.update();
            })
        },
        changeRangeNoProfit(start, end) {
            // console.log(start.format('YYYY-MM-D HH:mm:ss'));
            // console.log(end.format('YYYY-MM-D HH:mm:ss'));
            this.noProfitGraph.startDate      = start.format('YYYY-MM-D HH:mm:ss');
            this.noProfitGraph.endDate        = end.format('YYYY-MM-D HH:mm:ss');
            this.noProfitGraph.humanStartDate = start.format('D MMM YY');
            this.noProfitGraph.humanEndDate   = end.format('D MMM YY');
            this.loader                       = true;

            axios.get(window.location.origin + '/filter-no-profit-range', {
                params: {
                    start: this.noProfitGraph.startDate,
                    end: this.noProfitGraph.endDate
                }
            }).then(re => {
                this.current_modal_transaction = {};
                this.loader                    = false;
                this.noProfitGraph.data        = re.data;

                noProfitPie.data.labels           = [
                    'Employee\'s Salary: ' + this.formatMoney(re.data[0]),
                    'Operational Expenses: ' + this.formatMoney(re.data[1]),
                    'Regular Payments: ' + this.formatMoney(re.data[2]),
                    'Internal Inventory Movement: ' + this.formatMoney(re.data[3]),
                    'Other Expenses: ' + this.formatMoney(re.data[4])
                ];
                noProfitPie.data.datasets[0].data = re.data;
                noProfitPie.update();
            })
        },
        changeRangeExpenses(start, end) {
            // console.log(start.format('YYYY-MM-D HH:mm:ss'));
            // console.log(end.format('YYYY-MM-D HH:mm:ss'));
            this.expensesGraph.startDate      = start.format('YYYY-MM-D HH:mm:ss');
            this.expensesGraph.endDate        = end.format('YYYY-MM-D HH:mm:ss');
            this.expensesGraph.humanStartDate = start.format('D MMM YY');
            this.expensesGraph.humanEndDate   = end.format('D MMM YY');
            this.loader                       = true;

            axios.get(window.location.origin + '/filter-expenses-range', {
                params: {
                    start: this.expensesGraph.startDate,
                    end: this.expensesGraph.endDate
                }
            }).then(re => {
                this.current_modal_transaction = {};
                this.loader                    = false;
                this.expensesGraph.data        = re.data;

                expensesChart.data.datasets[0].data = re.data;
                expensesChart.update();
            })
        },
        changeRangeSalesSum(start, end) {
            // console.log(start.format('YYYY-MM-D HH:mm:ss'));
            // console.log(end.format('YYYY-MM-D HH:mm:ss'));
            this.salesSumGraph.startDate      = start.format('YYYY-MM-D HH:mm:ss');
            this.salesSumGraph.endDate        = end.format('YYYY-MM-D HH:mm:ss');
            this.salesSumGraph.humanStartDate = start.format('D MMM YY');
            this.salesSumGraph.humanEndDate   = end.format('D MMM YY');
            this.loader                       = true;

            axios.get(window.location.origin + '/filter-ssum-range', {
                params: {
                    start: this.salesSumGraph.startDate,
                    end: this.salesSumGraph.endDate
                }
            }).then(re => {
                this.current_modal_transaction = {};
                this.loader                    = false;
                this.salesSumGraph.data        = re.data;

                salesSum.data.datasets[0].data = re.data;
                salesSum.update();
            })
        },
        changeTransactionsDate(start, end) {
            // console.log(start.format('YYYY-MM-D HH:mm:ss'));
            // console.log(end.format('YYYY-MM-D HH:mm:ss'));
            this.startDate = start.format('YYYY-MM-D HH:mm:ss');
            this.endDate   = end.format('YYYY-MM-D HH:mm:ss');

            this.filterTransactions();
        },
        selectTransactionCurrency(currency) {
            this.transaction_currency = currency;

            if (currency === 'VES') {
                this.ves   = true;
                this.usd   = false;
                this.all_c = false;
            } else if (currency === 'USD') {
                this.ves   = false;
                this.usd   = true;
                this.all_c = false;
            } else {
                this.ves   = false;
                this.usd   = false;
                this.all_c = true;
            }

            this.filterTransactions();
        },
        selectTransactionType(type) {
            this.transaction_type = type;

            if (type === 'Outgoing') {
                this.outgoing = true;
                this.incoming = false;
                this.all_t    = false;
            } else if (type === 'Incoming') {
                this.outgoing = false;
                this.incoming = true;
                this.all_t    = false;
            } else {
                this.outgoing = false;
                this.incoming = false;
                this.all_t    = true;
            }

            this.filterTransactions();
        },
        filterTransactions() {
            this.loader = true;
            axios.get(window.location.origin + '/filter-transactions', {
                params: {
                    currency: this.transaction_currency,
                    type: this.transaction_type,
                    start: this.startDate,
                    end: this.endDate,
                    filterProfit: this.filterProfit
                }
            }).then(re => {
                this.current_modal_transaction = {};
                this.transactions              = re.data;
                this.loader                    = false;
            })
        },
        transactionsPagination(page) {
            this.loader = true;
            axios.get(window.location.origin + '/transactions-pagination', {
                params: {
                    page: page,
                    currency: this.transaction_currency,
                    type: this.transaction_type,
                    start: this.startDate,
                    end: this.endDate,
                    filterProfit: this.filterProfit
                }
            }).then(re => {
                //Vue.set(this, transactions, re.data);
                let fullListTransactions = this.transactions.data;
                fullListTransactions     = fullListTransactions.concat(re.data.data);
                this.transactions        = re.data;
                this.transactions.data   = fullListTransactions;
                this.loader              = false;
            })
        },
        getUrl(endpoint) {
            return window.location.origin + endpoint;
        },
        openModal(data, price, transaction) {
            this.current_modal_transaction = transaction;
            this.modal_visibility          = true;
            this.modal_contact_id          = data.contact_id;
            this.modal_close_date          = this.getDate(data.closed_at);
            this.modal_trade_type          = data.advertisement.trade_type;
            this.modal_partner             = data.seller.name;
            this.modal_amount              = data.amount;
            this.modal_volume              = data.amount_btc;
            this.modal_currency            = data.currency;
            this.modal_price               = (Math.round((data.amount / data.amount_btc) * 100) / 100).toLocaleString('en');
            this.modal_usd_price           = price;
            this.profit                    = false;
        },
        openModal2(data, price, transaction_id, transaction) {
            this.current_modal_transaction = transaction;
            this.modal_visibility          = true;
            this.modal_contact_id          = data.contact_id;
            this.modal_close_date          = this.getDate(data.closed_at);
            this.modal_trade_type          = data.advertisement.trade_type;
            this.modal_partner             = data.seller.name;
            this.modal_amount              = data.amount;
            this.modal_volume              = data.amount_btc;
            this.modal_currency            = data.currency;
            this.modal_price               = (Math.round((data.amount / data.amount_btc) * 100) / 100).toLocaleString('en');
            this.modal_usd_price           = price;

            axios.get(window.location.origin + '/transactions-outgoings/' + transaction_id, {}).then(re => {
                this.modal_profit = re.data;
                this.profit       = true;
            })

        },
        initWebsocket() {
            let vueObject    = this,
                subscribeMsg = {
                    "event": "bts:subscribe",
                    "data": {
                        "channel": "live_trades_btcusd"
                    }
                },
                ws           = new WebSocket("wss://ws.bitstamp.net");

            ws.onopen = function () {
                ws.send(JSON.stringify(subscribeMsg));
            };

            ws.onmessage = function (evt) {
                let response = JSON.parse(evt.data);
                /**
                 * This switch statement handles message logic. It processes data in case of trade event
                 * and it reconnects if the server requires.
                 */
                switch (response.event) {
                    case 'trade': {
                        vueObject.bitstampPriceNow = response.data.price;
                        break;
                    }
                    case 'bts:request_reconnect': {
                        vueObject.initWebsocket();
                        break;
                    }
                }

            };
            /**
             * In case of unexpected close event, try to reconnect.
             */
            ws.onclose = function () {
                console.log('Websocket connection closed');
                vueObject.initWebsocket();
            };
        }
    }
}
