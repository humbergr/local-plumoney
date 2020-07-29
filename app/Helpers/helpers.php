<?php

use App\Banco;
use App\Cuenta;
use App\Lote;
use App\Lotedetalle;
use App\Movimiento;
use App\Tarjeta;
use App\Transaction;
use App\User;
use App\UserExchangeTransactions;
use App\UserWalletsTransactions;
use Carbon\Carbon;
use Twilio\Rest\Client;

if (!function_exists('sms')) {
    function sms($menssage, $number)
    {

        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = env('TWILIO_ACCOUNT_SID');
        $auth_token  = env('TWILIO_AUTH_TOKEN');
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

        // A Twilio number you own with SMS capabilities
        $twilio_number = env('TWILIO_NUMBER');

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $number,
            array(
                'from' => $twilio_number,
                'body' => $menssage,
            )
        );
    }
}

if (!function_exists('currency')) {

    function currency($monto, $symbol)
    {


        if ($symbol == 'BTC') {
            $montoFin = (isset($monto)) ? $monto : 0;
            $a =  $symbol . '' . number_format($montoFin, 8, '.', ',');
        } else {
            $montoFin = (isset($monto)) ? $monto : 0;
            $a = $symbol . ' ' . number_format($montoFin, 2, '.', ',');
        }

        return $a;
    }
}

if (!function_exists('bancos')) {
    function bancos()
    {

        $model = Banco::orderBy('pais')->get();

        return $model;
    }
}
if (!function_exists('bancosBtc')) {
    function bancosBtc()
    {

        $model = Banco::where('moneda', 'BTC')->orderBy('pais')->get();

        return $model;
    }
}

if (!function_exists('bancosMoney')) {
    function bancosMoney($moneda)
    {

        $model = Banco::where('moneda', $moneda)->get();

        return $model;
    }
}


if (!function_exists('cuentas')) {
    function cuentas()
    {

        $model = Cuenta::all();

        return $model;
    }
}
if (!function_exists('usuarios')) {
    function usuarios()
    {

        $model = User::all();

        return $model;
    }
}

if (!function_exists('usuarioGet')) {
    function usuarioGet($id)
    {

        $model = User::where('id', $id)->get();

        return $model;
    }
}

