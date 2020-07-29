<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use App\Http\Controllers\ReferralsController;
use App\Http\Controllers\Testing\TestingController;
use App\Http\Controllers\UserController;

use GuzzleHttp\Client;

use function GuzzleHttp\json_decode;
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);;

Route::get('pago123', function () {



       $cedulaCliente =  '17168755';
       $nombreCliente =  'Douglas jOSE';
       $emailCliente =  'francysmendozaa@gmail.com';
       $nai =  '27'; //ID DE LA ORDEN
       $idCobro =  uniqid(); //Identificación de la operación de cobro-pago, es totalmente numérico
       $direccion =  'caracas'; //1-500
       $nuTarjeta =  '6011601160116611';
       $cvv2 =  '124';
       $fechven =  '0724';
       $nbproveedor =  'AKB';
       $concepto =  'GIFTCARD';
       $monto =  126;
       $idpromocion =  '2';
       $ms_factura =  uniqid(); //Número de la Factura de Pago
       $control =  uniqid(); //Número de Control de la Operación de Pago
       $telefono =  '04141331946';
       $api_key =  'a0998436c691f61e26bc9ec00960cefe';





    /*
        prueba a través de https://sandbox.123pago.net/ms_report/
        Accesos:
        Usuario: gsq@mericankryptosbank.com
        Contraseña: abc123
        //TARJETA SUCCESS = 6011601160116611
 */


    $curl = curl_init();

    curl_setopt_array($curl, array(
        // CURLOPT_URL => "https://payment.123pago.net/ms_123pagoPOSEngineREST_V2.0/webresources/pagar/pagarVPOS",
        CURLOPT_URL => "https://sandbox.123pago.net/ms_123pagoPOSEngineREST_V2.0/webresources/pagar/pagarVPOS",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => " {\n\"cedulaCliente\": \"$cedulaCliente\",\n\"nombreCliente\": \"$nombreCliente\",\n\"emailCliente\": \" $emailCliente  \",
            \n\"nai\": \"$nai\",\n\"idCobro\": \"$idCobro\",\n\"direccion\": \"$direccion\",\n\"nuTarjeta\": \"$nuTarjeta\",
            \n\"cvv2\": \"$cvv2\",\n\"fechven\": \"$fechven\",\n\"nbproveedor\": \"$nbproveedor\",\n\"concepto\": \"$concepto\",\n\"monto\":   $monto,
            \n\"idpromocion\": \"$idpromocion\",\n\"ms_factura\": \"$ms_factura\",\n\"control\": \"$control\",\n\"telefono\": \"$telefono\",
            \n\"api_key\": \"$api_key\"\n \n}",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json"

        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {

        $json = json_decode($response);
        dd($json, $json->cod_transaccion);
    }
});

Route::get('/confirm-card', 'UserController@confirmCard')->middleware('auth');
Route::post('/confirm-card', 'UserController@confirmCardStore')->middleware('auth');
Route::get('/confirm-card-admin', 'UserController@confirmCardAdmin')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);
Route::get('/user-profile-card-action/{user_id}', 'UserController@confirmCardAdminAction')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);
Route::post('/confirm-card-admin/{tarjeta}', 'UserController@confirmCardAdminUpdate')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);;

Route::get('setlocale/{locale}', function ($lang) {
    \Session::put('locale', $lang);

    return redirect()->back();
});

Route::get('/', 'HomeController@getHome')->middleware('language');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
//Jose Routes
Route::get('/signin', [ReferralsController::class, 'showSignin']);
Route::get('/referrals', [ReferralsController::class, 'showIndex'])->middleware('auth');
Route::get('/referrals/user/{user_id}', [ReferralsController::class, 'showPublicistsUser'])->middleware('auth');
Route::get('/referrals/list', [ReferralsController::class, 'showReferralsList'])->middleware('auth');
Route::get('/referrals/transactions', [ReferralsController::class, 'showReferralsTransactions'])->middleware('auth');
Route::get('/referrals/payments', [ReferralsController::class, 'showReferralsPayments'])->middleware('auth');
Route::get('/admin/publicists', [ReferralsController::class, 'showAdminPublicists'])->middleware('auth');
Route::post('/admin/save-new-code', [ReferralsController::class, 'saveNewCode'])->middleware('auth');
Route::post('/admin/update-code/{code_id}', [ReferralsController::class, 'updateCode'])->middleware('auth');
Route::post('/admin/disable-code/{code_id}', [ReferralsController::class, 'disableCode'])->middleware('auth');
Route::get('/admin/user/{user_id}/{code_id}', [ReferralsController::class, 'showAdminUser'])->middleware('auth');
Route::get('/admin/user-payments/{user_id}/{code_id}', [ReferralsController::class, 'showAdminUserPayments'])->middleware('auth');
Route::post('/admin/user-payments-pay/{user_id}/{code_id}', [ReferralsController::class, 'showAdminUserPaymentPay'])->middleware('auth');
Route::get('/admin/referral/{user_id}', [ReferralsController::class, 'adminShowPublicistsUser'])->middleware('auth');
Route::post('/admin/referral/{user_id}/get', [ReferralsController::class, 'showUserChart'])->middleware('auth');
Route::get('/admin/download-csv/{code_id}', [ReferralsController::class, 'downloadCSV'])->middleware('auth');

