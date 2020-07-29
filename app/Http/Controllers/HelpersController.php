<?php

namespace App\Http\Controllers;

use App\Akb\Banker;
use App\BitstampData;
use App\DestinationAccount;
use App\OutgoingBtcCache;
use App\Transaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use MasterCard\Api\CurrencyConversion\ConversionRate;
use MasterCard\Api\CurrencyConversion\ResourceConfig;
use MasterCard\Core\Model\RequestMap;
use MasterCard\Core\ApiConfig;
use MasterCard\Core\Exception\ApiException;
use MasterCard\Core\Security\OAuth\OAuthAuthentication;

class HelpersController extends Controller
{
    /**
     * Fix Sales VES prices according Bitstamp data
     */
    public function fixSalesVes()
    {
        $transactionsVes = Transaction::where(
            [
                'currency' => 'VES'
            ]
        )
            ->orderBy('released_date', 'asc')
            ->with('outgoingbtc')
            ->get();

        foreach ($transactionsVes as $transaction) {
            if ($transaction['outgoingbtc'] !== null) {
                $fundedDate    = new Carbon($transaction['json_data']['funded_at']);
                $fundedDate    = $fundedDate->format('Y-m-d H:i:s');
                $bitstampPrice = BitstampData::where('created_at', '>=', $fundedDate)
                    ->first();

                var_dump($fundedDate);
                var_dump($bitstampPrice);
                //var_dump($transaction['outgoingbtc']);
            }
        }
    }

    public function getStripeFees()
    {
        $inputs = request()->all();

         
        
        if ($inputs['amount'] !== null && $inputs['receive'] !== 'undefined') {
            if ($inputs['sendCurrency'] === 'USD') {
                
                return self::calculateFees($inputs['amount'], 'card', $inputs['cardCountry']);
               
            }

           
            $exchangeRates = self::getExchangeRates();
            $usdToCharge   = $inputs['amount'] / $exchangeRates['rates'][$inputs['sendCurrency']];
            $usdBridge     = (float)$inputs['receive'];

            if ($inputs['receiveCurrency'] !== 'USD') {
                $usdBridge = $inputs['receive'] / $exchangeRates['rates'][$inputs['receiveCurrency']];
            }

            if ($inputs['receiveCurrency'] === 'VES') {
                $usdBridge = $usdToCharge;
            }
        } else {
            $exchangeRates = self::getExchangeRates();
            $usdToCharge   = 0;
            $usdBridge     = 0;
        }

        //TODO Retornar error si no es igual o mayor a 50c USD

        return self::calculateFees(
            $usdToCharge,
            'card',
            $inputs['cardCountry'],
            $exchangeRates['rates'][$inputs['sendCurrency']],
            $usdBridge
        );
    }

