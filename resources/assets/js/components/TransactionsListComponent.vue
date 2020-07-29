<template lang="html">
  <div class="">
    <section class="section-head-1">
        <div class="grid-container">
            <div class="grid-x">
                <div class="cell text-center">
                    <div class="content">
                        <img src="img/icons/transactions-icon.png" alt="Transactions">
                        <h3>Transactions</h3>
                        <p>Income and Outcome transactions</p>
                    </div>
                </div>
            </div>
            <div class="grid-x">
                <div class="cell large-8 large-offset-2 filtering-form">
                    <form action="#!" method="get">
                        <div class="grid-x t-types">
                            <div class="cell buttons">
                                <button class="w-icon" v-on:click="selectTransactionType('Incoming')" :class="{ active: incoming }" name="tType" type="button" value="income">
                                    <img src="img/icons/incoming-icon.png" alt="Incoming transactions">
                                    Incoming transactions
                                </button>
                                <button class="no-icon active" v-on:click="selectTransactionType('all')" name="tType" type="button" value="all">
                                    All
                                </button>
                                <button class="w-icon" v-on:click="selectTransactionType('Outgoing')"  :class="{ active: outgoing }" name="tType" type="button" value="income">
                                    Outgoing transactions
                                    <img src="img/icons/outgoing-icon.png" alt="Outgoing transactions">
                                </button>
                            </div>
                            <div class="cell currencies">
                                <button name="currency" v-on:click="selectTransactionCurrency('VES')" :class="{ active: ves }" type="button" value="ves">
                                    Bolívares
                                </button>
                                <button name="currency" v-on:click="selectTransactionCurrency('USD')" :class="{ active: usd }" type="button" value="usd">
                                    Dollars
                                </button>
                                <button v-on:click="selectTransactionCurrency('all')" :class="{ active: all_c }" name="currency" type="button" value="all">
                                    All
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="operations-list">
        <div class="grid-container">
            <div class="grid-x">
                <div class="cell text-right">
                    <a :href="getUrl('/create-transaction')" class="button">
                        Create transaction
                    </a>
                    <a :href="getUrl('/app')" class="alert button">
                        Return
                    </a>
                </div>
            </div>
            <div class="grid-x">
                <div class="cell">
                    <div class="content-table">
                        <table>
                          <tbody>
                            <tr class="head">
                                <td></td>
                                <td>Amount</td>
                                <td>Currency</td>
                                <td>Transaction ID</td>
                                <td>Bank</td>
                                <td></td>
                            </tr>
                            <tr v-for="transaction in transactions.data" class="body">
                                <td v-if="transaction.type == 'Incoming'">
                                    <a href="#!">
                                        <img src="img/icons/incoming-icon.png" alt="Incoming transactions">
                                        Incoming
                                    </a>
                                </td>
                                <td v-else>
                                    <a href="#!" v-on:click="openModal(transaction.json_data.data, transaction.usd_price)">
                                        <img src="img/icons/outgoing-icon.png" alt="Outgoing transactions">
                                        Outgoing
                                    </a>
                                </td>
                                <td><span class="money">{{transaction.amount.toLocaleString('en')}}</span></td>
                                <td>{{transaction.currency}}</td>
                                <td><a :href="getUrl('/edit/' + transaction.id)">{{transaction.transaction_id}}</a></td>
                                <td>{{transaction.bank_name}}</td>
                                <td>{{getDate(transaction.released_date)}}</td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="grid-x">
                <div class="cell">
                    <nav aria-label="Pagination">
                        <ul class="pagination text-center">
                            <li v-if="transactions.current_page == 1" class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>
                            <li v-else class="pagination-previous"><a href="#!" v-on:click="transactionsPagination(transactions.current_page - 1)" aria-label="Previous page">Previous <span
                                    class="show-for-sr">page</span></a></li>
                            <li v-if="transactions.current_page != transactions.last_page" class="pagination-next"><a href="#!" v-on:click="transactionsPagination(transactions.current_page + 1)" aria-label="Next page">Next <span
                                    class="show-for-sr">page</span></a></li>
                            <li v-else class="pagination-next disabled">Next <span class="show-for-sr">page</span></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="grid-x">
              <div class="cell text-right">
                  <a :href="getUrl('/create-transaction')" class="button">
                      Create transaction
                  </a>
                  <a :href="getUrl('/app')" class="alert button">
                      Return
                  </a>
              </div>
            </div>
        </div>
    </section>

    <!-- Transactions Modal -->
    <div v-if="modal_visibility" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <span v-on:click="closeModal" class="close">&times;</span>
        <p>Contact ID: {{modal_contact_id}}</p>
        <p>Closed at: {{modal_close_date}}</p>
        <p>Trade type: {{modal_trade_type}}</p>
        <p>Transaction partner: {{modal_partner}}</p>
        <p>Transasction status: Bitcoins released</p>
        <p>Amount: {{modal_amount}} {{modal_currency}}</p>
        <p>BTC Volume: {{modal_volume}} BTC</p>
        <p>Price: {{modal_price}} {{modal_currency}}/BTC</p>
        <p v-if="modal_currency == 'VES'">USD Price: {{modal_usd_price}}</p>
      </div>

    </div>

  </div>