//Helpers
Route::get('/api/search', 'ToolsController@searchUserEmail');
//END Jose Routes

Route::get('/home', 'HomeController@getClientHome')->middleware('auth');

Route::get('/about-us', function () {
    return view('about-us');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/send-money', 'UserController@sendMoney')->middleware('auth', 'language');

Auth::routes();

//General User (Merchant Id = 5)
Route::get('/settings', 'UserController@getMerchantSettings')->middleware('auth');
Route::post('/settings', 'UserController@postMerchantSettings')->middleware('auth');
Route::post('/register-merchant', 'UserController@newMerchant')->middleware('language');

Route::get('/dashboard', 'HomeController@dashboard')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::get('/app', 'HomeController@indexV2')->middleware(
    'auth',
    'admins-only'
);

Route::get('/get-next-advertisements', 'DataController@getNextAdvertisements')->middleware(
    'auth',
    'admins-only'
);

Route::get('/get-ads-code/{operation}/{code}', 'DataController@getAdsCode')->middleware(
    'auth',
    'admins-only'
);

Route::get('/search', 'HomeController@getSearchPage')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);
//Route::get('/search', 'HomeController@searchAds')->middleware('auth');
Route::get('/home-data', 'HomeController@homeData')->name('home-data');
//Route::get('/market-data2', 'HomeController@marketData')->name('market-data');
Route::get('/market-data-now', 'HomeController@marketDataNow')->name('market-data-now');

Route::get('/get-advertisements', 'HomeController@getAdvertisements')->middleware(
    'auth',
    'admins-only'
);

Route::get('/get-bitstamp-data', 'HomeController@getBitstampData');
Route::get('/create-data', 'HomeController@createData');
Route::get('/get-volume-total/{code}', 'HomeController@getVolumeT');
Route::get('/get-volume-buy/{code}', 'HomeController@getVolumeB');
Route::get('/get-volume-sell/{code}', 'HomeController@getVolumeS');
Route::get('/get-ads/{code}', 'HomeController@getAds');
Route::get('/get-next', 'HomeController@getNext');
Route::get('/get-local-data', 'DataController@getLocalData');
Route::get('/get-local-volume/{code}', 'DataController@getLocalVolume');
Route::get('/market-data', 'DataController@marketData');
Route::get('/payment-methods', 'DataController@paymentMethods');

Route::get('/wallets-data', 'LocalWalletController@walletsData')->middleware(
    'auth',
    'admins-only'
);

//Fix VES sales
//Route::get('/fix-sales-ves', 'HelpersController@fixSalesVes');

// users
Route::get('/create-user', 'UserController@getCreateUser')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::post('/create-user', 'UserController@postCreateUser')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::get('/register', 'UserController@getRegister');
Route::post('/register', 'UserController@postRegister');

Route::get('/verify-account', 'UserController@getVerifyAccount');
Route::post('/verify-account', 'UserController@postVerifyAccount');

Route::get('/user-settings', 'UserController@getSettings')->middleware(
    'auth',
    'role:administrator,admin,trader_master,support'
);

Route::post('/user-settings', 'UserController@postSettings')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

// To attend
Route::post('/post-closed-trades', 'LocalTradesController@postClosedTrades')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);
Route::post('/post-completed-trades', 'LocalTradesController@postCompletedTrades')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);
Route::post('/post-cancelled-trades', 'LocalTradesController@postCancelledTrades')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);
Route::get('/trades-page', 'LocalTradesController@tradesPage')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

//advertisements
Route::get('/create-advertisement', 'AdvertisementController@getCreateForm')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::post('/create-advertisement', 'AdvertisementController@postCreate')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::get('/edit-advertisement/{id}', 'AdvertisementController@getEditAdvertisement')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::post('/edit-advertisement/{id}', 'AdvertisementController@postEditAdvertisement')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::post('/my-ads', 'AdvertisementController@myAds')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::post('/edit-equation/{id}', 'AdvertisementController@editEquation')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::post('/assign-ad', 'AdvertisementController@assignAd')->middleware(
    'auth',
    'admins-only'
);

Route::get('/workroom', 'AdvertisementController@getWorkroom')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::get('/create-contact/{id}', 'AdvertisementController@openContact')->middleware(
    'auth',
    'admins-only'
);
Route::get('/contact/chat/{id}', 'AdvertisementController@getChat')->middleware(
    'auth',
    'admins-only'
);