if (!function_exists('tipoDeMovimiento')) {
    function tipoDeMovimiento()
    {

        $model = [
            'DEPOSITO'              => 'Depósito',
            'RETIRO'                => 'Retiro',
            'INTERES'               => 'Interés',
            'NOTA DE CREDITO'       => 'Nota de Crédito',
            'REVERSO DE DEBITO'     => 'Reverso de Débito',
            'TRANFERENCIA NEGATIVA' => 'Transferencia (-)',
            'TRANFERENCIA POSITIVA' => 'Transferencia (+)',
            'CHEQUE'                => 'Cheque',
            'NOTA DE DEBITO'        => 'Nota de Debito',
            'REVERSO DE CREDITO'    => 'Reverso de Crédito',
            'ID'                    => 'Imp. Trans. Finac.',
        ];

        return $model;
    }
}
if (!function_exists('operacion')) {
    function operacion()
    {

        $model = [
            'INGRESO'              => 'Ingreso',
            'EGRESO'               => 'Egreso',

        ];

        return $model;
    }
}
if (!function_exists('tipoMonedas')) {
    function tipoMonedas()
    {

        $model = [
            ""    => "Currency:",
            "AED" => "United Arab Emirates Dirham",
            "AFN" => "Afghan Afghani",
            "ALL" => "Albanian Lek",
            "AMD" => "Armenian Dram",
            "ANG" => "Netherlands Antillean Guilder",
            "AOA" => "Angolan Kwanza",
            "ARS" => "Argentine Peso",
            "AUD" => "Australian Dollar",
            "AWG" => "Aruban Florin",
            "AZN" => "Azerbaijani Manat",
            "BAM" => "Bosnia-Herzegovina Convertible Mark",
            "BBD" => "Barbadian Dollar",
            "BDT" => "Bangladeshi Taka",
            "BGN" => "Bulgarian Lev",
            "BHD" => "Bahraini Dinar",
            "BIF" => "Burundian Franc",
            "BMD" => "Bermudan Dollar",
            "BND" => "Brunei Dollar",
            "BOB" => "Bolivian Boliviano",
            "BRL" => "Brazilian Real",
            "BSD" => "Bahamian Dollar",
            "BTC" => "Bitcoin",
            "BTN" => "Bhutanese Ngultrum",
            "BWP" => "Botswanan Pula",
            "BYN" => "Belarusian Ruble",
            "BZD" => "Belize Dollar",
            "CAD" => "Canadian Dollar",
            "CDF" => "Congolese Franc",
            "CHF" => "Swiss Franc",
            "CLF" => "Chilean Unit of Account (UF)",
            "CLP" => "Chilean Peso",
            "CNH" => "Chinese Yuan (Offshore)",
            "CNY" => "Chinese Yuan",
            "COP" => "Colombian Peso",
            "CRC" => "Costa Rican Colón",
            "CUC" => "Cuban Convertible Peso",
            "CUP" => "Cuban Peso",
            "CVE" => "Cape Verdean Escudo",
            "CZK" => "Czech Republic Koruna",
            "DJF" => "Djiboutian Franc",
            "DKK" => "Danish Krone",
            "DOP" => "Dominican Peso",
            "DZD" => "Algerian Dinar",
            "EGP" => "Egyptian Pound",
            "ERN" => "Eritrean Nakfa",
            "ETB" => "Ethiopian Birr",
            "EUR" => "Euro",
            "FJD" => "Fijian Dollar",
            "FKP" => "Falkland Islands Pound",
            "GBP" => "British Pound Sterling",
            "GEL" => "Georgian Lari",
            "GGP" => "Guernsey Pound",
            "GHS" => "Ghanaian Cedi",
            "GIP" => "Gibraltar Pound",
            "GMD" => "Gambian Dalasi",
            "GNF" => "Guinean Franc",
            "GTQ" => "Guatemalan Quetzal",
            "GYD" => "Guyanaese Dollar",
            "HKD" => "Hong Kong Dollar",
            "HNL" => "Honduran Lempira",
            "HRK" => "Croatian Kuna",
            "HTG" => "Haitian Gourde",
            "HUF" => "Hungarian Forint",
            "IDR" => "Indonesian Rupiah",
            "ILS" => "Israeli New Sheqel",
            "IMP" => "Manx pound",
            "INR" => "Indian Rupee",
            "IQD" => "Iraqi Dinar",
            "IRR" => "Iranian Rial",
            "ISK" => "Icelandic Króna",
            "JEP" => "Jersey Pound",
            "JMD" => "Jamaican Dollar",
            "JOD" => "Jordanian Dinar",
            "JPY" => "Japanese Yen",
            "KES" => "Kenyan Shilling",
            "KGS" => "Kyrgystani Som",
            "KHR" => "Cambodian Riel",
            "KMF" => "Comorian Franc",
            "KPW" => "North Korean Won",
            "KRW" => "South Korean Won",
            "KWD" => "Kuwaiti Dinar",
            "KYD" => "Cayman Islands Dollar",
            "KZT" => "Kazakhstani Tenge",
            "LAK" => "Laotian Kip",
            "LBP" => "Lebanese Pound",
            "LKR" => "Sri Lankan Rupee",
            "LRD" => "Liberian Dollar",
            "LSL" => "Lesotho Loti",
            "LYD" => "Libyan Dinar",
            "MAD" => "Moroccan Dirham",
            "MDL" => "Moldovan Leu",
            "MGA" => "Malagasy Ariary",
            "MKD" => "Macedonian Denar",
            "MMK" => "Myanma Kyat",
            "MNT" => "Mongolian Tugrik",
            "MOP" => "Macanese Pataca",
            "MRO" => "Mauritanian Ouguiya (pre-2018)",
            "MRU" => "Mauritanian Ouguiya",
            "MUR" => "Mauritian Rupee",
            "MVR" => "Maldivian Rufiyaa",
            "MWK" => "Malawian Kwacha",
            "MXN" => "Mexican Peso",
            "MYR" => "Malaysian Ringgit",
            "MZN" => "Mozambican Metical",
            "NAD" => "Namibian Dollar",
            "NGN" => "Nigerian Naira",
            "NIO" => "Nicaraguan Córdoba",
            "NOK" => "Norwegian Krone",
            "NPR" => "Nepalese Rupee",
            "NZD" => "New Zealand Dollar",
            "OMR" => "Omani Rial",
            "PAB" => "Panamanian Balboa",
            "PEN" => "Peruvian Nuevo Sol",
            "PGK" => "Papua New Guinean Kina",
            "PHP" => "Philippine Peso",
            "PKR" => "Pakistani Rupee",
            "PLN" => "Polish Zloty",
            "PYG" => "Paraguayan Guarani",
            "QAR" => "Qatari Rial",
            "RON" => "Romanian Leu",
            "RSD" => "Serbian Dinar",
            "RUB" => "Russian Ruble",
            "RWF" => "Rwandan Franc",
            "SAR" => "Saudi Riyal",
            "SBD" => "Solomon Islands Dollar",
            "SCR" => "Seychellois Rupee",
            "SDG" => "Sudanese Pound",
            "SEK" => "Swedish Krona",
            "SGD" => "Singapore Dollar",
            "SHP" => "Saint Helena Pound",
            "SLL" => "Sierra Leonean Leone",
            "SOS" => "Somali Shilling",
            "SRD" => "Surinamese Dollar",
            "SSP" => "South Sudanese Pound",
            "STD" => "São Tomé and Príncipe Dobra (pre-2018)",
            "STN" => "São Tomé and Príncipe Dobra",
            "SVC" => "Salvadoran Colón",
            "SYP" => "Syrian Pound",
            "SZL" => "Swazi Lilangeni",
            "THB" => "Thai Baht",
            "TJS" => "Tajikistani Somoni",
            "TMT" => "Turkmenistani Manat",
            "TND" => "Tunisian Dinar",
            "TOP" => "Tongan Pa'anga",
            "TRY" => "Turkish Lira",
            "TTD" => "Trinidad and Tobago Dollar",
            "TWD" => "New Taiwan Dollar",
            "TZS" => "Tanzanian Shilling",
            "UAH" => "Ukrainian Hryvnia",
            "UGX" => "Ugandan Shilling",
            "USD" => "United States Dollar",
            "UYU" => "Uruguayan Peso",
            "UZS" => "Uzbekistan Som",
            "VES" => "Venezuelan Bolívar",
            "VND" => "Vietnamese Dong",
            "VUV" => "Vanuatu Vatu",
            "WST" => "Samoan Tala",
            "XAF" => "CFA Franc BEAC",
            "XAG" => "Silver Ounce",
            "XAU" => "Gold Ounce",
            "XCD" => "East Caribbean Dollar",
            "XDR" => "Special Drawing Rights",
            "XOF" => "CFA Franc BCEAO",
            "XPD" => "Palladium Ounce",
            "XPF" => "CFP Franc",
            "XPT" => "Platinum Ounce",
            "YER" => "Yemeni Rial",
            "ZAR" => "South African Rand",
            "ZMW" => "Zambian Kwacha",
            "ZWL" => "Zimbabwean Dollar",

        ];

        return $model;
    }
}

