import Slick from 'vue-slick';
import Cleave from 'vue-cleave-component';

// let stripe   = Stripe('pk_live_CZ8X5RBZPTMGxoDYHGf7NiFb00mklv9zEw'),
let stripe   = Stripe('pk_test_M1JMPByVLXxtLYnWwRDP8Gou00MJea1o0Y'),
    elements = stripe.elements(),
    card     = undefined;

export default {
    name: 'slick-slider',
    components: {
        Slick,
        Cleave
    },
    props: {
        transTracking: String,
        user: Object,
        sender_country: String,
        no_viable_country: Boolean,
        forbidden_chat: Number,
        force_loading: Boolean,
        on_wallets: Boolean,
        qbpay: String
    },
    data() {
        return {
            slickOptions: {
                dots: true,
                infinite: true,
                arrows: false,
                speed: 300,
                autoplay: false,
                slidesToShow: 3,
                slidesToScroll: 3,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    }
                ]
            },
            cards: [],
            pay_method: '',
            pay_method_country: '',
            selected_payment_method: '',
            loader: true,
            card_name: null,
            card_number: null,
            card_month: null,
            card_year: null,
            card_cvv: null,
            card_options: {
                creditCard: true,
                delimiter: ' '
            }
        };
    },

    methods: {
        selectCard(card) {
            this.pay_method         = card.id;
            this.pay_method_country = card.country;
            this.$emit('updatePaymentMethod', ['card', card]);
        },
        gettingCards() {
            this.loader = true;
            axios.get(window.location.origin + '/api/getting-user-cards/'
            )
                .then(re => {
                    this.cards  = re.data;
                    this.loader = false;
                })
        },
        addCard() {
            let alerta    = this.$refs.toastr;
            let vueObject = this;
            this.loader   = true;
            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    alerta.e(result.error.message);
                    vueObject.loader = false;
                    return;
                }

                axios.post(window.location.origin + '/api/create-payment-method/', {
                    params: {
                        stripe_token: result.token.id,
                        _token: $('meta[name="csrf-token"]').attr("content")
                    }
                }).then(re => {
                    if (re.data['error']) {
                        alerta.e(re.data['error']);
                        vueObject.loader = false;
                    } else {
                        alerta.s('Your payment method has been created.');
                        vueObject.cards  = re.data;
                        vueObject.loader = false;

                        $('.card--overlaid').removeClass('--show');

                        let lastKey = vueObject.cards.length - 1;
                        if (vueObject.cards[lastKey]) {
                            vueObject.pay_method         = vueObject.cards[lastKey].id;
                            vueObject.pay_method_country = vueObject.cards[lastKey].country;
                            vueObject.$emit('updatePaymentMethod', ['card', vueObject.cards[lastKey]]);
                        }
                    }
                })
            });
        },
        deleteCard(id) {
            let alerta    = this.$refs.toastr;
            let vueObject = this;
            this.loader   = true;

            $.confirm({
                title: '¿Está seguro(a)?',
                content: '¿Quiere borrar ésta tarjeta?',
                theme: 'bootstrap',
                buttons: {
                    ok: {
                        text: "Si",
                        keys: ['enter'],
                        action: function () {
                            axios.post(window.location.origin + '/api/delete-card/', {
                                id: id,
                                _token: $('meta[name="csrf-token"]').attr("content")
                            }).then(re => {
                                if (re.data['error']) {
                                    alerta.e(re.data['error']);
                                    vueObject.loader = false;
                                } else {
                                    alerta.s('The card has been deleted.');
                                    vueObject.cards  = re.data;
                                    vueObject.loader = false;
                                }
                            });
                        }
                    },
                    cancel: {
                        text: "No",
                        action: function () {
                            return true;
                        }
                    }
                }
            });
        },
        selectCash() {
            $('#payment-wrapper-row-cards').removeClass('--active');
            $('#payment-wrapper-row-prepaid').removeClass('--active');
            this.pay_method              = 'cash';
            this.selected_payment_method = 'cash';
            this.$emit('updatePaymentMethod', 'cash');
        },
        seeMethods() {
            if (window.matchMedia("(max-width: 640px)").matches) {
                let topOriginal = $('#payment-method').offset().top;
                $("html, body").stop().animate({scrollTop: topOriginal}, 500, 'swing');
            }
        },
        smallScroll() {
            if (window.matchMedia("(max-width: 640px)").matches) {
                let topOriginal = $('#payment-wrapper-row-2').offset().top;
                $("html, body").stop().animate({scrollTop: topOriginal}, 500, 'swing');
            }
        },
        showCardOptions($cardType) {
            this.selected_payment_method = $cardType;
            this.pay_method              = '';

            if (this.qbpay !== '1') {
                let currIndex = this.$refs.slick.currentSlide();
                this.$refs.slick.destroy();
                this.$nextTick(() => {
                    this.$refs.slick.create();
                    this.$refs.slick.goTo(currIndex, true);
                });

                if (this.cards[0]) {
                    let card                = this.cards[0];
                    this.pay_method         = card.id;
                    this.pay_method_country = card.country;
                    this.$emit('updatePaymentMethod', ['card', card]);
                }
            }

            $('#payment-wrapper-row-1').removeClass('--active');
            $('#payment-wrapper-row-cards').addClass('--active');
        },
        verifyQbFields() {
            if (
                this.card_name !== null && this.card_name !== '' &&
                this.card_number !== null && this.card_number !== '' &&
                this.card_month !== null && this.card_month !== '' &&
                this.card_year !== null && this.card_year !== '' &&
                this.card_cvv !== null && this.card_cvv !== ''
            ) {
                let card                = {id: 'card_qb_noID', country: 'US'};
                this.pay_method         = card.id;
                this.pay_method_country = card.country;
                this.$emit('updatePaymentMethod', ['card', card]);
            } else {
                this.$emit('updatePaymentMethod', 'not_completed');
                return false;
            }
        },
        showCheckOptions() {
            this.selected_payment_method = 'check';
            this.pay_method              = 'check';
            this.$emit('updatePaymentMethod', 'check');

            $('#payment-wrapper-row-cards').removeClass('--active');
            $('#payment-wrapper-row-prepaid').removeClass('--active');
            $('#payment-wrapper-row-1').addClass('--active');
        },
        showVenmoOptions() {
            this.selected_payment_method = 'venmo';
            this.pay_method              = 'venmo';
            this.$emit('updatePaymentMethod', 'venmo');
            $('#payment-wrapper-row-cards').removeClass('--active');
            $('#payment-wrapper-row-prepaid').removeClass('--active');
            // $('#payment-wrapper-row-1').removeClass('--active');
            // $('#payment-wrapper-row-2').addClass('--active');
            //
            // this.smallScroll();
        },
        showCashAppOptions() {
            this.selected_payment_method = 'cashapp';
            this.pay_method              = 'cashapp';
            this.$emit('updatePaymentMethod', 'cashapp');
            $('#payment-wrapper-row-cards').removeClass('--active');
            $('#payment-wrapper-row-prepaid').removeClass('--active');
            // $('#payment-wrapper-row-1').removeClass('--active');
            // $('#payment-wrapper-row-2').addClass('--active');
            //
            // this.smallScroll();
        },
        showPayoneerOptions() {
            this.selected_payment_method = 'payoneer';
            this.pay_method              = 'payoneer';
            this.$emit('updatePaymentMethod', 'payoneer');
            $('#payment-wrapper-row-cards').removeClass('--active');
            $('#payment-wrapper-row-prepaid').removeClass('--active');
            // $('#payment-wrapper-row-1').removeClass('--active');
            // $('#payment-wrapper-row-2').addClass('--active');
            //
            // this.smallScroll();
        },
        showZelleOptions() {
            this.selected_payment_method = 'zelle';
            this.pay_method              = 'zelle';
            this.$emit('updatePaymentMethod', 'zelle');
            $('#payment-wrapper-row-cards').removeClass('--active');
            $('#payment-wrapper-row-prepaid').removeClass('--active');
            // $('#payment-wrapper-row-1').removeClass('--active');
            // $('#payment-wrapper-row-2').addClass('--active');
            //
            // this.smallScroll();
        },
        showAmazPrepaidOptions() {
            this.selected_payment_method = 'amaz_prepaid';
            this.pay_method              = 'amaz_prepaid';
            this.$emit('updatePaymentMethod', 'amaz_prepaid');
            $('#payment-wrapper-row-prepaid').addClass('--active');
            // $('#payment-wrapper-row-1').removeClass('--active');
            // $('#payment-wrapper-row-2').addClass('--active');
            //
            // this.smallScroll();
        },
        showAthPrepaidOptions() {
            this.selected_payment_method = 'ath_prepaid';
            this.pay_method              = 'ath_prepaid';
            this.$emit('updatePaymentMethod', 'ath_prepaid');
            $('#payment-wrapper-row-ath-prepaid').addClass('--active');
            // $('#payment-wrapper-row-1').removeClass('--active');
            // $('#payment-wrapper-row-2').addClass('--active');
            //
            // this.smallScroll();
        },
        showPopmoneyOptions() {
            this.selected_payment_method = 'popmoney';
            this.pay_method              = 'popmoney';
            this.$emit('updatePaymentMethod', 'popmoney');
            $('#payment-wrapper-row-cards').removeClass('--active');
            $('#payment-wrapper-row-prepaid').removeClass('--active');
            // $('#payment-wrapper-row-1').removeClass('--active');
            // $('#payment-wrapper-row-2').addClass('--active');
            //
            // this.smallScroll();
        },
        showPandcoOptions() {
            this.selected_payment_method = 'pandco';
            this.pay_method              = 'pandco';
            this.$emit('updatePaymentMethod', 'pandco');
            $('#payment-wrapper-row-cards').removeClass('--active');
            $('#payment-wrapper-row-prepaid').removeClass('--active');
            // $('#payment-wrapper-row-1').removeClass('--active');
            // $('#payment-wrapper-row-2').addClass('--active');
            //
            // this.smallScroll();
        },
        openNewCardForm() {
            let addContact = $('#add-card-form');

            addContact.toggleClass("--show");
            let scrollToTop  = document.body.scrollTop || document.documentElement.scrollTop,
                offsetParent = $('.__overlay_container').offset().top;
            addContact.css('top', scrollToTop - offsetParent);
            // $('#add-contact-person').siblings(".card-body").css("filter", "blur(1px)");
        },
        closeCardOverlaid() {
            let cardOverlaid = $('.card--overlaid');
            if (window.matchMedia("(max-width: 640px)").matches) {
                let scrollToTop = document.body.scrollTop || document.documentElement.scrollTop;
                let topOriginal = cardOverlaid[0].getBoundingClientRect().top + scrollToTop;
                $("html, body").stop().animate({scrollTop: topOriginal}, 800, 'swing');
            }
            cardOverlaid.removeClass('--show');
        }
    },
    updated: function () {
        this.$nextTick(function () {
            if (this.force_loading === true) {
                this.pay_method              = '';
                this.pay_method_country      = '';
                this.selected_payment_method = '';
            }
        })
    },
    mounted: function () {
        card = elements.create('card');
        card.mount(this.$refs.card);
        this.gettingCards();
        $('[data-toggle="tooltip"]').tooltip()
    },

    watch: {
        card_number: function () {
            this.verifyQbFields();
        }
        // cards: function () {
        //     let currIndex = this.$refs.slick.currentSlide();
        //
        //     this.$refs.slick.destroy();
        //     this.$nextTick(() => {
        //         this.$refs.slick.create();
        //         this.$refs.slick.goTo(currIndex, true);
        //     });
        // }
    }
}