Route::get('/api/contact-messages/{id}/{count}', 'AdvertisementController@getMessages')->middleware(
    'auth',
    'admins-only'
);

Route::get('/api/contact-messages-not/{id}', 'AdvertisementController@getMessagesNot')->middleware(
    'auth',
    'admins-only'
);

Route::post('/api/send-message/{id}', 'AdvertisementController@sendMessage')->middleware('auth');

Route::post('/assign-ad', 'AdvertisementController@assignAd')->middleware('auth');

//transactions
Route::get('/create-transaction', 'TransactionController@getCreate')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances,compliance'
);

Route::post('/create-incoming-transaction', 'TransactionController@postCreate')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances,compliance'
);

Route::get('/transactions', 'TransactionController@getList')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances,compliance'
);

Route::get('/transactions-test', 'TransactionController@getTest')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

Route::get('/filter-transactions', 'TransactionController@getFilterTransactions')->middleware('auth');
Route::get('/filter-daterange', 'TransactionController@getFilterDaterange')->middleware('auth');
Route::get('/filter-profit-range', 'TransactionController@getRangeProfitLoss')->middleware('auth');
Route::get('/filter-no-profit-range', 'TransactionController@getRangeNoProfit')->middleware('auth');
Route::get('/filter-expenses-range', 'TransactionController@getRangeExpenses')->middleware('auth');
Route::get('/filter-ssum-range', 'TransactionController@getRangeSalesSum')->middleware('auth');
Route::get('/transactions-pagination', 'TransactionController@getTransactionsPagination')->middleware('auth');
Route::get('/calculate-btc-sale', 'TransactionController@calculateBtcSale')->middleware('auth');

//Exchange Rate Calculator
Route::get('/get-fees', 'HelpersController@getStripeFees');
Route::get('/exchange-rate', 'HelpersController@getExchangeRate')->middleware('auth');
Route::get('/exchange-rates', 'HelpersController@getExchangeRates')->middleware('auth');

//Helpers
Route::get('/get-countries', 'HelpersController@getFxCountries');

Route::get('/transactions/edit/{id}', 'TransactionController@getEdit')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances,compliance'
);

Route::post('/transactions/edit/{id}', 'TransactionController@postEdit')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances,compliance'
);
Route::post('/transactions/bank-inc/{id}', 'TransactionController@postMoveBankInc')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances,compliance'
);
Route::post('/transactions/bank-out/{id}', 'TransactionController@postMoveBankOut')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances,compliance'
);

Route::get('/record-transactions', 'TransactionController@recordTransactions');
Route::get('/ign-record-transactions', 'TransactionController@ignRecordTransactions');

Route::get('/error-transactions', 'TransactionController@errorTransactions');
Route::get('/solve-error/{id}', 'TransactionController@getSolveError');
Route::post('/solve-error/{id}', 'TransactionController@postSolveError');
Route::get('/transactions-outgoings/{id}', 'TransactionController@getProfit');

//getNotificationsData
Route::get('/notifications-data', 'DataController@getNotificationsData');
Route::get('/contact-data', 'DataController@getContactData');
Route::get('/first-notification', 'DataController@getFirstNotification');

//traders-data
Route::get('/update-traders-data', 'TradersDataController@updateData');
Route::get('/api/bit-now', 'DataController@getBitNow');

//AntiFraudForm
Route::get('/antifraud-forms', 'AntiFraudController@allForms')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::get('/form-view/{id}', 'AntiFraudController@formView')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::get('/create-antifraud', 'AntiFraudController@getCreate')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::get('/create-antifraud-url', 'AntiFraudController@getCreateUrl')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::get('/antifraud-form/{token}', 'AntiFraudController@getForm');

Route::post('/edit-form/{id}', 'AntiFraudController@postEditAntifraud');

//Routes about rates images generator.
//Route::get('/tools-images', 'ToolsController@generateImages')->middleware(
//'auth',
//'admins-only'
//);
//Route::post('/tools/images-generator/save-foreign', 'ToolsController@saveForeign')->middleware(
//'auth',
//'admins-only'
//);
//Route::post('/tools/images-generator/save-saleUSDven', 'ToolsController@saveVzlaSaleUSD')->middleware('auth',
//    'admins-only');
//Route::post('/tools/images-generator/save-buyUSDven', 'ToolsController@saveVzlaBuyUSD')->middleware('auth',
//    'admins-only');
//Route::get('/tools/images-generator/getInitialValues', 'ToolsController@getInitialValues')->middleware('auth',
//    'admins-only');

Route::get('/a-tests', 'TransactionController@aTest')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);
Route::get('/up-tests', 'TransactionController@upTest')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);
Route::get('/up-tests-form', 'TransactionController@upTestForm')->middleware(
    'auth',
    'role:administrator,admin,trader_master'
);