if (!function_exists('moverSaldo')) {
    function moverSaldo($banco)
    {


        $bank       = Banco::find($banco);
        $movimiento = Movimiento::where('banco_id', $banco)->sum('monto');
        $movimiento_usd = Movimiento::where('banco_id', $banco)->sum('monto_usd');

        $bank->saldo =  $movimiento;
        $bank->saldo_usd =  $movimiento_usd;
        $bank->update();
    }
}

if (!function_exists('moverLote')) {
    function moverLote($lote)
    {


        $lot       = Lote::find($lote);
        $movimiento = Lotedetalle::where('lote_id', $lote)->sum('monto');
        $lot->saldo =  $movimiento;
        $lot->update();
    }
}

if (!function_exists('currency')) {

    function currency($monto, $symbol)
    {
        $montoFin = (isset($monto)) ? $monto : 0;

        return $symbol . ' ' . number_format($montoFin, 2, '.', ',');
    }
}

if (!function_exists('bancos')) {
    function bancos()
    {

        $model = Banco::orderBy('pais')->get();

        return $model;
    }
}

if (!function_exists('lotes')) {
    function lotes($banco_id)
    {

        $model = Lote::where('banco_id', $banco_id)->get();


        return $model;
    }
}

if (!function_exists('cuentas')) {
    function cuentas()
    {

        $model = Cuenta::all();

        return $model;
    }
}
if (!function_exists('usuarios')) {
    function usuarios()
    {

        $model = User::all();

        return $model;
    }
}