    /**
     * @param      $amount
     * @param      $type
     * @param      $cardCountry
     * @param null $exchangeRate
     * @param null $receive
     *
     * @param null $inUSD
     *
     * @return array|null
     */
    public static function calculateFees(

       
        $amount,
        $type,
        $cardCountry,
        $exchangeRate = null,
        $receive = null,
        $inUSD = null
    ): ?array {

           //dd( $amount ,$type, $cardCountry , $exchangeRate,$receive, $inUSD  );
        if ($type === 'card' && $amount !== 0) {
      
            $total = (100 * (0.30 + $amount)) / ((env('FEE_123PAGO')) + 100);
           //dd(1,$total , $amount);
            if ($cardCountry !== 'US') {
                $total += ($amount / 92);
            }

            $fee = $total - $amount;

            if ($exchangeRate !== null) {
                $receivePlusFees = ((100 * (0.30 + $receive)) / ((env('FEE_123PAGO')) + 100)) + $receive / 100;

                //Ensure profit on Stripe
                if ($receivePlusFees > $total) {
                    $difference = $receivePlusFees - $total;
                    $total      += $difference;
                }
                $fee = $total - $amount;

                if ($inUSD === null) {
                    return [round($fee * $exchangeRate, 2), round($total * $exchangeRate, 2)];
                }

                return [round($fee, 2), round($total, 2)];
            }

            return [round($fee, 2), round($total, 2)];
        }

        return null;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFxCountries()
    {
        $inputs     = request()->all();
        $countryISO = $inputs['countryISO'];

        //TODO dejar de fusilarse Fedex
        $client = new Client();
        $res    = $client->request(
            'GET',
            'https://www.fedex.com/commonDataCal/v2/countryDetail/' . $countryISO)
            ->getBody()
            ->getContents();

        $states           = json_decode($res, true)['output']['states'];
        $statesFancyArray = [];

        if (!empty($states)) {
            foreach ($states as $state) {
                $statesFancyArray[$state['name']] = $state['name'];
            }
        }

        return $statesFancyArray;
    }

    /**
     *
     */
    public function getOpCountries()
    {
        $countries = Banker::getOpCountries();
        $inputs    = request()->all();

        $countriesCurrencies = [];

        $htmlSender   = '';
        $htmlReceiver = '';
        foreach ($countries as $key => $region) {
            $htmlSender   .= '<optgroup label="' . $key . '">';
            $htmlReceiver .= '<optgroup label="' . $key . '">';
            foreach ($region as $rKey => $country) {
                if (isset($inputs['without']) && in_array($country[0], $inputs['without'], true)) {
                    continue;
                }

                $countriesCurrencies[$country[0]] = $country[3];
                $htmlSender                       .= '<option value="' . $country[0] . '"';

                if (!isset($country['noLB'])) {
                    $htmlReceiver .= '<option value="' . $country[0] . '"
                                       data-flag="/img/landing/flags/' . $country[1] . '.svg">';
                    $htmlReceiver .= $country[0] . '</option>';
                } else {
                    $htmlSender .= ' data-nb="true"';
                }
                $htmlSender .= ' data-flag="/img/landing/flags/' . $country[1] . '.svg">';
                $htmlSender .= $country[0] . '</option>';
            }
            $htmlSender   .= '</optgroup>';
            $htmlReceiver .= '</optgroup>';
        }

        return json_encode([$countriesCurrencies, $htmlSender, $htmlReceiver]);
    }

    /**
     * @return array
     */
    public static function getAdminCountries($senderF, $receiverF): array
    {
        $countries = Banker::getOpCountries();

        $htmlSender   = '';
        $htmlReceiver = '';
        foreach ($countries as $key => $region) {
            $htmlSender   .= '<optgroup label="' . $key . '">';
            $htmlReceiver .= '<optgroup label="' . $key . '">';
            foreach ($region as $rKey => $country) {
                $htmlSender .= '<option value="' . $country[3] . '"';
                if ($senderF === $country[3]) {
                    $htmlSender .= ' selected ';
                }
                $htmlSender .= ' data-flag="/img/landing/flags/' . $country[1] . '.svg">';
                $htmlSender .= $country[0] . '</option>';

                if (!isset($country['noLB'])) {
                    $htmlReceiver .= '<option value="' . $country[3] . '"';
                    if ($receiverF === $country[3]) {
                        $htmlReceiver .= ' selected ';
                    }
                    $htmlReceiver .= 'data-flag="/img/landing/flags/' . $country[1] . '.svg">';
                    $htmlReceiver .= $country[0] . '</option>';
                }
            }
            $htmlSender   .= '</optgroup>';
            $htmlReceiver .= '</optgroup>';
        }

        return [$htmlSender, $htmlReceiver];
    }

    public static function getExchangeRates()
    {
        $opexchID = env('OPEXCH_ID');
        $client   = new Client();
        $res      = $client->request(
            'GET',
            'https://openexchangerates.org/api/latest.json',
            [
                'query' => ['app_id' => $opexchID]
            ]
        )
            ->getBody()
            ->getContents();

        return json_decode($res, true);
    }

    public function getDestinationInfo()
    {
        $inputs        = request()->all();
        $destinationID = $inputs['id'];

        return DestinationAccount::where(['id' => $destinationID])->first();
    }

    /**
     * @return array
     */
    public function getCountriesCurrencies(): array
    {
        return [
            'MXN' => 'MX',
            'DOP' => 'DO',
            'CRC' => 'CR',
            'PAB' => 'PA',
            'CAD' => 'CA',
            'GBP' => 'UK',
            'VES' => 'VE',
            'ARS' => 'AR',
            'CLP' => 'CL',
            'COP' => 'CO',
            'USD' => 'US',
            'EUR' => 'ES',
            'PEN' => 'PE',
            //BO
            //BR
            //PE
            //PY
            //UY
            //CA
            //PT
        ];
    }

    /**
     * Return simple exchange rate for Payoneer according Mastercard
     */
    public function getExchangeRate(): void
    {
//        $consumerKey = 'ffE9HajXh7fIQZmCUB_S3_nOhxIv8oYt-JphIiVgc0bfd87d!42d9bf38d1cd4d8d8d6c9af64a27d11a0000000000000000';   // You should copy this from 'My Keys' on your project page e.g. UTfbhDCSeNYvJpLL5l028sWL9it739PYh6LU5lZja15xcRpY!fd209e6c579dc9d7be52da93d35ae6b6c167c174690b72fa
//        $keyAlias    = 'keyalias';   // For production: change this to the key alias you chose when you created your production key
//        $keyPassword = 'keystorepassword';   // For production: change this to the key alias you chose when you created your production key
//        $privateKey  = file_get_contents(__DIR__ . '/Tasa_Payoneer_Bancolombia-sandbox.p12'); // e.g. /Users/yourname/project/sandbox.p12 | C:\Users\yourname\project\sandbox.p12
//        ApiConfig::setAuthentication(new OAuthAuthentication($consumerKey, $privateKey, $keyAlias, $keyPassword));
//        ApiConfig::setDebug(true); // Enable http wire logging
//// This is needed to change the environment to run the sample code.
//        ResourceConfig::getInstance()->setEnvironment('sandbox_mtf'); // For production: use ApiConfig::setSandbox(false)
//
//
//        try {
//            $map = new RequestMap();
//            $map->set('fxDate', date('Y-m-d'));
//            $map->set('transCurr', 'COP');
//            $map->set('crdhldBillCurr', 'USD');
//            $map->set('bankFee', '0');
//            $map->set('transAmt', '600000');
//            $response = ConversionRate::query($map);
//
//            dd($response);
//
//            $this->out($response, 'name'); //-->settlement-conversion-rate
//            $this->out($response, 'description'); //-->Settlement conversion rate and billing amount
//            $this->out($response, 'date'); //-->2017-11-03 03:59:50
//            $this->out($response, 'data.conversionRate'); //-->0.57
//            $this->out($response, 'data.crdhldBillAmt'); //-->13.11
//            $this->out($response, 'data.fxDate'); //-->2016-09-30
//            $this->out($response, 'data.transCurr'); //-->ALL
//            $this->out($response, 'data.crdhldBillCurr'); //-->DZD
//            $this->out($response, 'data.transAmt'); //-->23
//            $this->out($response, 'data.bankFee'); //-->5
//
//        } catch (ApiException $e) {
//            $this->err('HttpStatus: ' . $e->getHttpStatus());
//            $this->err('Message: ' . $e->getMessage());
//            $this->err('ReasonCode: ' . $e->getReasonCode());
//            $this->err('Source: ' . $e->getSource());
//            //print_r($e);
//        }
        $copRate          = 0.0003031; //TODO must be dynamic MasterCard
        $payoneerFixed    = 3.15;
        $ammountUSD       = 600000 * $copRate;
        $diffCurrencyPerc = $ammountUSD * 3.5 / 100;
        $atmBancolUSD     = 14520 * $copRate;
        $totalFee         = $payoneerFixed + $diffCurrencyPerc + $atmBancolUSD;

        $rate = 600000 / ((($ammountUSD / 100) + $ammountUSD) + $totalFee);

        echo round($rate, 2);
    }

    private function out($response, $key)
    {
        echo "$key-->{$response->get($key)}<br>";
    }

    private function outObj($response, $key)
    {
        echo "$key-->{$response[$key]}<br>";
    }

    private function errObj($response, $key)
    {
        echo "$key-->{$response->get($key)}<br>";
    }

    private function err($message)
    {
        echo "$message <br>";
    }
}