//getGirosData
Route::get('/giros-data', 'DataController@getGirosData');
Route::get('/get-price', 'DataController@getPrice');
//Wallets
Route::get('/wallets-get-price/{intention?}', 'DataController@walletsGetPrice');
Route::get('/check-hours', 'DataController@checkHours');

//create transaction route
Route::post('/new-transaction', 'UserController@createTransaction');

Route::get('/api/order-messages/{order_id}', 'TransactionOrderController@getMessages')->middleware('auth');
Route::post('/api/create-order-message/{order_id}', 'TransactionOrderController@postCreateMessage')->middleware('auth');
Route::get('/api/get-orders', 'TransactionOrderController@getOrders')->middleware('auth');
Route::get('/api/get-queue/{id}', 'TransactionOrderController@getQueue')->middleware('auth');
Route::post('/api/create-payment-method', 'UserController@createPaymentMethod')->middleware('auth');
Route::post('/api/delete-card', 'UserController@deleteCard')->middleware('auth');
Route::get('/api/getting-user-cards', 'UserController@gettingCards')->middleware('auth');
Route::post('/api/create-destination-account', 'UserController@createDestination')->middleware('auth');
Route::get('/api/getting-destination-accounts', 'UserController@getDestinations')->middleware('auth');
Route::get('/api/getting-my-accounts', 'UserController@getMyDestinations')->middleware('auth');
Route::get('/api/delete-destination', 'UserController@delDestination')->middleware('auth');
Route::post('/make-purchase', 'UserController@makePurchase')->middleware('auth');

Route::get('/send-cash/{id}', 'UserController@sendCash')->middleware('auth');
Route::post('/send-bank-account/{id}', 'TransactionOrderController@createPaymentMessage')->middleware('auth');
Route::post('/cancel-bank-account', 'TransactionOrderController@cancelBankAccount')->middleware('auth');
Route::get('/api/bank-accounts/{id}', 'TransactionOrderController@getBankAccounts')->middleware('auth');
Route::get('/api/bank-accounts-status/{id}', 'TransactionOrderController@getBankAccountsStatus')->middleware('auth');
Route::get('/currencies-tables', 'HelpersController@getCountriesCurrencies');
Route::get('/get-destination-info', 'HelpersController@getDestinationInfo');

Route::get('/user-profiles', 'UserController@getUsersList')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,compliance,support,finances'
);

Route::get('/user-profile/{id}', 'UserController@userProfile')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,compliance,support,finances'
);

Route::get('/tools/due-diligence-pdf', 'ToolsController@generateDueDiligencePDF')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,compliance,support,finances'
);

Route::get('/tools/due-diligence-pdf', 'ToolsController@generateDueDiligencePDF')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,compliance,support,finances'
);


Route::get('/traders', 'UserController@getTradersList')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances'
);

Route::get('/trader-details/{id}', 'UserController@getTrader')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances'
);

Route::get('/transaction-success/{id}', 'UserController@transactionSuccess')->middleware('auth');
Route::post('/transaction-success/{id}', 'UserController@transactionReportPayment')->middleware('auth');

Route::get('/transaction-success-fin/{id}', 'UserController@transactionSuccessFin')->middleware('auth');
Route::get('/transaction-success-cc/{id}', 'UserController@transactionSuccessCC')->middleware('auth');

Route::get('/transaction-all', 'UserController@transactionAll')->middleware('auth');

Route::post('/verify-person-profile/{id}', 'UserController@verifyPerson')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::post('/verify-comp-profile/{id}', 'UserController@verifyCompany')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::resource('bancos', 'BancoController')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::resource('movimientos', 'MovimientoController')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::get('movimientos-bank-to-bank', 'MovimientoController@bankToBank')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::post('movimientos-bank-to-bank', 'MovimientoController@bankToBankStore')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::get('movimientos-create-user', 'MovimientoController@userCreate')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::post('movimientos-create-user', 'MovimientoController@userCreateStore')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);


Route::resource('lotes', 'LoteController')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);



Route::get('transaction-all', 'LoteController@transaction')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::get('lotedetalles/{id}', 'LoteController@lotedetalles')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::get('lotedetalles-cancel/{lote}', 'LoteController@lotedetallesCancel')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::get('lotedetalles-banco/{id}', 'LoteController@lotedetallesBanco')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::get('movimientos-create-user', 'MovimientoController@userCreate')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::get('lotes/{banco}', 'MovimientoController@lotes')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::post('movimientos-create-user', 'MovimientoController@userCreateStore')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);


Route::resource('cuentas', 'CuentaController')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);

Route::get('/exchange-transactions-list', 'TransactionOrderController@getExchangeTransactions')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances,support,compliance'
);

Route::get('/exchange-transaction/{id}', 'TransactionOrderController@getExchange')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances,support,compliance'
);