if (!function_exists('tipoDeMovimiento')) {
    function tipoDeMovimiento()
    {

        $model = [
            'DEPOSITO'              => 'Depósito',
            'INTERES'               => 'Interés',
            'NOTA DE CREDITO'       => 'Nota de Crédito',
            'REVERSO DE Debito'     => 'Reverso de Débito',
            'TRANFERENCIA NEGATIVA' => 'Transferencia (-)',
            'TRANFERENCIA POSITIVA' => 'Transferencia (+)',
            'CHEQUE'                => 'Cheque',
            'NOTA DE DEBITO'        => 'Nota de Debito',
            'REVERSO DE CREDITO'    => 'Reverso de Crédito',
            'ID'                    => 'Imp. Trans. Finac.',
        ];

        return $model;
    }
}

if (!function_exists('moverSaldo')) {
    function moverSaldo($banco)
    {

        $bank       = Banco::find($banco);
        $movimiento = Movimiento::where('banco_id', $banco)->sum('monto');

        $bank->saldo = $movimiento;
        $bank->update();
    }
}
if (!function_exists('tarjetaActiva')) {
    function tarjetaActiva($inputs)
    {

        $ultimos4Digitos = substr($inputs['qb_card']['cardNumber'], -4);
        // $fecha  =  $inputs['qb_card']['card_month'] . ' / ' . $inputs['qb_card']['card_year'];
        $cvv =  $inputs['qb_card']['card_cvv'];
        $tarjeta = Tarjeta::where('user_id', Auth::id())->where('numero', $ultimos4Digitos)->where('cvc', $cvv)->where('active', true)->first();

        // dd( $tarjeta, $ultimos4Digitos, $fecha  ,$cvv  );
        return   $tarjeta;
    }
}


if (!function_exists('tarjetaPending')) {
    function tarjetaPending()
    {


        $tarjeta = Tarjeta::where('user_id', Auth::id())->where('verified', false)->count();

        return ($tarjeta > 0);
    }
}

