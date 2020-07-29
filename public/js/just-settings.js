function getPrice(to_send, sender, receiver, sender_country, receiver_country, ajax_fee_percent) {
    return axios.get(window.location.origin + '/get-price', {
        params: {
            amount: to_send,
            sender: sender,
            receiver: receiver,
            sender_country: sender_country,
            receiver_country: receiver_country,
            ajax_fee_percent: ajax_fee_percent
        }
    });
}

function wGetPrice(to_send, sender, receiver, sender_country, receiver_country, ajax_fee_percent) {
    return axios.get(window.location.origin + '/wallets-get-price', {
        params: {
            amount: to_send,
            sender: sender,
            receiver: receiver,
            sender_country: sender_country,
            receiver_country: receiver_country,
            ajax_fee_percent: ajax_fee_percent
        }
    });
}

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
        console.log(e)
    }
}

$('#ex_fee_to_ven').change(function () {
    getPrice(150, 'USD', 'VES2', 'United States', 'Venezuela', $(this).val()).then(function (response) {
        $('.number_to_ven').text(formatMoney(response.data[0]));
    });
});

$('#ex_fee_from_ven').change(function () {
    getPrice(999999999, 'VES2', 'USD', 'Venezuela', 'United States', $(this).val()).then(function (response) {
        $('.number_from_ven').text(formatMoney(1 / response.data[0]));
    });
});

$('#wallets_fee_to_ven').change(function () {
    wGetPrice(150, 'USD', 'VES2', 'United States', 'Venezuela', $(this).val()).then(function (response) {
        $('.wallets_number_to_ven').text(formatMoney(response.data[0]));
    });
});

$('#wallets_fee_from_ven').change(function () {
    wGetPrice(999999999, 'VES2', 'USD', 'Venezuela', 'United States', $(this).val()).then(function (response) {
        $('.wallets_number_from_ven').text(formatMoney(1 / response.data[0]));
    });
});

$('#wallets_usd_usd_charge').change(function () {
    wGetPrice(1, 'USD', 'USD', 'United States', 'United States', $(this).val()).then(function (response) {
        $('.wallets_number_usd_usd_charge').text(formatMoney(response.data[0]));
    });
});

$('#wallets_usd_usd_withdraw').change(function () {
    wGetPrice(1, 'USD', 'USD', 'United States', 'United States', $(this).val()).then(function (response) {
        $('.wallets_number_usd_usd_withdraw').text(formatMoney(response.data[0]));
    });
});