</template>

<script>
export default {

  props: ['transactions2'],

  data() {
    return {
      transactions: this.transactions2,
      outgoing:false,
      incoming: false,
      all_t: true,
      ves: false,
      usd: false,
      all_c:true,
      transaction_currency: 'all',
      transaction_type: 'all',
      modal_visibility:false,
      modal_contact_id: '',
      modal_close_date: '',
      modal_trade_type: '',
      modal_partner: '',
      modal_amount: '',
      modal_volume: '',
      modal_currency: '',
      modal_price: '',
      modal_usd_price: '',
      modal_profit: ''
    }
  },

  methods: {
    getDate(date){
      var newDate = new Date(date);
      return (newDate.getMonth() + 1) + '/' + newDate.getDate() + '/' + newDate.getFullYear();
    },

    selectTransactionType(type){
      this.transaction_type = type;

      if (type == 'Outgoing') {
        this.outgoing = true;
        this.incoming = false;
        this.all_t = false;
      } else if (type == 'Incoming') {
        this.outgoing = false;
        this.incoming = true;
        this.all_t = false;
      } else {
        this.outgoing = false;
        this.incoming = false;
        this.all_t = true;
      }

      this.filterTransactions();

    },

    selectTransactionCurrency(currency){
      this.transaction_currency = currency;

      if (currency == 'VES') {
        this.ves = true;
        this.usd = false;
        this.all_c = false;
      } else if (currency == 'USD') {
        this.ves = false;
        this.usd = true;
        this.all_c = false;
      } else {
        this.ves = false;
        this.usd = false;
        this.all_c = true;
      }

      this.filterTransactions();

    },

    filterTransactions(){
      axios.get(window.location.origin+'/filter-transactions', {
        params: {
          currency: this.transaction_currency,
          type: this.transaction_type
        }
      }).then(re => {
        this.transactions = re.data;
      })
    },

    transactionsPagination(page){
      axios.get(window.location.origin+'/transactions-pagination', {
        params: {
          page: page,
          currency: this.transaction_currency,
          type: this.transaction_type
        }
      }).then(re => {
        this.transactions = re.data;
      })
    },

    getUrl(endpoint){
      return window.location.origin+endpoint;
    },

    closeModal(){
      this.modal_visibility = false;
    },

    openModal(data, price){
      this.modal_visibility = true;
      console.log(data);

      this.modal_contact_id = data.contact_id;
      this.modal_close_date = this.getDate(data.closed_at);
      this.modal_trade_type = data.advertisement.trade_type;
      this.modal_partner = data.seller.name;
      this.modal_amount = data.amount;
      this.modal_volume = data.amount_btc;
      this.modal_currency = data.currency;
      this.modal_price = (Math.round((data.amount / data.amount_btc) * 100) / 100).toLocaleString('en');
      this.modal_usd_price = price;
    }

  }

}
</script>

<style lang="css">
</style>