if (!function_exists('tarjetasUser')) {
    function tarjetasUser($user)
    {


        $tarjeta = Tarjeta::where('user_id', $user)->get();

        return ($tarjeta);
    }
}
if (!function_exists('transactionOfToday')) {
    function transactionOfToday($user)
    {

        return  UserExchangeTransactions::where('user_id',  $user)->whereDate('created_at', Carbon::today()->toDateString())->get();;
    }
}
if (!function_exists('calcularTasa')) {
    function calcularTasa($amount, $receiving, $receiver, $receiver_country)
    {

        $curl = curl_init();

        //  dd("get-price?amount=".$amount."&receiving=".$receiving."&sender=USD&receiver=".$receiver."&sender_country=United+States&receiver_country=".$receiver_country."");

        curl_setopt_array($curl, array(
            CURLOPT_URL => url("get-price?amount=" . $amount . "&receiving=" . $receiving . "&sender=USD&receiver=" . $receiver . "&sender_country=United+States&receiver_country=" . $receiver_country . ""),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"holas\"\r\n\r\nque mas\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 0cd21e54-0cac-539a-7c91-ab2b591c6cba"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }
    }
}


if (!function_exists('customerTransaction')) {
    function customerTransaction($receiver_fiat)
    {

        return  UserExchangeTransactions::where('receiver_fiat', $receiver_fiat)->whereIn('status', [4, 0])->get();
    }
}


if (!function_exists('customerTransaction_id')) {
    function customerTransaction_id($id)
    {

        return  UserExchangeTransactions::find($id);
    }
}

if (!function_exists('UserWalletsTransactions')) {
    function UserWalletsTransactions($id)
    {

        return  UserWalletsTransactions::find($id);
    }
}

function get_user_name_by_id($id){
    return User::where('id','=',$id)->first()->name;
}

if (!function_exists('transactionForCurrency')) {
    function transactionForCurrency($type, array $currency)
    {
        return  Transaction::where('type', $type)
            ->where('created_at', '>=', '2020-07-01 00:00:00')
            ->where('salida', null)
            ->whereIn('currency', $currency)
            ->get();
    }
}

if (!function_exists('docBank')) {
    function docBank($descripcion, $id)
    {
       

        return  Movimiento::where('descripcion',$descripcion)->where('doc_id', $id)->first();
    }
}
if (!function_exists('robotEgresos')) {
    function robotEgresos($monto, $receiver_fiat, $operacion, $tasa, $request, $result, $comentario)
    {


        $lotes = Lote::where('saldo', '>', 0)->where('active', true)->where('banco_id', $request->banco_id)->orderBy('id', 'asc')->get();
        $cont3 = 0;
        foreach ($lotes as $key => $value) {


            if ($cont3 < $monto) {
                $cont3 += $value->saldo;
                $resta = $monto - $cont3;
                $idlotest_out[] = $value->id;
            }
        }
        ///debita un parcial del ultimo lote si es tru
        $partialLast = ($resta < 0) ? true : false;

        $lotesSoloAUsar = Lote::whereIn('id', $idlotest_out)->get();

        //  dd(  $cont3, $resta, $idlotest_out, $partialLast,$lotesSoloAUsar);
        $cuentaLotesAUsar =  count($lotesSoloAUsar) - 1;
        $partial = (count($lotesSoloAUsar) > 1) ? true : false ;

        foreach ($lotesSoloAUsar as $key => $value) {

            if ($partialLast === true && $key === $cuentaLotesAUsar) {

                $loted = new Lotedetalle();
                $loted->lote_id = $value->id;
                $loted->lote =  $value->lote;
                $loted->monto =   ($value->saldo + $resta) * -1;;
                $loted->currency =  $receiver_fiat;
                $loted->operacion = $operacion;
                $loted->tasa = $tasa;
                $loted->banco_id = $request->banco_id;
                $loted->movimiento_id =  $result->id;
                $loted->comentarios = $comentario; //'Customer Transaction';
                $loted->partial =  $partial;
                $loted->save();
                // $view[] = $loted->monto ; 
                // $view2[] =  $value->id; 
            } else {

                $loted = new Lotedetalle();
                $loted->lote_id = $value->id;
                $loted->lote =  $value->lote;
                $loted->monto =  $value->saldo * -1;
                $loted->currency =  $receiver_fiat;
                $loted->operacion = $operacion;
                $loted->tasa = $tasa;
                $loted->banco_id = $request->banco_id;
                $loted->movimiento_id = $result->id;
                $loted->comentarios = $comentario; //'Customer Transaction';
                $loted->partial =  $partial;
                $loted->save();
                //   $view[] = $loted->monto ; 
                //    $view2[] =  $value->id; 
            }
        }



        //  dd(1, $lotes, $view, $view2);
        $lote = Lote::all();
        foreach ($lote as $key2 => $value2) {
            moverLote($value2->id);
        }
    }
}