Route::post('/edit-exchange-transaction/{id}', 'TransactionOrderController@editExchangeTransaction')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/mark-as-payed/{id}', 'TransactionOrderController@markAsPayed')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/new-note-status/{id}', 'TransactionOrderController@setNewNoteStatus')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/new-subject', 'TransactionOrderController@newSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/edit-subject/{id}', 'TransactionOrderController@editSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::get('/delete-subject/{id}', 'TransactionOrderController@deleteSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::Get('statusByID/{id}', 'TransactionOrderController@byCategory');

Route::get('/exchange-pagination', 'TransactionOrderController@exchangePagination')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances,support,compliance'
);

Route::post('/contact', 'UserController@sendContactMessage');

Route::post('/internal-notes-user/{id}', 'UserController@newInternalNote')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/new-subject-user', 'UserController@newSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/edit-subject-user/{id}', 'UserController@editSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::get('/delete-subject-user/{id}', 'UserController@deleteSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::Get('/subjects-user', 'UserController@subjectsUserReject');

Route::post('/change-status/{id}', 'TransactionOrderController@changeStatus')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::get('/change-payed/{id}', 'TransactionOrderController@changePayed')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/assign-exchange/{id}', 'TransactionOrderController@assignExchange')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::get('/help', 'HomeController@getHelp')->middleware('language');
Route::get('/agreement', 'HomeController@getAgreement')->middleware('language');
Route::get('/wallets-agreement', 'HomeController@getWagreement')->middleware('language');
Route::get('/privacy-policies', 'HomeController@getPolicies')->middleware('language');
Route::get('/cookies-policies', 'HomeController@getCookies')->middleware('language');
Route::get('/licenses', 'HomeController@getLicenses')->middleware('language');

Route::post('/payment-data/{id}', 'TransactionOrderController@postPaymentData')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/payment-bank/{id}', 'TransactionOrderController@paymentBank')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);
Route::post('/cryptocurrency-transactions/{id}', 'TransactionOrderController@cryptocurrencyTransactions')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('/cryptocurrency-transactions2/{id}', 'TransactionOrderController@cryptocurrencyTransactions2')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);


Route::post('/payment-bank-incoming/{id}', 'TransactionOrderController@paymentBankIncoming')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);


Route::get('/remaining-alert', 'TransactionController@remainingAlerts');
Route::get('/forgotten-password', 'HomeController@forgotPassword');

Route::post('/forgot-password', 'HomeController@postForgotPassword');

Route::get('/forgotten-password-form', 'UserController@getForgotPassword');
Route::post('/forgot-password-form', 'UserController@postForgotPassword');

Route::get('/authentication', 'UserController@getActivate2FAuth')->middleware('auth', 'language');
Route::post('/set-2fa', 'UserController@postActivate2FAuth')->middleware('auth', 'language');

Route::get('/verify-2fa', 'UserController@verify2f');
Route::get('/verify-2fa-code', 'UserController@verify2fCode');

Route::get('/search-transaction', 'TransactionOrderController@searchTransaction');
Route::get('/transactions-history', 'UserController@transactionsHistory')->middleware('auth');
Route::post('/reply-note/{id}', 'UserController@replyNote')->middleware('auth');
Route::post('/reply-note-incoming/{id}', 'UserController@replyNoteIncoming')->middleware('auth');
Route::get('/tools/gen-t-pdf', 'ToolsController@generateTransactionPdf')->middleware('auth');
Route::get('/transaction-success-image/{id}', 'UserController@transactionSuccessImage')->middleware('auth');

/**
 * Services
 */
Route::get('/services/send-money', 'UserController@servicesSendMoney')->middleware('language');
Route::get('/services/exchange-money', 'UserController@servicesExchangeMoney')->middleware('language');
Route::get('/services/crypto-market', 'UserController@servicesCryptoMarket')->middleware('language');
Route::get('/services/savings', 'UserController@servicesSavings')->middleware('language');
Route::get('/services/transfer-money', 'UserController@servicesTransferMoney')->middleware('language');
Route::get('/services/investments', 'UserController@servicesInvestments')->middleware('language');
Route::get('/convert-money', 'UserController@convertMoney')->middleware('auth');

