<template lang="html">

    <div class="container">
        <vue-toastr ref="toastr"></vue-toastr>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group d-block mr-3">
                     <div class="row">
                          <div class="col-md-4">
                              <input type="text" name="" v-model="transaction_id" class="form-control"
                                     placeholder="Search by ID" value="">
                          </div>
                          <div class="col-md-1">
                              <button type="button" class="btn btn-secondary btn-pill px-md-3" v-on:click="searchByID"
                                      name="button">Search
                              </button>
                          </div>
                      </div>
                      <hr>
                    <div class="row">
                         <div class="col-md-3">
                              <div class="form-group d-block">
                                  <label for="sender-select" class="mb-1">Sender</label>
                                  <select name="" v-model="sender" id="sender-select"
                                          class="custom-select flag-selector flag-selector--full">
                                      <option value="All">All Currrencies</option>
                                      <optgroup label="Latino América">
                                          <option value="CLP" data-flag="img/landing/flags/cl.svg">
                                              Chile
                                          </option>
                                          <option value="VES" data-flag="img/landing/flags/ve.svg">
                                              Venezuela
                                          </option>
                                          <option value="ARS" data-flag="img/landing/flags/ar.svg">
                                              Argentina
                                          </option>
                                          <option value="COP" data-flag="img/landing/flags/co.svg">
                                              Colombia
                                          </option>
                                          <option value="PEN" data-flag="img/landing/flags/pe.svg">
                                              Perú
                                          </option>
                                          <option value="MXN" data-flag="img/landing/flags/mx.svg">
                                              Mexico
                                          </option>
                                          <option value="DOP" data-flag="img/landing/flags/do.svg">
                                              República Dominicana
                                          </option>
                                          <option value="CRC" data-flag="img/landing/flags/cr.svg">
                                              Costa Rica
                                          </option>
                                          <option value="PAB" data-flag="img/landing/flags/pa.svg">
                                              Panama
                                          </option>
                                      </optgroup>
                                      <optgroup label="Otros">
                                          <option value="USD" data-flag="img/landing/flags/us.svg"
                                                  selected>United States
                                          </option>
                                          <option value="CAD" data-flag="img/landing/flags/ca.svg">
                                              Canada
                                          </option>
                                          <option value="EUR" data-flag="img/landing/flags/es.svg">
                                              España
                                          </option>
                                          <option value="EUR" data-flag="img/landing/flags/pt.svg">
                                              Portugal
                                          </option>
                                          <option value="EUR" data-flag="img/landing/flags/it.svg">
                                              Italia
                                          </option>
                                          <option value="EUR" data-flag="img/landing/flags/fr.svg">
                                              Francia
                                          </option>
                                          <option value="GBP" data-flag="img/landing/flags/gb.svg">
                                              Reino Unido
                                          </option>
                                      </optgroup>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-3">
                              <div class="form-group d-block">
                                  <label for="receiver-select" class="mb-1">Receiver</label>
                                  <select name="" v-model="receiver" id="receiver-select"
                                          class="custom-select flag-selector flag-selector--full">
                                      <option value="All">All Currrencies</option>
                                      <optgroup label="Latino América">
                                          <option value="CLP" data-flag="img/landing/flags/cl.svg">
                                              Chile
                                          </option>
                                          <option value="VES" data-flag="img/landing/flags/ve.svg">
                                              Venezuela
                                          </option>
                                          <option value="ARS" data-flag="img/landing/flags/ar.svg">
                                              Argentina
                                          </option>
                                          <option value="COP" data-flag="img/landing/flags/co.svg">
                                              Colombia
                                          </option>
                                          <option value="PEN" data-flag="img/landing/flags/pe.svg">Perú</option>
                                      </optgroup>
                                      <optgroup label="Otros">
                                          <option value="USD" data-flag="img/landing/flags/us.svg">United States
                                          </option>
                                          <option value="EUR" data-flag="img/landing/flags/es.svg">
                                              España
                                          </option>
                                      </optgroup>
                                  </select>
                              </div>
                          </div>
                        <div class="col-md-3">
                            <label for="user_name" class="mb-1">User Name</label>
                            <input name="user_name"
                                   id="user_name"
                                   v-model="user_name"
                                   class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label for="user_email" class="mb-1">User Email</label>
                            <input name="user_email"
                                   id="user_email"
                                   v-model="user_email"
                                   class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label for="creation-date-filter" class="mb-1">Date</label>
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
                        <div class="col-md-3">
                            <div class="form-group d-block">
                                <label for="transactions-select" class="mb-1">Status</label>
                                <select name="" v-model="transactions_status" id="transactions-select"
                                        class="custom-select">
                                    <option value="All">All Status</option>
                                    <option value="1" selected>Waiting</option>
                                    <option value="2">Approved</option>
                                    <option value="3">Failed</option>
                                    <option value="4">Rejected</option>
                                    <option value="6">In Process</option>
                                    <option value="5">Refund</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" v-on:click="filterHander()"
                                    class="btn btn-secondary btn-pill px-md-3" style="margin-top:38%" name="button">
                                Filter
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-secondary btn-pill px-md-3" style="margin-top:15%"
                                    v-on:click="orderByTrader"
                                    name="button">Order by Trader
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card loader--wrapper" :class="{ '--loading': loader }">
                    <table class="table table-striped table-borderless mb-0">
                        <thead>
                        <tr>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Trader</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Nombre</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Monto Pagado</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Monto Cargado</h5>
                            </th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Payment Method</h5>
                            </th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Status</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Date</h5></th>
                            <!-- <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Actions</h5></th> -->
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="transaction in transactions.data"
                            :class="transaction.status === 1 || transaction.status === 4 || transaction.status === 6 ? transaction.payment_way : ''">
                            <th class="text-center" style="vertical-align:middle">
                                <a class="d-block"
                                   href="#!"
                                   v-if="!transaction.trader_id"
                                   v-on:click="assignExchange(transaction.id)">
                                    <img src="/img/icons/attended.svg" class="img-fluid __reclaim_image" alt="Atender"
                                         style="height: 38px">
                                    <br>
                                    <span style="font-size:10px;color:red">unassigned</span>
                                </a>
                                <p v-else style="color:green;font-size:15px">
                                    <a :href="getEndpoint('/trader-details/' + transaction.trader_id)">
                                        {{transaction.trader_info.name}}
                                    </a>
                                </p>
                            </th>
                            <th class="text-center" style="vertical-align:middle">
                                <a :href="getEndpoint('/user-profile/' + transaction.user_account.id)"
                                    target="_blank">
                                    {{transaction.user_account.name}}
                                </a>
                            </th>
                            <td class="text-center" style="vertical-align:middle">
                                {{transaction.sender_fiat}}
                                {{parseFloat(transaction.sender_fiat_amount).toLocaleString('en')}}
                            </td>
                            <td class="text-center" style="vertical-align:middle">
                                {{transaction.receiver_fiat}}
                                {{parseFloat(transaction.receiver_fiat_amount).toLocaleString('en')}}
                            </td>
                            <td style="vertical-align:middle" class="text-center __payment_data_place">
                                {{getPaymentMethod(transaction.payment_way)}}
                            </td>
                            <td class="text-center" style="vertical-align:middle"
                                :class="getStatusColor(transaction.status)" :id="'status-' + transaction.id">
                                {{getStatus(transaction.status)}}

                                <span v-if="transaction.payment_support !== null" class="badge badge-success">Soporte Cargado</span>
                            </td>
                            <td class="text-center" style="vertical-align:middle">{{getDate(transaction.created_at)}}
                            </td>
                            <!--  <td v-if="(transaction.is_payed === 1 && transaction.payed_at) && transaction.status === 0"
                                  class="text-center"
                                  style="vertical-align:middle">
                                  <select id="" :id="'select'+transaction.id" v-on:change="changeStatus(transaction.id)"
                                          class="custom-select">
                                      <option value="1">Approve</option>
                                      <option value="2">Reject</option>
                                      <option value="" selected disabled>Action</option>
                                  </select>
                              </td>
                              <td v-if="transaction.status === 1" class="text-center" style="vertical-align:middle">
                                  Completed
                              </td>
                              <td v-if="transaction.status === 2" class="text-center" style="vertical-align:middle">
                                  Rejected
                              </td>
                              <td v-if="transaction.status === 3" class="text-center" style="vertical-align:middle">
                                  Failed
                              </td>
                              <td v-if="transaction.status === 5" class="text-center" style="vertical-align:middle">
                                  Refunded
                              </td>
                              <td v-if="transaction.is_payed !== 1 && transaction.status === 0" class="text-center"
                                  style="vertical-align:middle">
                                  Not paid yet
                              </td> -->
                            <td class="text-center" style="vertical-align:middle">
                                <a target="_blank" :href="getUrl(transaction.id)">View Transaction</a>
                                <br>
                                <span style="font-size: 14px">{{transaction.tracking_id}}</span>
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
                <div v-if="transactions.current_page" class="d-flex justify-content-center mt-3">
                    <a v-if="transactions.current_page != 1" class="page-link px-3" href="javascript:void(0);"
                       aria-label="Load more"
                       style="width: unset; height: unset; border-radius: 10rem !important;"
                       v-on:click="transactionsPagination(transactions.current_page - 1)">
                        <span aria-hidden="true">Prev</span>
                    </a>
                    <a v-if="transactions.current_page != transactions.last_page" class="page-link px-3"
                       href="javascript:void(0);" aria-label="Load more"
                       style="width: unset; height: unset; border-radius: 10rem !important;"
                       v-on:click="transactionsPagination(transactions.current_page + 1)">
                        <span aria-hidden="true">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
  props: ["transactions_init_value", "purpose"],

  data() {
    return {
      transactions: this.transactions_init_value,
      loader: false,
      transaction_id: "",
      receiver: "All",
      sender: "All",
      transactions_status: "All",
      user_name: null,
      user_email: null,
      startDate: null,
      endDate: null,
      order_by_trader: 0,
      purpose: this.purpose
    };
  },

  methods: {
    getStatus(id) {
      if (id == 1) {
        return "Waiting";
      } else if (id == 2) {
        return "Completed";
      } else if (id == 3) {
        return "Failed";
      } else if (id == 4) {
        return "Rejected";
      } else if (id == 5) {
        return "Refund";
      } else if (id == 6) {
        return "In Process";
      } else {
        return "Waiting";
      }
    },

    orderByTrader() {
      this.order_by_trader = 1;
      this.sender = "All";
      this.receiver = "All";
      this.transactions_status = 0;
      this.user_name = null;
      this.user_email = null;
      this.loader = true;
      this.transactionsPagination(1);
    },

    filterHander() {
      this.order_by_trader = 0;
      this.transactionsPagination(1);
    },

    searchByID() {
      this.loader = true;
      axios
        .get(window.location.origin + "/search-transaction", {
          params: {
            trans_id: this.transaction_id,
            purpose_id: this.purpose,
            _token: $('meta[name="csrf-token"]').attr("content")
          }
        })
        .then(re => {
          if (re.data.error) {
            this.loader = false;
            this.$refs.toastr.e("Transaction not found.");
            return;
          }

          this.loader = false;
          this.transactions = re.data;
        });
    },

    assignExchange(id) {
      this.loader = true;
      axios
        .post(window.location.origin + "/wallets/assign-transaction/" + id)
        .then(re => {
          if (re.data.status === "error") {
            this.$refs.toastr.e(re.data.msg);
          } else {
            this.$refs.toastr.s(re.data.msg);
          }
          this.transactionsPagination(transactions.current_page);
          this.loader = false;
        });
    },

    getPaymentMethod(meth) {
      let paymentMethods = {
        cash: "Pago en Efectivo",
        Stripe: "Tarjeta de Crédito o Débito",
        QuickBook: "Tarjeta de Crédito o Débito QB",
        zelle: "Zelle",
        venmo: "Venmo",
        cashapp: "Cash App",
        payoneer: "Payoneer",
        popmoney: "PopMoney",
        pandco: "Pandco",
        userWallet: "Billetera AKB",
        amaz_prepaid: "Tarjeta VISA pre. Amazon",
        ath_prepaid: "Tarjeta Gift Card American Time Holding"
      };

      return paymentMethods[meth];
    },

    getDate(date) {
      date = date.replace(" ", "T");
      date = date + "Z";
      let newDate = new Date(date);

      return (
        newDate.getMonth() +
        1 +
        "/" +
        newDate.getDate() +
        "/" +
        newDate.getFullYear() +
        " - " +
        (newDate.getHours() < 10 ? "0" : "") +
        newDate.getHours() +
        ":" +
        (newDate.getMinutes() < 10 ? "0" : "") +
        newDate.getMinutes()
      );
    },

    getStatusColor(id) {
      if (id == 1) {
        return "text-warning";
      } else if (id == 2) {
        return "text-success";
      } else if (id == 3) {
        return "text-warning";
      } else if (id == 4) {
        return "text-danger";
      } else {
        return "text-info";
      }
    },

    changeStatus(id) {
      let sel = $("#select" + id).val();
      this.loader = true;
      axios
        .post(window.location.origin + "/change-status/" + id, {
          params: {
            status: sel,
            _token: $('meta[name="csrf-token"]').attr("content")
          }
        })
        .then(re => {
          this.transactionsPagination(this.transactions.current_page);
        });
    },

    transactionsPagination(page) {
      this.loader = true;
      axios
        .get(window.location.origin + "/api/wallets/transactions-pagination", {
          params: {
            page: page,
            receiver: this.receiver,
            sender: this.sender,
            transactions_status: this.transactions_status,
            purpose: this.purpose,
            user_name: this.user_name,
            user_email: this.user_email,
            start_date: this.startDate,
            end_date: this.endDate
          }
        })
        .then(re => {
          this.transactions = re.data;
          this.loader = false;
        });
    },

    getUrl(id) {
      return window.location.origin + "/wallets/transaction-details/" + id;
    },

    getEndpoint(endpoint) {
      return window.location.origin + endpoint;
    },

    subscribe() {
      let pusher = new Pusher("889fce6a69a9c7050bd3", { cluster: "us2" });
      pusher.subscribe("wallets-channel");
      pusher.bind("transaction-order", data => {
        if (this.transactions.current_page == 1) {
          this.transactionsPagination(1);
        }
      });
    },
    
    changeFilterDate(start, end) {
        this.startDate = start.format('YYYY-MM-D HH:mm:ss');
        this.endDate   = end.format('YYYY-MM-D HH:mm:ss');
    
    }
  },

  created() {
    this.subscribe();
  },

  mounted() {
    this.$nextTick(function() {
      // let vueObject = this;
      //
      // $.each(this.walletsObject, function (index, value) {
      //     if (value.currency === 'USD') {
      //         vueObject.activeWallet = value;
      //     }
      // });
      let vueObject = this,
        startDate = moment().startOf("month"),
        endDate = moment().utc();
      this.startDate = startDate.format("YYYY-MM-D HH:mm:ss");
      this.endDate = endDate.format("YYYY-MM-D HH:mm:ss");

      function cb(start, end) {
        if (start !== vueObject.startDate || end !== vueObject.endDate) {
          vueObject.changeFilterDate(start, end);
        }
      }

      $("#creation-date-filter").daterangepicker(
        {
          opens: "center",
          startDate: startDate,
          endDate: endDate
        },
        cb
      );

      cb(startDate, endDate);

      //this.filterTransactions();
    });
  }
};
</script>

<style lang="scss">
tr {
  &.cash {
    border: 1px solid #d35f5f;
    border-left: 3px solid #d35f5f;

    .__payment_data_place {
      background: #d35f5f;
      color: #fff;
    }
  }

  &.zelle,
  &.venmo,
  &.cashapp,
  &.payoneer,
  &.popmoney,
  &.pandco,
  &.amaz_prepaid {
    border: 1px solid #ffdd55;
    border-left: 3px solid #ffdd55;

    .__payment_data_place {
      background: #ffdd55;
      color: #333;
    }
  }

  &.Stripe,
  &.QuickBook,
  &.userWallet {
    border: 1px solid #8dd35f;
    border-left: 3px solid #8dd35f;

    .__payment_data_place {
      background: #8dd35f;
      color: #333;
    }
  }
}
</style>
