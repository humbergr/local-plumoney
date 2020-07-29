<?php
/**
 * Created by PhpStorm.
 * User: isalcedo
 * Date: 2/17/19
 * Time: 7:53 PM
 */

namespace App\Http\Controllers;

use App\ApiHelper;
use App\BitstampData;
use App\CopUsdPrices;
use App\ToolsImageGeneratorCountryOffer;
use App\ToolsImageGeneratorVenBuyUsd;
use App\ToolsImageGeneratorVenSaleUsd;
use App\Transaction;
use App\User;
use App\UserExchangeTransactions;
use App\UserPersonProfile;
use App\VesUsdPrices;
use App\WebsiteSettings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;

class ToolsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function generateImages()
    {
//        $latestOffer = ToolsImageGeneratorCountryOffer::latest()->first()->toArray();
        //        var_dump($latestOffer);
        //        die;

        return view('tools.generate-images');
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function saveForeign(Request $request): int
    {
        $post = $request->post();

        if ($request->post('id') === '-1') {
            unset($post['_token']);
            $offer                   = new ToolsImageGeneratorCountryOffer;
            $offer->country_iso      = $post['countryIso'];
            $offer->country_name     = $post['country'];
            $offer->country_currency = $post['countryCurr'];
            $offer->status           = 1;
            $offer->user_id          = null;
            $offer->attributes       = null;
            $offer->save();

            $post['id']        = (string) $offer->id;
            $offer->attributes = json_encode($post);
            $offer->md5        = md5($offer->attributes);
            $offer->save();

            return $offer->id;
        }

        unset($post['_token']);
        $validation = md5(json_encode($post));
        $dbOffer    = ToolsImageGeneratorCountryOffer::find($post['id']);

        if ($dbOffer->md5 === $validation) {
            return $dbOffer->id;
        }

        $dbOffer->status = 2;
        $dbOffer->save();

        $offer                   = new ToolsImageGeneratorCountryOffer;
        $offer->country_iso      = $post['countryIso'];
        $offer->country_name     = $post['country'];
        $offer->country_currency = $post['countryCurr'];
        $offer->status           = 1;
        $offer->user_id          = null;
        $offer->attributes       = null;
        $offer->save();

        $post['id']        = (string) $offer->id;
        $offer->attributes = json_encode($post);
        $offer->md5        = md5($offer->attributes);
        $offer->save();

        return $offer->id;
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function saveVzlaSaleUSD(Request $request): int
    {
        $post = $request->post();
        unset($post['_token']);

        if ($request->post('id') === '-1') {
            $offer             = new ToolsImageGeneratorVenSaleUsd;
            $offer->status     = 1;
            $offer->user_id    = null;
            $offer->attributes = null;
            $offer->save();

            $post['id']        = (string) $offer->id;
            $offer->attributes = json_encode($post);
            $offer->md5        = md5($offer->attributes);
            $offer->save();

            return $offer->id;
        }

        $validation = md5(json_encode($post));
        $dbOffer    = ToolsImageGeneratorVenSaleUsd::find($post['id']);

        if ($dbOffer->md5 === $validation) {
            return $dbOffer->id;
        }

        $dbOffer->status = 2;
        $dbOffer->save();

        $offer             = new ToolsImageGeneratorVenSaleUsd;
        $offer->status     = 1;
        $offer->user_id    = null;
        $offer->attributes = null;
        $offer->save();

        $post['id']        = (string) $offer->id;
        $offer->attributes = json_encode($post);
        $offer->md5        = md5($offer->attributes);
        $offer->save();

        return $offer->id;
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function saveVzlaBuyUSD(Request $request): int
    {
        $post = $request->post();
        unset($post['_token']);

        if ($request->post('id') === '-1') {
            $offer             = new ToolsImageGeneratorVenBuyUsd;
            $offer->status     = 1;
            $offer->user_id    = null;
            $offer->attributes = null;
            $offer->save();

            $post['id']        = (string) $offer->id;
            $offer->attributes = json_encode($post);
            $offer->md5        = md5($offer->attributes);
            $offer->save();

            return $offer->id;
        }

        $validation = md5(json_encode($post));
        $dbOffer    = ToolsImageGeneratorVenBuyUsd::find($post['id']);

        if ($dbOffer->md5 === $validation) {
            return $dbOffer->id;
        }

        $dbOffer->status = 2;
        $dbOffer->save();

        $offer             = new ToolsImageGeneratorVenBuyUsd;
        $offer->status     = 1;
        $offer->user_id    = null;
        $offer->attributes = null;
        $offer->save();

        $post['id']        = (string) $offer->id;
        $offer->attributes = json_encode($post);
        $offer->md5        = md5($offer->attributes);
        $offer->save();

        return $offer->id;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInitialValues(): JsonResponse
    {
        $latestVenSaleUSD     = ToolsImageGeneratorVenSaleUsd::latest()->first();
        $latestsVenBuyUSD     = ToolsImageGeneratorVenBuyUsd::latest()->first();
        $latestVenSaleUSD     = $latestVenSaleUSD !== null ? $latestVenSaleUSD->toArray() : null;
        $latestsVenBuyUSD     = $latestsVenBuyUSD !== null ? $latestsVenBuyUSD->toArray() : null;
        $latestsForeignOffers = ToolsImageGeneratorCountryOffer::where('status', 1)->get();

        return response()->json([
            'latestVnzlaSaleUSD'  => $latestVenSaleUSD,
            'latestVnzlaBuyUSD'   => $latestsVenBuyUSD,
            'latestForeignOffers' => $latestsForeignOffers,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function generateTransactionPdf()
    {

        $inputs                  = request()->all();
        $userExchangeTransaction = UserExchangeTransactions::where('id', '=', $inputs['id'])
            ->with('destinationAccount')
            ->with('payment')
            ->first();

        return $this->pdf($userExchangeTransaction, Auth::user()->personProfileObject());
    }

    /**
     * @param UserExchangeTransactions $transaction
     *
     * @param UserPersonProfile        $userPersonProfile
     *
     * @throws MpdfException
     * @throws \Throwable
     */
    public function pdf($transaction, $userPersonProfile)
    {
        $html = view('tools.transaction-pdf')
            ->with(compact('transaction', 'userPersonProfile'))
            ->render();
        $nameFile          = 'transaction-ID-' . $transaction->tracking_id . '.pdf';
        $defaultConfig     = (new ConfigVariables())->getDefaults();
        $fontDirs          = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];
        $mpdf              = new Mpdf([
            'fontDir'      => array_merge($fontDirs, [
                public_path() . '/fonts',
            ]),
            'fontdata'     => $fontData + [
                'mukta' => [
                    'l' => 'Mukta-Light.ttf',
                    'R' => 'Mukta-Regular.ttf',
                    'B' => 'Mukta-Bold.ttf',
                ],
            ],
            'default_font' => 'mukta',
            'format'       => 'letter',
        ]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($nameFile, 'I');
    }

    public function generateDueDiligencePDF()
    {

        $inputs                  = request()->all();
        $user = User::where('id', '=', $inputs['id'])
            ->with('personProfile')
            ->first();
            
        if(isset($inputs['trader_id'])){

            $userWhoMadeTheApproval = User::where('id', '=', $inputs['trader_id'])
            ->with('personProfile')
            ->first();

            return $this->DueDiligencePDF($user, $inputs['lang'], $userWhoMadeTheApproval);
        }

        return $this->DueDiligencePDF($user, $inputs['lang']);

        
    }

    public function DueDiligencePDF($user, $lang, $userWhoMadeTheApproval = null){

        if($lang === 'ES'){
            $html = view('tools.generate-due-diligence-ES')
            ->with(compact('user', 'userWhoMadeTheApproval'))
            ->render();
        }elseif($lang === 'EN'){
            $html = view('tools.generate-due-diligence-EN')
            ->with(compact('user', 'userWhoMadeTheApproval'))
            ->render();
        }
        
        $nameFile          = 'transaction-ID-.pdf';
        $defaultConfig     = (new ConfigVariables())->getDefaults();
        $fontDirs          = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];
        $mpdf              = new Mpdf([
            'fontDir'      => array_merge($fontDirs, [
                public_path() . '/fonts',
            ]),
            'fontdata'     => $fontData + [
                'mukta' => [
                    'l' => 'Mukta-Light.ttf',
                    'R' => 'Mukta-Regular.ttf',
                    'B' => 'Mukta-Bold.ttf',
                ],
            ],
            'default_font' => 'mukta',
            'format'       => 'letter',
        ]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($nameFile, 'I');
    }

    /**
     * @throws GuzzleException
     */
    public function vesUsdExchangeRate(): void
    {
        $sellPriceModel = new VesUsdPrices();
        //Sell prices
        $sellPricesData                         = $this->vesSellExchangeRate();
        $sellPriceModel->sell_price             = $sellPricesData[0][0];
        $sellPriceModel->sell_price_announces   = $sellPricesData[0][1];
        $sellPriceModel->sell_price_2           = $sellPricesData[1][0];
        $sellPriceModel->sell_price_2_announces = $sellPricesData[1][1];
        //Buy prices
        $buyPricesData                         = $this->vesBuyExchangeRate();
        $sellPriceModel->buy_price             = $buyPricesData[0][0];
        $sellPriceModel->buy_price_announces   = $buyPricesData[0][1];
        $sellPriceModel->buy_price_2           = $buyPricesData[1][0];
        $sellPriceModel->buy_price_2_announces = $buyPricesData[1][1];
        $sellPriceModel->save();

//        return 'Cool';
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function vesSellExchangeRate(): array
    {
        //VES - Venezuela - VE - transfers-with-specific-bank
        //USD - USA - US - transfers-with-specific-bank
        /**
         * Banks
         */
        $banks = [
            'banesco',
            'provincial',
            'mercantil',
            'venezuela',
            'bod',
        ];

        $venezuelanAnnounces = ApiHelper::getLocalBtc(
            1,
            '/sell-bitcoins-online/VES/transfers-with-specific-bank/.json',
            null,
            [],
            'GET',
            true
        );

        $justAnnounces = $venezuelanAnnounces['data']['ad_list'];

        while (isset($venezuelanAnnounces['pagination']['next'])) {
            $page                = explode('=', $venezuelanAnnounces['pagination']['next'])[1];
            $venezuelanAnnounces = ApiHelper::getLocalBtc(
                1,
                '/sell-bitcoins-online/VES/transfers-with-specific-bank/.json',
                ['page' => $page],
                [],
                'GET',
                true
            );
            $justAnnounces = array_merge($justAnnounces, $venezuelanAnnounces['data']['ad_list']);
        }
        $validAnnounces = [];
        $vesSellRates   = [];
        $precioBitstamp = BitstampData::getNow();

        foreach ($justAnnounces as $announce) {
            $re = '/(?i:' . implode('|', $banks) . ')+/';
            preg_match_all(
                $re,
                $announce['data']['bank_name'],
                $matches,
                PREG_SET_ORDER
            );
            if (!empty($matches) && $announce['data']['limit_to_fiat_amounts'] === '') {
                $validAnnounces[] = $announce;
            }
        }

        usort($validAnnounces, static function ($a, $b) {
            return $b['data']['temp_price'] <=> $a['data']['temp_price'];
        });

        $validAnnouncesFirstGroup = $validAnnounces;
        //First Moment - Valid Announces >15 <35 USD
        foreach ($validAnnouncesFirstGroup as $key => $announce) {
            $vesRate = (float) $announce['data']['temp_price'] / $precioBitstamp;

            if ($announce['data']['min_amount'] &&
                ((float) $announce['data']['min_amount'] / $vesRate < 15 ||
                    (float) $announce['data']['min_amount'] / $vesRate > 35)) {
                unset($validAnnouncesFirstGroup[$key]);
            }

            if ($announce['data']['max_amount'] && (float) $announce['data']['max_amount'] / $vesRate < 30) {
                unset($validAnnouncesFirstGroup[$key]);
            }
            if ($announce['data']['max_amount_available'] &&
                (float) $announce['data']['max_amount_available'] / $vesRate < 100) {
                unset($validAnnouncesFirstGroup[$key]);
            }
        }

        /**
         * Calcular precios que no varíen por más del 5% del precio mayor.
         * - Es importante recordar con las anuncios que mínimo bajo varían drásticamente con el precio mayor.
         */
        $filterValidR   = $this->filterValidAnnouncesSell($validAnnouncesFirstGroup, $precioBitstamp);
        $roundInfo      = [];
        $roundInfo[]    = array_sum($filterValidR[0]) / count($filterValidR[0]);
        $roundInfo[]    = $filterValidR[1];
        $vesSellRates[] = $roundInfo;

        //Second Moment - Valid Announces >100 <300 USD
        foreach ($validAnnounces as $key => $announce) {
            $vesRate = (float) $announce['data']['temp_price'] / $precioBitstamp;

            if ($announce['data']['min_amount'] &&
                ((float) $announce['data']['min_amount'] / $vesRate < 100 ||
                    (float) $announce['data']['min_amount'] / $vesRate > 300)) {
                unset($validAnnounces[$key]);
            }

            if ($announce['data']['max_amount'] && (float) $announce['data']['max_amount'] / $vesRate < 30) {
                unset($validAnnounces[$key]);
            }
            if ($announce['data']['max_amount_available'] &&
                (float) $announce['data']['max_amount_available'] / $vesRate < 100) {
                unset($validAnnounces[$key]);
            }
        }

        /**
         * Calcular precios que no varíen por más del 5% del precio mayor.
         * - Es importante recordar con las anuncios que mínimo bajo varían drásticamente con el precio mayor.
         */
        $filterValidR   = $this->filterValidAnnouncesSell($validAnnounces, $precioBitstamp);
        $roundInfo      = [];
        $roundInfo[]    = array_sum($filterValidR[0]) / count($filterValidR[0]);
        $roundInfo[]    = $filterValidR[1];
        $vesSellRates[] = $roundInfo;

        return $vesSellRates;
    }

    /**
     * @param $validAnnounces
     * @param $precioBitstamp
     *
     * @return array
     */
    private function filterValidAnnouncesSell($validAnnounces, $precioBitstamp): array
    {
        $highestPrice         = 0;
        $vesRateProm          = [];
        $validAnnouncesBackup = $validAnnounces;

        foreach ($validAnnounces as $key => $announce) {
            if ($highestPrice !== 0) {
                $percentDifference = (100 * (float) $announce['data']['temp_price']) / $highestPrice;
                if (round($percentDifference) > 97) {
                    $vesRateProm[] = (float) $announce['data']['temp_price'] / $precioBitstamp;
                } else {
                    unset($validAnnounces[$key]);
                }
            } else {
                $highestPrice = (float) $announce['data']['temp_price'];
                unset($validAnnouncesBackup[$key]);
                $vesRateProm[] = (float) $announce['data']['temp_price'] / $precioBitstamp;
            }
        }

        $announcesLenght = count($validAnnounces);

        if ($announcesLenght < 3) {
            return $this->filterValidAnnouncesSell($validAnnouncesBackup, $precioBitstamp);
        }

        return [$vesRateProm, $validAnnounces];
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function vesBuyExchangeRate(): array
    {
        //VES - Venezuela - VE - transfers-with-specific-bank
        //USD - USA - US - transfers-with-specific-bank
        /**
         * Banks
         */
        $banks = [
            'banesco',
            'provincial',
            'mercantil',
            'venezuela',
            'bod',
        ];

        $venezuelanAnnounces = ApiHelper::getLocalBtc(
            1,
            '/buy-bitcoins-online/VES/transfers-with-specific-bank/.json',
            null,
            [],
            'GET',
            true
        );

        $justAnnounces = $venezuelanAnnounces['data']['ad_list'];

        while (isset($venezuelanAnnounces['pagination']['next'])) {
            $page                = explode('=', $venezuelanAnnounces['pagination']['next'])[1];
            $venezuelanAnnounces = ApiHelper::getLocalBtc(
                1,
                '/buy-bitcoins-online/VES/transfers-with-specific-bank/.json',
                ['page' => $page],
                [],
                'GET',
                true
            );
            $justAnnounces = array_merge($justAnnounces, $venezuelanAnnounces['data']['ad_list']);
        }

        $validAnnounces = [];
        $precioBitstamp = BitstampData::getNow();

        foreach ($justAnnounces as $announce) {
            $re = '/(?i:' . implode('|', $banks) . ')+/';
            preg_match_all(
                $re,
                $announce['data']['bank_name'],
                $matches,
                PREG_SET_ORDER
            );
            if (!empty($matches) && $announce['data']['limit_to_fiat_amounts'] === '') {
                $validAnnounces[] = $announce;
            }
        }

        usort($validAnnounces, static function ($a, $b) {
            return $a['data']['temp_price'] <=> $b['data']['temp_price'];
        });

        $validAnnouncesFirstGroup = $validAnnounces;
        //First Moment - Valid Announces >15 <35 USD
        foreach ($validAnnouncesFirstGroup as $key => $announce) {
            $vesRate = (float) $announce['data']['temp_price'] / $precioBitstamp;

            if ($announce['data']['min_amount'] &&
                ((float) $announce['data']['min_amount'] / $vesRate < 10 ||
                    (float) $announce['data']['min_amount'] / $vesRate > 50)) {
                unset($validAnnouncesFirstGroup[$key]);
            }

            if ($announce['data']['max_amount'] && (float) $announce['data']['max_amount'] / $vesRate < 30) {
                unset($validAnnouncesFirstGroup[$key]);
            }

            if ($announce['data']['max_amount_available'] &&
                (float) $announce['data']['max_amount_available'] / $vesRate < 100) {
                unset($validAnnouncesFirstGroup[$key]);
            }
        }

        /**
         * Calcular precios que no varíen por más del 5% del precio mayor.
         * - Es importante recordar con las anuncios que mínimo bajo varían drásticamente con el precio mayor.
         */
        $filterValidR   = $this->filterValidAnnouncesBuy($validAnnouncesFirstGroup, $precioBitstamp);
        $roundInfo      = [];
        $roundInfo[]    = array_sum($filterValidR[0]) / count($filterValidR[0]);
        $roundInfo[]    = $filterValidR[1];
        $vesSellRates[] = $roundInfo;

        //Second Moment - Valid Announces >100 <300 USD
        foreach ($validAnnounces as $key => $announce) {
            $vesRate = (float) $announce['data']['temp_price'] / $precioBitstamp;

            if ($announce['data']['min_amount'] &&
                ((float) $announce['data']['min_amount'] / $vesRate < 100 ||
                    (float) $announce['data']['min_amount'] / $vesRate > 300)) {
                unset($validAnnounces[$key]);
            }

            if ($announce['data']['max_amount'] && (float) $announce['data']['max_amount'] / $vesRate < 30) {
                unset($validAnnounces[$key]);
            }

            if ($announce['data']['max_amount_available'] &&
                (float) $announce['data']['max_amount_available'] / $vesRate < 100) {
                unset($validAnnounces[$key]);
            }
        }

        /**
         * Calcular precios que no varíen por más del 5% del precio mayor.
         * - Es importante recordar con las anuncios que mínimo bajo varían drásticamente con el precio mayor.
         */
        $filterValidR   = $this->filterValidAnnouncesBuy($validAnnounces, $precioBitstamp);
        $roundInfo      = [];
        $roundInfo[]    = array_sum($filterValidR[0]) / count($filterValidR[0]);
        $roundInfo[]    = $filterValidR[1];
        $vesSellRates[] = $roundInfo;

        return $vesSellRates;
    }

    /**
     * @param $validAnnounces
     * @param $precioBitstamp
     *
     * @return array
     */
    private function filterValidAnnouncesBuy($validAnnounces, $precioBitstamp): array
    {
        $lowestPrice          = 0;
        $vesRateProm          = [];
        $validAnnouncesBackup = $validAnnounces;

        foreach ($validAnnounces as $key => $announce) {
            if ($lowestPrice !== 0) {
                $percentDifference = (100 * (float) $announce['data']['temp_price']) / $lowestPrice;
                if (round($percentDifference) < 103) {
                    $vesRateProm[] = (float) $announce['data']['temp_price'] / $precioBitstamp;
                } else {
                    unset($validAnnounces[$key]);
                }
            } else {
                $lowestPrice = (float) $announce['data']['temp_price'];
                unset($validAnnouncesBackup[$key]);
                $vesRateProm[] = (float) $announce['data']['temp_price'] / $precioBitstamp;
            }
        }

        $announcesLength = count($validAnnounces);

        if ($announcesLength < 3) {
            return $this->filterValidAnnouncesBuy($validAnnouncesBackup, $precioBitstamp);
        }

        return [$vesRateProm, $validAnnounces];
    }

    /**
     * @throws GuzzleException
     */
    public function copUsdExchangeRateTry(): void
    {
//        $sellPriceModel             = new VesUsdPrices();
        //        $copSellPrice = $this->copSellExchangeRate();
        //        $copBuyPrice  = $this->copBuyExchangeRate();
        //        //Central Bank exchange
        //        $exchangeRates = HelpersController::getExchangeRates();
        //        echo 'Central Bank Rate: ' . $exchangeRates['rates']['COP'] . '<br><br>';
        //        echo 'BTC - Bitstamp Bank. USD > COP: ' . $copSellPrice . '<br><br>';
        //
        //        //Diference
        //        $percent = (100 * $copSellPrice) / $exchangeRates['rates']['COP'];
        ////        dd($percent);
        //        echo 'Difference %: ' . (100 - $percent) * -1 . '<br><br>';
        //
        //        $precioBitstamp = BitstampData::getNow();
        //        $btcUSA         = 100 / $precioBitstamp;
        //        echo '100USD in BTC > USD: ' . $btcUSA . '<br><br>';
        //        echo 'En caso de querer igualar la tasa de Colombia forzadamente sin respetar la tasa BTC <br><br>';
        //        $forcedBTC = $btcUSA + (($btcUSA * (100-$percent)) / 100);
        //        echo '100USD in BTC > COP: ' . $forcedBTC . '. El quivalente a: ' . $forcedBTC * $precioBitstamp .'<br><br>';
        //        echo '<hr>';
        //        echo 'BTC - Bitstamp Bank. COP > USD: ' . $copBuyPrice . '<br><br>';
        //        //Diference
        //        $percent = (100 * $copBuyPrice) / $exchangeRates['rates']['COP'];
        //        echo 'Difference %: ' . (100 - $percent) * -1 . '<br><br>';

        $isosMoney = [
            'CLP',
            'ARS',
            'COP',
            'PEN',
            'MXN',
            'DOP',
            'CRC',
            'PAB',
            'CAD',
            'EUR', //Problema Una sola tasa para EUR
            'GBP',
        ];
        $exchangeRates = HelpersController::getExchangeRates();
        //dd($exchangeRates['rates']);

        echo '<table border="1" style="text-align: right"><tbody>';
        foreach ($isosMoney as $iso) {
            $exchangeRate = $exchangeRates['rates'][$iso] ?? null;
            if ($exchangeRate !== null) {
                $exchangeRate = 1 / $exchangeRate;
                $simulateSend = 1 * $exchangeRate;
                //        $fees          = HelpersController::calculateFees(
                //            $simulateSend,
                //            'card',
                //            $exchangeRates['rates']['PEN'],
                //            $simulateSend
                //        );

                $exchangePriceData = VesUsdPrices::orderBy('id', 'desc')->first();
                $exchangeUtil      = $exchangePriceData['sell_price'] - (($exchangePriceData['sell_price'] * 6) / 100);
                $vesReceive        = $simulateSend * $exchangeUtil;
                echo '<tr><td>1 ' . $iso . '</td><td>' . round($vesReceive, 2) . ' VES</td></tr>';
            }
        }
        echo '</tbody></table>';
    }

    /**
     * @throws GuzzleException
     */
    public function copUsdExchangeRate(): void
    {
        $sellPriceModel = new CopUsdPrices();
        //Sell prices
        $sellPricesData                       = $this->copSellExchangeRate();
        $sellPriceModel->sell_price           = $sellPricesData[0];
        $sellPriceModel->sell_price_announces = $sellPricesData[1];
        $buyPricesData                        = $this->copBuyExchangeRate();
        $sellPriceModel->buy_price            = $buyPricesData[0];
        $sellPriceModel->buy_price_announces  = $buyPricesData[1];
        $sellPriceModel->save();

        $venezuelaUsdPrices = VesUsdPrices::orderBy('id', 'desc')->first();
        //Col to Venezuela Sample
        $copVesRate1 = $venezuelaUsdPrices->sell_price / $sellPriceModel->buy_price;
        $copVesRate2 = $venezuelaUsdPrices->sell_price_2 / $sellPriceModel->buy_price;
        dd($copVesRate1, $copVesRate2);

        //Calculate a relation between COP => VES
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function copSellExchangeRate(): array
    {
        //VES - Venezuela - VE - transfers-with-specific-bank
        //USD - USA - US - transfers-with-specific-bank
        /**
         * Banks
         */
        $banks = [
            'bancolombia',
            'davivienda',
            'bbva',
        ];

        $colombianAnnounces = ApiHelper::getLocalBtc(
            1,
            '/sell-bitcoins-online/COP/transfers-with-specific-bank/.json',
            null,
            [],
            'GET',
            true
        );

        $justAnnounces = $colombianAnnounces['data']['ad_list'];

        while (isset($colombianAnnounces['pagination']['next'])) {
            $page               = explode('=', $colombianAnnounces['pagination']['next'])[1];
            $colombianAnnounces = ApiHelper::getLocalBtc(
                1,
                '/sell-bitcoins-online/COP/transfers-with-specific-bank/.json',
                ['page' => $page],
                [],
                'GET',
                true
            );
            $justAnnounces = array_merge($justAnnounces, $colombianAnnounces['data']['ad_list']);
        }
        $validAnnounces = [];
        $precioBitstamp = BitstampData::getNow();

        foreach ($justAnnounces as $announce) {
            $re = '/(?i:' . implode('|', $banks) . ')+/';
            preg_match_all(
                $re,
                $announce['data']['bank_name'],
                $matches,
                PREG_SET_ORDER
            );
            if (!empty($matches) && $announce['data']['limit_to_fiat_amounts'] === '') {
                $validAnnounces[] = $announce;
            }
        }

        usort($validAnnounces, static function ($a, $b) {
            return $b['data']['temp_price'] <=> $a['data']['temp_price'];
        });

        //Si tiene máximo y su máximo es menor a 30USD se va.
        foreach ($validAnnounces as $key => $announce) {
            $vesRate = (float) $announce['data']['temp_price'] / $precioBitstamp;

            if ($announce['data']['min_amount'] &&
                ((float) $announce['data']['min_amount'] / $vesRate < 30 ||
                    (float) $announce['data']['min_amount'] / $vesRate > 150)) {
                unset($validAnnounces[$key]);
            }

            if ($announce['data']['max_amount'] && (float) $announce['data']['max_amount'] / $vesRate < 30) {
                unset($validAnnounces[$key]);
            }
            if ($announce['data']['max_amount_available'] &&
                (float) $announce['data']['max_amount_available'] / $vesRate < 100) {
                unset($validAnnounces[$key]);
            }
        }

        //dd($validAnnounces);

        $filterValidR = $this->filterValidAnnouncesSell($validAnnounces, $precioBitstamp);

        return [
            array_sum($filterValidR[0]) / count($filterValidR[0]),
            $filterValidR[1],
        ];
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function copBuyExchangeRate(): array
    {
        //VES - Venezuela - VE - transfers-with-specific-bank
        //USD - USA - US - transfers-with-specific-bank
        /**
         * Banks
         */
        $banks = [
            'bancolombia',
            'davivienda',
            'bbva',
        ];

        $venezuelanAnnounces = ApiHelper::getLocalBtc(
            1,
            '/buy-bitcoins-online/COP/transfers-with-specific-bank/.json',
            null,
            [],
            'GET',
            true
        );

        $justAnnounces = $venezuelanAnnounces['data']['ad_list'];

        while (isset($venezuelanAnnounces['pagination']['next'])) {
            $page                = explode('=', $venezuelanAnnounces['pagination']['next'])[1];
            $venezuelanAnnounces = ApiHelper::getLocalBtc(
                1,
                '/buy-bitcoins-online/COP/transfers-with-specific-bank/.json',
                ['page' => $page],
                [],
                'GET',
                true
            );
            $justAnnounces = array_merge($justAnnounces, $venezuelanAnnounces['data']['ad_list']);
        }

        $validAnnounces = [];
        $precioBitstamp = BitstampData::getNow();

        foreach ($justAnnounces as $announce) {
            $re = '/(?i:' . implode('|', $banks) . ')+/';
            preg_match_all(
                $re,
                $announce['data']['bank_name'],
                $matches,
                PREG_SET_ORDER
            );
            if (!empty($matches) && $announce['data']['limit_to_fiat_amounts'] === '') {
                $validAnnounces[] = $announce;
            }
        }

        usort($validAnnounces, static function ($a, $b) {
            return $a['data']['temp_price'] <=> $b['data']['temp_price'];
        });

        //Si tiene máximo y su máximo es menor a 30USD se va.
        foreach ($validAnnounces as $key => $announce) {
            $vesRate = (float) $announce['data']['temp_price'] / $precioBitstamp;

            if ($announce['data']['min_amount'] &&
                ((float) $announce['data']['min_amount'] / $vesRate < 30 ||
                    (float) $announce['data']['min_amount'] / $vesRate > 150)) {
                unset($validAnnounces[$key]);
            }

            if ($announce['data']['max_amount'] && (float) $announce['data']['max_amount'] / $vesRate < 30) {
                unset($validAnnounces[$key]);
            }
            if ($announce['data']['max_amount_available'] &&
                (float) $announce['data']['max_amount_available'] / $vesRate < 100) {
                unset($validAnnounces[$key]);
            }
        }

        $filterValidR = $this->filterValidAnnouncesSell($validAnnounces, $precioBitstamp);

        return [
            array_sum($filterValidR[0]) / count($filterValidR[0]),
            $filterValidR[1],
        ];
    }

    public function joinInternalMovements()
    {
        $transactionsMovements = Transaction::where('released_date', '>', '2019-06-01 00:00:00')
            ->where('is_manual', '=', 1)
            ->with(['outgoingbtc', 'incomingbtc'])
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        foreach ($transactionsMovements as $key => $transaction) {
            if ($transaction['outgoingbtc'] && ($transaction['outgoingbtc']['category'] !== 5 && $transaction['outgoingbtc']['category'] !== 6)) {
                unset($transactionsMovements[$key]);
            }
        }

        dd($transactionsMovements);
    }

    /**
     * Get authorization URL for user the app on Quickbook.
     *
     * @return RedirectResponse
     * @throws \QuickBooksOnline\API\Exception\SdkException
     */
    public function tameKerberos(): RedirectResponse
    {
        $dataService = DataService::Configure([
            'auth_mode'    => 'oauth2',
            'ClientID'     => 'AB4lUseYg9wLdcOrGXzSOPkxIErt4rsH6Er9aDk73fCgV6wIbO',
            'ClientSecret' => '5yhoghc5QLDMqknebjZzhXGY5kYXktsg5xavaDz3',
            'RedirectURI'  => 'http://akb.local/oauth-kerberos',
            'scope'        => 'com.intuit.quickbooks.payment',
            'baseUrl'      => 'Development',
        ]);
        $OAuth2LoginHelper    = $dataService->getOAuth2LoginHelper();
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        return Redirect::to($authorizationCodeUrl);
    }

    /**
     * Get Tokens for requests to QuickBook
     *
     * @return string
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    public function oauthKerberos(): string
    {
        $inputs      = request()->all();
        $dataService = DataService::Configure([
            'auth_mode'    => 'oauth2',
            'ClientID'     => 'ABHPXDEHR4TE0gQu7VKziMfd8A9ZhPGWIxWLhETNC35ozYEv2b',
            //'AB4lUseYg9wLdcOrGXzSOPkxIErt4rsH6Er9aDk73fCgV6wIbO',
            'ClientSecret' => 'jdRB0OH3e68baTkPkLZ3vw1rj33F1dOEOkkZIAWY',
            //'5yhoghc5QLDMqknebjZzhXGY5kYXktsg5xavaDz3',
            'RedirectURI'  => 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl',
            'scope'        => 'com.intuit.quickbooks.payment',
            'baseUrl'      => 'Production',
            //'Development'
        ]);
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $accessTokenObj    = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken(
            'AB11568240978bSlnNe83oo9537OX8LGae8AN1EIVlq15LKQA2', //$inputs['code'],
            '123146047031789' //$inputs['realmId']
        );
        $settings                       = WebsiteSettings::find(1);
        $settings->qb_access_token_key  = $accessTokenObj->getAccessToken();
        $settings->qb_refresh_token_key = $accessTokenObj->getRefreshToken();
        $settings->qb_realm_id          = $accessTokenObj->getRealmID();
        $settings->save();

        return 'true';
    }

    /**
     * Refresh Access Token
     *
     * @return string
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    public function washKerberos(): string
    {
        $settings          = WebsiteSettings::find(1);
        $oauth2LoginHelper = new OAuth2LoginHelper(
            'ABHPXDEHR4TE0gQu7VKziMfd8A9ZhPGWIxWLhETNC35ozYEv2b',
            'jdRB0OH3e68baTkPkLZ3vw1rj33F1dOEOkkZIAWY'
        );
        $accessTokenObj = $oauth2LoginHelper->
            refreshAccessTokenWithRefreshToken($settings->qb_refresh_token_key);
        $settings->qb_access_token_key  = $accessTokenObj->getAccessToken();
        $settings->qb_refresh_token_key = $accessTokenObj->getRefreshToken();
        $settings->save();

        return 'true';
    }

    /**
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws GuzzleException
     */
    public function qbTestCreateCard()
    {
        $settings = WebsiteSettings::find(1);
//        $dataService = DataService::Configure(array(
        //            'auth_mode'       => 'oauth2',
        //            'ClientID'        => 'AB4lUseYg9wLdcOrGXzSOPkxIErt4rsH6Er9aDk73fCgV6wIbO',
        //            'ClientSecret'    => '5yhoghc5QLDMqknebjZzhXGY5kYXktsg5xavaDz3',
        //            'accessTokenKey'  => $settings->qb_access_token_key,
        //            'refreshTokenKey' => $settings->qb_refresh_token_key,
        //            'QBORealmID'      => $settings->qb_realm_id,
        //            'baseUrl'         => 'Development'
        //        ));

        $client = new Client([
            'base_uri' => 'https://api.intuit.com', //'https://sandbox.api.intuit.com',
        ]);

//        $response = $client->request('POST', '/quickbooks/v4/customers/17/cards', [
        //            'headers' => [
        //                'content-type'  => 'application/json',
        //                'accept'        => 'application/json',
        //                'authorization' => 'Bearer ' . $settings->qb_access_token_key,
        //                'request-id'    => uniqid('ath_qb_', true)
        //            ],
        //            'json'    => [
        //                'number'   => '4408041234567893',
        //                'expMonth' => '12',
        //                'expYear'  => '2026',
        //                'cvc'      => '123'
        //            ]
        //        ]);
        //
        //        $body     = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        try {
            $response = $client->request('POST', '/quickbooks/v4/payments/charges', [
                'headers' => [
                    'content-type'  => 'application/json',
                    'accept'        => 'application/json',
                    'authorization' => 'Bearer ' . $settings->qb_access_token_key,
                    'request-id'    => uniqid('ath_qb_', false),
                ],
                'json'    => [
                    'currency' => 'USD',
                    'amount'   => 300,
                    'context'  => [
                        'mobile'      => false,
                        'isEcommerce' => true,
                    ],
                    'card'     => [
                        'number'   => '4085404022868552',
                        'expMonth' => '03',
                        'expYear'  => '2023',
                        'cvc'      => '692',
                    ],
                ],
            ]);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }

            die;
        }

        $body = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return response()->json($body);
    }

    /**
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws GuzzleException
     */
    public function qbTestChargeObject()
    {
        $settings = WebsiteSettings::find(1);

        $client = new Client([
            'base_uri' => 'https://api.intuit.com',
        ]);

        try {
            $response = $client->request('GET', '/quickbooks/v4/payments/charges/PI0255083536', [
                'headers' => [
                    'content-type'  => 'application/json',
                    'accept'        => 'application/json',
                    'authorization' => 'Bearer ' . $settings->qb_access_token_key,
                    'request-id'    => uniqid('ath_qb_', false),
                ],
            ]);
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }

            die;
        }

        $body = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return response()->json($body);
    }

    /**
     * @param string $email
     *
     * @return JsonResponse
     */
    public function searchUserEmail(): JsonResponse
    {
        $inputs      = request()->all();
        $responseArr = [];
        $users       = User::where('email', 'like', '%' . $inputs['search_keyword'] . '%')->get();

        foreach ($users as $user) {
            $responseArr[] = [
                'email'    => $user->email,
                'fullname' => $user->name,
            ];
        }

        return response()->json($responseArr);
    }
}