Route::get('/wallets', 'UserServicesController@getWallets')->middleware('auth');
Route::get('/filter-wallets-transactions', 'UserServicesController@filterWalletTransactions')->middleware('auth');
Route::get('/wallets/charge', 'UserServicesController@getCharge')->middleware('auth');
Route::post('/wallets/charge', 'UserServicesController@postWalletCharge')->middleware('auth');
Route::get('/wallets/send', 'UserServicesController@getSend')->middleware('auth');
Route::get('/wallets/transfer', 'UserServicesController@getTransfer')->middleware('auth');
Route::post('/wallets/transfer', 'UserServicesController@postWalletTransfer')->middleware('auth');
Route::get('/wallets/withdraw', 'UserServicesController@getWithdraw')->middleware('auth');
Route::post('/wallets/withdraw', 'UserServicesController@postWalletWithdraw')->middleware('auth');
Route::get('/wallets/transaction/{id}', 'UserServicesController@getWalletTransaction')->middleware('auth');
Route::get('/wallets/details/{id}', 'UserServicesController@getWalletTransactionDetails')->middleware('auth');
Route::post('/wallets/details/{id}', 'UserServicesController@walletChargeReportPayment')->middleware('auth');
Route::get('/wallets/details-fin/{id}', 'UserServicesController@walletChargeReportPaymentFin')->middleware('auth');

Route::get('/api/wallets/get-orders', 'UserServicesController@getOrders')->middleware('auth');
Route::get('/api/wallets/order-messages/{order_id}', 'UserServicesController@getMessages')->middleware('auth');
Route::post(
    '/api/wallets/create-order-message/{order_id}',
    'UserServicesController@postCreateMessage'
)->middleware('auth');
Route::get('/api/wallets/bank-accounts/{id}', 'UserServicesController@getBankAccounts')->middleware('auth');
Route::get(
    '/api/wallets/bank-accounts-status/{id}',
    'UserServicesController@getBankAccountsStatus'
)->middleware('auth');
Route::get('/api/wallets/get-queue/{id}', 'UserServicesController@getQueue')->middleware('auth');
Route::post('/wallets-cancel-bank-account', 'UserServicesController@cancelBankAccount')->middleware('auth');
Route::post('/wallets-send-bank-account/{id}', 'UserServicesController@createPaymentMessage')->middleware('auth');

Route::get('/wallets/incoming', 'UserServicesController@getIncomingWalletTransactions')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances,support,compliance'
);
Route::get('/wallets/outgoing', 'UserServicesController@getOutgoingWalletTransactions')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances,support,compliance'
);

Route::post('/api/wallets/mark-as-payed/{id}', 'UserServicesController@markAsPayed')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::post('/api/wallets/mark-as-not-payed/{id}', 'UserServicesController@unmarkAsPayed')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::post('/wallets/update-transaction/{id}', 'UserServicesController@updateTransaction')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::get('/api/wallets/transactions-pagination', 'UserServicesController@transactionsPagination');
Route::post('/wallets/assign-transaction/{id}', 'UserServicesController@assignTransaction')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::get('/wallets/transaction-details/{id}', 'UserServicesController@getTransactionDetails')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,support,finances'
);

Route::get('/wallets/dashboard/', 'UserServicesController@getWalletsDashboard')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances,support,compliance'
);

Route::get('/wallets/users/', 'UserServicesController@getWalletsUsers')->middleware(
    'auth',
    'role:administrator,admin,trader_master,finances,support,compliance'
);

Route::Get('wallet/statusByID/{id}', 'UserServicesController@byCategory');

Route::post('wallet/new-note-status/{id}', 'UserServicesController@setNewNoteStatus')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('wallet/new-subject', 'UserServicesController@newSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::post('wallet/edit-subject/{id}', 'UserServicesController@editSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);

Route::get('wallet/delete-subject/{id}', 'UserServicesController@deleteSubject')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master'
);
/**
 * Extend Chat
 */
Route::get('/api/reclaim/{order_id}', 'TransactionOrderController@reclaimOrder')->middleware('auth');

/**
 * New Exchange Rates
 */
Route::get('/get-op-countries', 'HelpersController@getOpCountries');

/**
 * Test
 */
Route::get('/ves-usd-rate', 'ToolsController@vesUsdExchangeRate');
Route::get('/cop-usd-rate', 'ToolsController@copUsdExchangeRate');
Route::get('/get-price-ves', 'DataController@getPriceVes');
Route::get('/get-internal', 'ToolsController@joinInternalMovements');

Route::get('/resolviendo-asignacion', 'TransactionOrderController@resolviendoAssignacion');

/**
 * Profile routes
 */

Route::get('/user-info', 'UserController@getUserInfo')->middleware('auth');
Route::post('/user-info', 'UserController@postUserInfo')->middleware('auth');
Route::post('/user-verificacion-identidad', 'UserController@VeryficacionIdentidad')->middleware('auth');
Route::get('/user-verify-phone', 'UserController@userVerifyPhone')->middleware('auth');
Route::post('/user-verify-phone', 'UserController@userVerifyPhonePost')->middleware('auth');

Route::get('user-succcess', function () {
    return view('profile.success');
});

Route::get('/ubication-info', 'UserController@getUbicationInfo')->middleware('auth');
Route::post('/ubication-info', 'UserController@postUbicationInfo')->middleware('auth');

Route::get('/business-info', 'UserController@getBusinessInfo')->middleware('auth');
Route::post('/business-info', 'UserController@postBusinessInfo')->middleware('auth');

Route::get('/change-password', 'UserController@getChangePassword')->middleware('auth');
Route::post('/change-password', 'UserController@postChangePassword')->middleware('auth');

Route::get('/auth-config', 'UserController@getAuthConfig')->middleware('auth');
Route::post('/auth-config', 'UserController@postAuthConfig')->middleware('auth');
Route::post('/disable-2fa', 'UserController@postDisable2fa')->middleware('auth');

Route::get('/profiles-to-approve', 'UserController@getProfilesToApprove')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);
Route::post('/approve-profile/{id}', 'UserController@getApproveProfile')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);
Route::post('/refuse-profile/{id}', 'UserController@postRefuseProfile')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance'
);
Route::get('/block-user/{id}', 'UserController@getBlockUser')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);
Route::get('/unblock-user/{id}', 'UserController@getUnblockUser')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/download-csv/{status}', 'UserController@downloadCSV')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::post('/open-contact', 'LocalContactController@postOpenContact')->middleware('auth');

//Kick User
Route::get('/kick-user/{id}', 'HomeController@kickuser');

//Simultaneous
Route::get('/get-active-users/{admins?}', 'SimultaneousController@getActiveUsers');
Route::get('/set-idle-user/{admins?}', 'SimultaneousController@setIdleUser');
Route::get('/rm-idle-user/{admins?}', 'SimultaneousController@rmIdleUser');
Route::get('/im-alive', 'SimultaneousController@imAliveUser');
Route::get('/afk-watcher', 'SimultaneousController@afkWatcher'); //CRON
Route::get('/api/get-transaction-data/{order_id}', 'TransactionOrderController@getTransactionData')->middleware('auth');
Route::get(
    '/transfer-chat-user/{operation_id}/{id}',
    'SimultaneousController@transferOperationChat'
)->middleware('auth');

//Wallets new events routes
Route::get(
    '/transfer-chat-user-wallet/{operation_id}/{id}',
    'SimultaneousController@transferWalletsOperationChat'
)->middleware('auth');
//Route::post('/wallets/create-order-message/{order_id}', 'UserServicesController@postCreateMessage')->middleware('auth');

//Tests
Route::get('/test-wt', 'DataController@testWT');

//QB
Route::get('/tame-kerberos', 'ToolsController@tameKerberos');
Route::get('/oauth-kerberos', 'ToolsController@oauthKerberos');
Route::get('/wash-kerberos', 'ToolsController@washKerberos');

//QBTests
//Route::get('/qb-create-card', 'ToolsController@qbTestCreateCard');
//Route::get('/get-charges', 'ToolsController@qbTestChargeObject');

Route::get('/get-csv', 'TransactionOrderController@getCsv')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

//AML-BSA
Route::get('/operaciones/envio', 'AmlBsa@operacionesDeEnvio')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::get('/operaciones/recepcion', 'AmlBsa@operacionesDeRecepcion')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::get('/operaciones/recarga-de-billetera', 'AmlBsa@recargaDeBilletera')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::get('/operaciones/entre-billetera', 'AmlBsa@operacionesEntreBilletera')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::get('/operaciones/compraBTC', 'AmlBsa@compraBtc')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::get('/operaciones/ventaBTC', 'AmlBsa@ventaBTC')->middleware(
    'auth',
    'role:administrator,admin,trader,wallets_trader,trader_master,finances'
);

Route::get('/pagination', 'AmlBsa@pagination')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::post('/send-to-revision', 'AmlBsa@sentRevision')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/send-user-to-revision', 'AmlBsa@sentUserRevision')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/test', 'AmlBsa@test');

Route::get('/get-data-to-user-review', 'AmlBsa@getDataToReview')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::post('/upload-tier-docs/{user}', 'AmlBsa@uploadTierDocs')->middleware(
    'auth'
);
Route::get('/unblock-tier-user/{user}', 'AmlBsa@unblockUser')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/aml/get-data', 'AmlBsa@getData')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/operaciones/tier-settings', 'AmlBsa@tierSettings')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/operaciones/new-tier-level', 'AmlBsa@newTierLevel')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/operaciones/update-tier-level', 'AmlBsa@UpdateTierLevel')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/operaciones/delete-tier-level', 'AmlBsa@deteleTierLevel')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);

Route::get('/operaciones/get-tiers', 'AmlBsa@getTiers')->middleware(
    'auth',
    'role:administrator,admin,trader_master,compliance,support'
);
/**
 * SMS endpoints
 */
Route::get('/user/send-sms-confirmation', [UserController::class, 'sendSmsConfirmation']);
Route::get('/user/confirm-sms-code', [UserController::class, 'confirmSmsCode']);

// Support Center
Route::get('/support', 'SupportController@getIndex')->middleware('language');
Route::get('/my-tickets', 'SupportController@getMyTickets')->middleware('language', 'auth');
Route::get('/create-ticket', 'SupportController@getCreateTicket')->middleware('language');
Route::post('/create-ticket', 'SupportController@postCreateTicket');
Route::post('/get-favorite', 'SupportController@getFavorite');
Route::post('/set-favorite', 'SupportController@postSetFavorite');
Route::get('/ticket/{number}', 'SupportController@getTicket')->middleware('language');
Route::post('/reply-ticket', 'SupportController@postReplyTicket');
//Support Admin
Route::get('/support-seeders', 'SupportController@getSeeders')->middleware('auth','admins-only');
Route::get('/support-admin', 'SupportController@getAdmin')->middleware('auth','admins-only');
Route::get('/get-tickets-count', 'SupportController@getTicketsCount')->middleware('auth','admins-only');
Route::get('/ticket-list', 'SupportController@getTickets')->middleware('auth','admins-only');
Route::get('/get-ticket-details', 'SupportController@getTicketDetails')->middleware('auth','admins-only');
Route::get('/get-last-thread', 'SupportController@getTicketDetails')->middleware('auth','admins-only');
Route::get('/get-agents', 'SupportController@getAgents')->middleware('auth','admins-only');
Route::get('/get-groups', 'SupportController@getGroups')->middleware('auth','admins-only');
Route::get('/get-departments', 'SupportController@getDepartments');
// Route::get('/get-department-name', 'SupportController@getDepartmentName');
Route::post('/new-department', 'SupportController@postNewDepartment')->middleware('auth','admins-only');
Route::post('/edit-department', 'SupportController@postEditDepartment')->middleware('auth','admins-only');
Route::post('/change-department', 'SupportController@postChangeDepartment')->middleware('auth','admins-only');
Route::get('/get-statuses', 'SupportController@getStatuses')->middleware('auth','admins-only');
Route::post('/new-status', 'SupportController@postNewStatus')->middleware('auth','admins-only');
Route::post('/edit-status', 'SupportController@postEditStatus')->middleware('auth','admins-only');
Route::post('/change-status', 'SupportController@postChangeStatus')->middleware('auth','admins-only');
Route::get('/get-priorities', 'SupportController@getPriorities')->middleware('auth','admins-only');
Route::post('/new-priority', 'SupportController@postNewPriority')->middleware('auth','admins-only');
Route::post('/edit-priority', 'SupportController@postEditPriority')->middleware('auth','admins-only');
Route::post('/change-priority', 'SupportController@postChangePriority')->middleware('auth','admins-only');
Route::get('/get-sources', 'SupportController@getSources')->middleware('auth','admins-only');
Route::post('/new-source', 'SupportController@postNewSource')->middleware('auth','admins-only');
Route::post('/edit-source', 'SupportController@postEditSource')->middleware('auth','admins-only');
Route::get('/get-number-codes', 'SupportController@getNumberCodes')->middleware('auth','admins-only');
Route::post('/new-number-code', 'SupportController@postNewNumberCode')->middleware('auth','admins-only');
Route::post('/edit-number-code', 'SupportController@postEditNumberCode')->middleware('auth','admins-only');
Route::get('/get-dues', 'SupportController@getDues')->middleware('auth','admins-only');
Route::post('/new-due', 'SupportController@postNewDue')->middleware('auth','admins-only');
Route::post('/edit-due', 'SupportController@postEditDue')->middleware('auth','admins-only');
Route::post('/delete-config', 'SupportController@postDeleteConfig')->middleware('auth','admins-only');
Route::get('/ticket-action', 'SupportController@postTicketAction')->middleware('auth','admins-only');
Route::get('/agents-admin', 'SupportController@getAgents')->middleware('auth','admins-only');
// Route::get('/ticket-title', 'SupportController@getTicketTitle')->middleware('auth','admins-only');
Route::get('/ticket-body', 'SupportController@getTicketBody')->middleware('auth','admins-only');
// Route::get('/ticket-group', 'SupportController@getTicketGroup')->middleware('auth','admins-only');
Route::get('/ticket-agent', 'SupportController@getTicketAgent')->middleware('auth','admins-only');
Route::get('/get-user-by-id', 'SupportController@getUserById')->middleware('auth','admins-only');
Route::get('/get-tickets-app', 'SupportController@getTicketsApp')->middleware('auth','admins-only');
Route::get('/get-support-app', 'SupportController@getSupportApp')->middleware('auth','admins-only');
Route::get('/get-tickets-now-month', 'SupportController@getTicketsNowMonth')->middleware('auth','admins-only');
Route::get('/get-tickets-today-yesterday', 'SupportController@getTicketsTodayYesterday')->middleware('auth','admins-only');
/**
 * Testing sync
 */
Route::get('/testing/sync', [TestingController::class, 'synchronizeTestingEnvironment']);
