<?php

namespace App\Http\Controllers;

use App\Akb\Banker;
use App\Akb\Toolkit;
use App\BankAccount;
use App\BonusCoupon;
use App\ContactUsMessage;
use App\Credential;
use App\CurrencyWallet;
use App\DestinationAccount;
use App\GenericPaymentsMethods;
use App\Http\Requests\UserInfoRequest;
use App\Mail\ApproveProfile;
use App\Mail\CardConfirm;
use App\Mail\ContactUsMessageEmail;
use App\Mail\RefuseProfile;
use App\Mail\SentYouMoney;
use App\Mail\WelcomeToAmericanKryptosBank;
use App\MarketData;
use App\NotesUserProfile;
use App\Role;
use App\StatusNotes;
use App\StatusNotesIncoming;
use App\SubjectsRejectProfile;
use App\Tarjeta;
use App\Tier;
use App\TierFile;
use App\TierLevel;
use App\User;
use App\UserCompanyProfile;
use App\UserExchangeTransactions;
use App\UserPersonProfile;
use App\UserRegistrationCode;
use App\UserRegistrationCodeUse;
use App\WebsiteSettings;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Pusher\Laravel\Facades\Pusher;
use Redirect as GlobalRedirect;
use Response;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client as TwilioClient;
use URL;

class UserController extends Controller
{

    public function getCreateUser()
    {
        $roles = Role::all();

        return view('user.create')->with(compact('roles'));
    }

    public function postCreateUser()
    {
        $inputs = request()->all();

        $exist_user = User::where('email', $inputs['email'])->first();
        if (is_object($exist_user)) {
            return Redirect::back()->with('error', 'User with the same email already exists.');
        }

        $verification_token = str_random(24);

        $data = [
            'name'               => $inputs['name'],
            'email'              => $inputs['email'],
            'role_id'            => $inputs['role_id'],
            'verification_token' => $verification_token,
            'password'           => $verification_token,
            'is_verified'        => 0,
            'currency'           => $inputs['currency'],
        ];

        $user = User::create($data);

        $verification_link = URL::to('verify-account?token=' . $user->verification_token);
        $data              = [
            'url'       => URL::to('verify-account?token=' . $user->verification_token),
            'user_name' => $inputs['name'],
        ];
        Mail::to($data)->send(new WelcomeToAmericanKryptosBank($verification_link));

        return Redirect::back()->with('success', 'User has been created.');
    }

    public function getVerifyAccount()
    {
        if (request()->has('token')) {
            $user  = User::where('verification_token', request()->get('token'))->first();
            $token = request()->get('token');

            if (is_object($user)) {
                if ($user->is_verified == 0) {
                    return Redirect::to('/signin')->with('success', 'Tu cuenta ha sido verificada.');
                }

                return response()->json(['status' => 'error', 'message' => 'Account already verified.']);
            }

            return response()->json(['status' => 'error', 'message' => 'Can not find user.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid request.']);
    }

    public function postVerifyAccount()
    {
        $inputs = request();

        if (!preg_match('((?=.*\d)(?=.*[A-Z])(?=.*[@#$%]).{6,20})', $inputs->password)) {
            return Redirect::back()->with(
                'error',
                'The new password must have a minimum of 8 characters and must contain an uppercase letter and a special symbol.'
            );
        }

        if ($inputs->password != '' && $inputs->password_verification != '') {
            if ($inputs->password == $inputs->password_verification) {
                $user              = User::where('verification_token', $inputs->verification_token)->where(
                    'email',
                    $inputs->email
                )->first();
                $user->password    = Hash::make($inputs->password);
                $user->is_verified = 1;
                $user->save();
            } else {
                return Redirect::back()->with('error', 'New password and confirm password did not match.');
            }
        } else {
            return Redirect::back()->with('error', 'New password and confirm password must not be empty.');
        }

        return Redirect::to('/login')->with('success', 'Your account has been confrimed');
    }

    /**
     * @return Factory|View
     */
    public function getSettings()
    {
        $credentials       = Credential::all();
        $settings          = WebsiteSettings::find(1);
        $exchangeRatesFees = [
            'to_ven'                       => env('FEE_TO_VEN'),
            'from_ven'                     => env('FEE_FROM_VEN'),
            'fee_general'                  => env('FEE_GENERAL'),
            'wallets_from_ven'             => env('WALLETS_FEE_FROM_VEN'),
            'wallets_to_ven'               => env('WALLETS_FEE_TO_VEN'),
            'wallets_usd_usd_charge'       => env('WALLETS_USD_USD_CHARGE'),
            'wallets_usd_usd_withdraw'     => env('WALLETS_USD_USD_WITHDRAW'),
            'wallets_fee_general_charge'   => env('WALLETS_FEE_GENERAL_CHARGE'),
            'wallets_fee_general_withdraw' => env('WALLETS_FEE_GENERAL_WITHDRAW'),
        ];
        //        $exchangePriceData = Banker::getPrice($inputs);
        //        $exchangePrice     = $exchangePriceData[0];
        $exchangeRatesPrices = [
            'to_ven'                   => Banker::getPrice([
                'sender'           => 'USD',
                'sender_country'   => 'United States',
                'amount'           => 150,
                'receiver'         => 'VES',
                'receiver_country' => 'Venezuela',
            ])[0],
            'from_ven'                 => 1 / Banker::getPrice([
                'sender'           => 'VES',
                'sender_country'   => 'Venezuela',
                'amount'           => 999999999,
                'receiver'         => 'USD',
                'receiver_country' => 'United States',
            ])[0],
            'wallets_to_ven'           => Banker::walletsGetPrice([
                'sender'           => 'USD',
                'sender_country'   => 'United States',
                'amount'           => 150,
                'receiver'         => 'VES',
                'receiver_country' => 'Venezuela',
            ])[0],
            'wallets_from_ven'         => 1 / Banker::walletsGetPrice([
                'sender'           => 'VES',
                'sender_country'   => 'Venezuela',
                'amount'           => 999999999,
                'receiver'         => 'USD',
                'receiver_country' => 'United States',
            ])[0],
            'wallets_usd_usd_charge'   => Banker::walletsGetPrice(
                [
                    'sender'           => 'USD',
                    'sender_country'   => 'United States',
                    'amount'           => 1,
                    'receiver'         => 'USD',
                    'receiver_country' => 'United States',
                ]
            )[0],
            'wallets_usd_usd_withdraw' => Banker::walletsGetPrice(
                [
                    'sender'           => 'USD',
                    'sender_country'   => 'United States',
                    'amount'           => 1,
                    'receiver'         => 'USD',
                    'receiver_country' => 'United States',
                ],
                'withdraw'
            )[0],
        ];

        return view('user.user-settings')->with(compact(
            'credentials',
            'settings',
            'exchangeRatesFees',
            'exchangeRatesPrices'
        ));
    }

    /**
     * @return RedirectResponse
     */
    public function postSettings(): RedirectResponse
    {
        $inputs            = request();
        $exchangeRatesFees = [
            'FEE_TO_VEN'                   => $inputs->to_ven,
            'FEE_FROM_VEN'                 => $inputs->from_ven,
            'FEE_GENERAL'                  => $inputs->fee_general,
            'WALLETS_FEE_TO_VEN'           => $inputs->wallets_to_ven,
            'WALLETS_FEE_FROM_VEN'         => $inputs->wallets_from_ven,
            'WALLETS_USD_USD_CHARGE'       => $inputs->wallets_usd_usd_charge,
            'WALLETS_USD_USD_WITHDRAW'     => $inputs->wallets_usd_usd_withdraw,
            'WALLETS_FEE_GENERAL_CHARGE'   => $inputs->wallets_fee_general_charge,
            'WALLETS_FEE_GENERAL_WITHDRAW' => $inputs->wallets_fee_general_withdraw,
        ];

        Toolkit::changeEnv($exchangeRatesFees);
        DB::table('credentials')->update(['is_active' => 0]);

        Credential::where('env_number', $inputs->local_env_number)->update(['is_active' => 1]);
        $websiteSettings = WebsiteSettings::find(1);
        $websiteSettings->switchStatus($inputs->is_active);

        //Notification::setLastNotification();

        return Redirect::to('/user-settings')->with('success', 'User has been updated.');
    }

    /**
     * @return Factory|View
     */
    public function getMerchantSettings()
    {
        $personProfile  = UserPersonProfile::where(['user_id' => Auth::user()->id])->with('user')->first();
        $companyProfile = UserCompanyProfile::where(['user_id' => Auth::user()->id])->with('user')->first() ??
            new UserCompanyProfile();

        //        var_dump($personProfile);
        //        var_dump($companyProfile);
        //        die;

        if (!is_null(Auth::user()->google2fa_secret)) {
            // Initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');

            //setting up
            $QR_image = $google2fa->getQRCodeInline(
                'American Kryptos Bank',
                Auth::user()->email,
                Auth::user()->google2fa_secret
            );

            return view('user.merchant-settings')->with(compact('personProfile', 'companyProfile', 'QR_image'));
        }

        return view('user.merchant-settings')->with(compact('personProfile', 'companyProfile'));
    }

    /**
     * @return RedirectResponse
     */
    public function postMerchantSettings(): RedirectResponse
    {
        $inputs = request()->all();
        if (isset($inputs['UserPersonProfile'])) {
            $userPersonProfile = UserPersonProfile::where(['id' => $inputs['UserPersonProfile']['id']])->first();
            $userPersonProfile->fill($inputs['UserPersonProfile']);
            $userPersonProfile->mobile = $inputs['UserPersonProfile']['pre-mobile'] . ' ' . $inputs['UserPersonProfile']['main-mobile'];

            if (isset($inputs['UserPersonProfile']['selfie'])) {
                $path                      = Storage::disk('public')->putFile(
                    'profile-files',
                    $inputs['UserPersonProfile']['selfie']
                );
                $userPersonProfile->selfie = 'storage/' . $path;
            }

            if (isset($inputs['UserPersonProfile']['id_confirmation'])) {
                $path                               = Storage::disk('public')->putFile(
                    'profile-files',
                    $inputs['UserPersonProfile']['id_confirmation']
                );
                $userPersonProfile->id_confirmation = 'storage/' . $path;
            }

            $userPersonProfile->save();
        }

        if (isset($inputs['User'])) {
            $user = User::where('email', Auth::user()->email)->first();
            if (isset($inputs['User']['email']) && $inputs['User']['email'] !== Auth::user()->email) {
                $user->email = $inputs['User']['email'];
                $user->save();
            }

            if (isset($inputs['User']['password']) && $inputs['User']['password'] !== null) {
                $user->password = Hash::make($inputs['User']['password']);
                $user->save();
            }
        }

        if (isset($inputs['UserCompanyProfile'])) {
            $userCompanyProfile = UserCompanyProfile::where(['user_id' => Auth::user()->id])->with('user')->first() ??
                new UserCompanyProfile();
            $userCompanyProfile->fill($inputs['UserCompanyProfile']);
            $userCompanyProfile->user_id      = Auth::user()->id;
            $userCompanyProfile->status       = 0;
            $userCompanyProfile->mobile       = $inputs['UserCompanyProfile']['pre-mobile'] . ' ' .
                $inputs['UserCompanyProfile']['main-mobile'];
            $userCompanyProfile->office_phone = $inputs['UserCompanyProfile']['pre-office_phone'] . ' ' .
                $inputs['UserCompanyProfile']['main-office_phone'];

            if (isset($inputs['UserCompanyProfile']['logo'])) {
                $path                     = Storage::disk('public')->putFile(
                    'profile-files',
                    $inputs['UserCompanyProfile']['logo']
                );
                $userCompanyProfile->logo = 'storage/' . $path;
            }

            if (isset($inputs['UserCompanyProfile']['id_confirmation'])) {
                $path                                = Storage::disk('public')->putFile(
                    'profile-files',
                    $inputs['UserCompanyProfile']['id_confirmation']
                );
                $userCompanyProfile->id_confirmation = 'storage/' . $path;
            }

            $userCompanyProfile->save();
        }

        return Redirect::to('/settings')->with('success', 'User has been updated.');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegister()
    {
        $inputs = request()->all();

        $exist_user = User::where('email', $inputs['email'])->first();
        if (is_object($exist_user)) {
            return Redirect::back()->with('email', 'User with the same email already exists.');
        }

        return 'xd';
    }

    /**
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createTransaction()
    {
        if (Auth::user() === null) {
            return Redirect('/signin');
        }

        return Redirect('/send-money');
    }

    /**
     * @return RedirectResponse
     */
    public function newMerchant(): RedirectResponse
    {
        $inputs = request()->all();
        $code   = null;

        if ($inputs['password'] === '') {
            //|| !preg_match('((?=.*\d)(?=.*[A-Z])(?=.*[@#$%]).{6,20})', $inputs['password'])) {
            return Redirect('/signin')->with(
                'error',
                'Password must have a minimum of 8 characters and must contain an uppercase letter and a special symbol.'
            );
        }

        $exist_user = User::where('email', $inputs['email'])->first();
        if (is_object($exist_user)) {
            return Redirect('/signin')->with('error', 'User with the same email already exists.');
        }

        if (isset($inputs['code']) || $inputs['code'] !== null) {
            $code = UserRegistrationCode::find($inputs['code']) ??
                UserRegistrationCode::where(['code' => $inputs['code']])->first();
            if ($code && $code->is_disabled) {
                return Redirect('/signin')->with('error', 'Éste código promocional está deshabilitado.');
            }
        }

        $verification_token = str_random(24);
        $data               = [
            'name'               => $inputs['first-name'] . ' ' . $inputs['last-name'],
            'email'              => $inputs['email'],
            'role_id'            => 5,
            'verification_token' => $verification_token,
            'password'           => Hash::make($inputs['password']),
            'is_verified'        => 0,
            'currency'           => 'All Currencies',
        ];
        $user               = User::create($data);
        $profileData        = [
            'user_id'    => $user->id,
            'first_name' => $inputs['first-name'],
            'last_name'  => $inputs['last-name'],
            'email'      => $inputs['email'],
        ];
        UserPersonProfile::create($profileData);

        $data = ['url' => URL::to('verify-account?token=' . $verification_token), 'name' => $inputs['first-name']];
        Mail::to($inputs['email'])->send(new WelcomeToAmericanKryptosBank($data));

        //        $verification_link = URL::to('verify-account?token=' . $user->verification_token);
        //        Mail::to($inputs['email'])->send(new WelcomeToAmericanKryptosBank($verification_link));

        //        if (isset($inputs['sender'])) {
        //            $date = Carbon::now();
        //            $date = Carbon::now();
        //
        //            TransactionOrder::create([
        //                'amount'        => $inputs['to_send'],
        //                'sender'        => $inputs['sender'],
        //                'receiver'      => $inputs['receiver'],
        //                'to_send'       => $inputs['to_send'],
        //                'to_receive'    => $inputs['to_send'] * $inputs['recomended_price'] * 0.96,
        //                'spending_date' => $date,
        //                'user_id'       => $user->id,
        //            ]);
        //
        //            Pusher::trigger('my-channel', 'transaction-order',
        //                ['message' => $user->name . ' has created a new transaction order.']);
        //
        //        }

        if ($code) {
            $code_use          = new UserRegistrationCodeUse();
            $code_use->user_id = $user->id;
            $code_use->code_id = $code->id;
            $code_use->save();
        }

        Auth::login($user);

        //return Redirect::to('/')->with('success', 'User has been created.');
        return Redirect::to('/send-money');
    }

    public function sendMoney()
    {
        $user = Auth::user();

        if ($user->personProfile->approval_status !== 2) {
            return Redirect::to('/user-info');
        }

        $activeTransaction = UserExchangeTransactions::where(
            [
                'user_id' => $user->id,
                'status'  => 0,
            ]
        )->orderBy('created_at', 'DESC')->first();

        if ($activeTransaction && $activeTransaction->payment_way === 'cash_deposit') {
            return Redirect::to('/send-cash/' . $activeTransaction->id)->with('error', 'Posee una transacción activa.');
        }

        $qbPay = env('QBPAY') === '1';

        //If deserves Bonus
        $bonus            = 1;
        $codeUse          = UserRegistrationCodeUse::where(['user_id' => $user->id])->first();
        $userTransactions = UserExchangeTransactions::where(['user_id' => $user->id])
            ->orderBy('created_at', 'DESC')
            ->with('walletTransaction')
            ->get();
        $higherSend       = $this->getHigherSend($userTransactions);

        if ($codeUse && ($userTransactions === null || $higherSend < 100)) {
            $bonus = 0;
        }

        return view('user.send-money')->with(compact('qbPay', 'bonus'));
    }

    /**
     * @param $transactions
     *
     * @return int
     */
    public function getHigherSend($transactions): int
    {
        if ($transactions === null) {
            return 0;
        }

        $maxNum = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->walletTransaction !== null && $transaction->walletTransaction->amount > $maxNum) {
                $maxNum = $transaction->walletTransaction->amount;
            }
        }

        return $maxNum;
    }

    public function createPaymentMethod()
    {
        $inputs = request()->all();

        Stripe::setApiKey(env('STRIPE_PRIVATE_KEY'));

        $user = Auth::user();

        if (Auth::user()->stripe_id === null) {
            try {
                $customer = \Stripe\Customer::create([
                    'email'  => Auth::user()->email,
                    'source' => $inputs['params']['stripe_token'],
                ]);

                $user->stripe_id = $customer->id;
                $user->save();
            } catch (Card $e) {
                return ['error' => $e->getMessage()];
            }
        } else {
            try {
                \Stripe\Customer::createSource(
                    $user->stripe_id,
                    [
                        'source' => $inputs['params']['stripe_token'],
                    ]
                );
            } catch (Card $e) {
                return ['error' => $e->getMessage()];
            }

            $customer = \Stripe\Customer::retrieve($user->stripe_id);
            return $customer->sources->data;
        }

    }

    public function deleteCard()
    {
        $inputs = request()->all();
        Stripe::setApiKey(env('STRIPE_PRIVATE_KEY'));
        $user = Auth::user();

        try {
            \Stripe\Customer::deleteSource(
                $user->stripe_id,
                $inputs['id']
            );
        } catch (Card $e) {
            return ['error' => $e->getMessage()];
        }

        $customer = \Stripe\Customer::retrieve($user->stripe_id);

        return $customer->sources->data;
    }

    public function gettingCards()
    {
        Stripe::setApiKey(env('STRIPE_PRIVATE_KEY'));

        if (is_null(Auth::user()->stripe_id)) {

            return [];
        } else {

            $customer = \Stripe\Customer::retrieve(Auth::user()->stripe_id);

            return $customer->sources->data;
        }
    }

    /**
     * @return array
     */
    public function createDestination(): array
    {
        $inputs                 = request()->all();
        $inputs['user_id']      = Auth::user()->id;
        $inputs['phone_number'] = $inputs['pre-mobile'] . ' ' . $inputs['main-mobile'];

        if (isset($inputs['pre-office_phone'])) {
            $inputs['office_phone_number'] = $inputs['pre-office_phone'] . ' ' . $inputs['main-office_phone'];
        }

        if (isset($inputs['birthday'])) {
            $inputs['birthday'] = Carbon::createFromFormat('m/d/Y', $inputs['birthday'])->toDateTimeString();
        }

        if (isset($inputs['id_origin_date'])) {
            $inputs['id_origin_date'] = Carbon::createFromFormat(
                'm/d/Y',
                $inputs['id_origin_date']
            )->toDateTimeString();
        }

        if (isset($inputs['id_end_date'])) {
            $inputs['id_end_date'] = Carbon::createFromFormat('m/d/Y', $inputs['id_end_date'])->toDateTimeString();
        }

        if (isset($inputs['id'])) {
            $destinationAccount = DestinationAccount::find($inputs['id']);
            $destinationAccount->fill($inputs);
            $destinationAccount->save();
        } else {
            DestinationAccount::create($inputs);
        }

        $destinationAccounts = DestinationAccount::where(
            [
                'user_id'  => Auth::user()->id,
                'currency' => $inputs['currency'],
            ]
        )->get();
        $arrayToReturn       = [];

        foreach ($destinationAccounts as $destinationAccount) {
            $arrayToReturn[$destinationAccount->type][] = $destinationAccount;
        }

        return $arrayToReturn;
    }

    /**
     * @return array
     */
    public function getDestinations(): array
    {
        $inputs = request()->all();

        $destinationAccounts = DestinationAccount::where(
            [
                'user_id'  => Auth::user()->id,
                'currency' => $inputs['currency'],
            ]
        )->get();
        $arrayToReturn       = [];

        foreach ($destinationAccounts as $destinationAccount) {
            $arrayToReturn[$destinationAccount->type][] = $destinationAccount;
        }

        return $arrayToReturn;
    }

    /**
     * @return array
     */
    public function getMyDestinations(): array
    {
        $inputs = request()->all();

        $destinationAccounts = DestinationAccount::where(
            [
                'user_id'  => Auth::user()->id,
                'currency' => $inputs['currency'],
                'email'    => Auth::user()->email,
            ]
        )->get();
        $arrayToReturn       = [];

        foreach ($destinationAccounts as $destinationAccount) {
            $arrayToReturn[$destinationAccount->type][] = $destinationAccount;
        }

        return $arrayToReturn;
    }

    /**
     * @return array
     */
    public function delDestination(): array
    {
        $inputs  = request()->all();
        $account = DestinationAccount::find($inputs['id']);
        $account->delete();

        $destinationAccounts = DestinationAccount::where(
            [
                'user_id'  => Auth::user()->id,
                'currency' => $inputs['currency'],
            ]
        )->get();
        $arrayToReturn       = [];

        foreach ($destinationAccounts as $destinationAccount) {
            $arrayToReturn[$destinationAccount->type][] = $destinationAccount;
        }

        return $arrayToReturn;
    }

    private function amountValidation($to_send, $sender)
    {
        if ($sender === 'USD' && $to_send >= 15.00) {

            return false;
        } elseif ($sender !== 'VES') {

            $exchange_data = MarketData::getExchangeRates();

            if ($to_send / $exchange_data[$sender] >= 15.00) {
                return false;
            }
        } elseif ($sender === 'VES') {
            $exchangePriceData = Banker::getExchangeRate(
                'VES',
                'Venezuela',
                'USD',
                'United States'
            )[0];
            $price             = $exchangePriceData->exchange_rate;
            $akbFee            = env('FEE_FROM_VEN'); //TODO dynamic
            $fee               = ($price * $akbFee) / 100;
            $price             -= $fee;

            if ($to_send / $price >= 15) {
                return false;
            }
        }

        return true;
    }


    public function confirmCard()
    {
        return view('services.confim-card');
    }

    public function confirmCardStore(Request $request)
    {
        $request->validate([
            'expiry' => 'required|min:7'

        ]);


        $ultimos4Digitos = substr($request->number, -4);

        $exis = Tarjeta::where('numero', $ultimos4Digitos)->where('user_id', Auth::id())->where(
            'verified',
            false
        )->first();

        if ($exis) {
            return redirect()->back()->withErrors(['number' => 'Esta tarjeta esta en proceso de verificación'])->withInput();;
        }


        $tarjeta = new Tarjeta;

        if (isset($request->photo_text)) {

            $path = Storage::disk('public')->putFile(
                'tarjetas',
                $request->photo_text
            );

            $fotoUrl = 'storage/' . $path;
        }

        $tarjeta->user_id = Auth::id();
        $tarjeta->numero  = $ultimos4Digitos;
        $tarjeta->nombre  = $request->name;
        $tarjeta->expiry  = $request->expiry;
        $tarjeta->cvc     = $request->cvc;
        $tarjeta->foto    = $fotoUrl;
        $tarjeta->save();

        return Redirect::back()->with(
            'success',
            'La tarjeta esta en proceso de verificación le notificaremos por correo su activación'
        );
    }

    public function paymentCard($inputs, $userNewTransaction, $banker, $walletID, $isoCurrency)
    {


        $userNewTransaction->payment_way    = 'Pago123';
        $userNewTransaction->payment_source = 'Pago123';
        $userNewTransaction->status         = 0;
        $userNewTransaction->save();

        if ($inputs['sender'] !== 'USD') {


            $exchangeRates = HelpersController::getExchangeRates();
            $usdToCharge   = $inputs['to_send'] / $exchangeRates['rates'][$inputs['sender']];
            $usdBridge     = $inputs['to_receive'];
            if ($inputs['receiver'] !== 'USD') {
                $usdBridge = (float)str_replace(
                    ',',
                    '',
                    $inputs['to_receive']
                ) / $exchangeRates['rates'][$inputs['receiver']];
            }

            if ($inputs['receiver'] === 'VES') {
                $usdBridge = $usdToCharge;
            }

            $fees = HelpersController::calculateFees(
                $usdToCharge,
                'card',
                $inputs['pay_method_country'],
                $exchangeRates['rates'][$inputs['sender']],
                $usdBridge,
                env('FEE_123PAGO') ? true : null
            );
        } else {

            $fees        = HelpersController::calculateFees(
                $inputs['to_send'],
                'card',
                $inputs['pay_method_country']
            );
            $usdToCharge = $inputs['to_send'];
        }

        //dd($inputs, $fees , $usdToCharge,$inputs['to_send'] , $exchangeRates['rates'][$inputs['sender']],  $exchangeRates['rates']['VEF']  );

        $m       = $inputs['qb_card']['card_month'];
        $y       = substr($inputs['qb_card']['card_year'], -2);
        $f_array = $m . $y;

        $cedulaCliente = Auth::user()->personProfile->id_number;
        $nombreCliente = Auth::user()->name;
        $emailCliente  = Auth::user()->email;
        $nai           = $userNewTransaction->id;            //ID DE LA ORDEN
        $idCobro       = uniqid();                           //Identificación de la operación de cobro-pago, es totalmente numérico
        $direccion     = Auth::user()->personProfile->state; //1-500
        $nuTarjeta     = $inputs['qb_card']['cardNumber'];
        $cvv2          = $inputs['qb_card']['card_cvv'];;
        $fechven     = $f_array;
        $nbproveedor = 'AKB';
        $concepto    = 'AKB';
        $monto       = $fees[1];
        $idpromocion = '2';
        $ms_factura  = uniqid(); //Número de la Factura de Pago
        $control     = uniqid(); //Número de Control de la Operación de Pago
        $telefono    = Auth::user()->personProfile->mobile;
        $api_key     = 'a0998436c691f61e26bc9ec00960cefe';

        /*  
        prueba a través de https://sandbox.123pago.net/ms_report/
        Accesos:
        Usuario: gsq@mericankryptosbank.com
        Contraseña: abc123
        //TARJETA SUCCESS = 6011601160116611 4032033168625759

       */
        //dd( env('APP_DEBUG') === false ? "https://sandbox.123pago.net/ms_123pagoPOSEngineREST_V2.0/webresources/pagar/pagarVPOS" : "https://payment.123pago.net/ms_123pagoPOSEngineREST_V2.0/webresources/pagar/pagarVPOS");
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => env('APP_DEBUG') === true ? "https://sandbox.123pago.net/ms_123pagoPOSEngineREST_V2.0/webresources/pagar/pagarVPOS" : "https://payment.123pago.net/ms_123pagoPOSEngineREST_V2.0/webresources/pagar/pagarVPOS",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => " {\n\"cedulaCliente\": \"$cedulaCliente\",\n\"nombreCliente\": \"$nombreCliente\",\n\"emailCliente\": \" $emailCliente  \",
            \n\"nai\": \"$nai\",\n\"idCobro\": \"$idCobro\",\n\"direccion\": \"$direccion\",\n\"nuTarjeta\": \"$nuTarjeta\",
            \n\"cvv2\": \"$cvv2\",\n\"fechven\": \"$fechven\",\n\"nbproveedor\": \"$nbproveedor\",\n\"concepto\": \"$concepto\",\n\"monto\":   $monto,
            \n\"idpromocion\": \"$idpromocion\",\n\"ms_factura\": \"$ms_factura\",\n\"control\": \"$control\",\n\"telefono\": \"$telefono\",
            \n\"api_key\": \"$api_key\"\n \n}",
                CURLOPT_HTTPHEADER     => [
                    "cache-control: no-cache",
                    "content-type: application/json"
                ],
            ]
        );

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $json     = json_decode($response);

        curl_close($curl);

        if ($err) {

            $userNewTransaction->status     = 3;
            $userNewTransaction->is_revised = 1;
            $userNewTransaction->failed_at  = Carbon::now()->toDateTimeString();
            $userNewTransaction->failed_by  = 'Operación Fallida Servidor pago123';
            $userNewTransaction->update();


            return Redirect::to('/send-money')->with('error', 'Operación Fallida');
        }

        //Si un IF retorna algo, el ELSE no se va a ejecutar, no es necesario, es mejor continuar el flow normalmente
        //Se usa el IF {} ELSE {}, evitar el IF: ELSE: ENDIF;

        if ($json->cod_transaccion === '00') {

            $userNewTransaction->status      = 0;
            $userNewTransaction->is_revised  = 0;
            $userNewTransaction->is_payed    = 1;
            $userNewTransaction->payed_at    = Carbon::now()->toDateTimeString();
            $userNewTransaction->payed_by    = $json->referencia;
            $userNewTransaction->stripe_data = json_encode($json);

            //Create reload Wallet Transaction
            $walletTransactionID = $banker->rechargeWalletCredits(
                $walletID,
                $usdToCharge,
                $isoCurrency,
                'Pago123',
                null,
                $inputs
            );

            $userNewTransaction->wallet_transaction_id = $walletTransactionID;
            $userNewTransaction->save();

            //Create hold Wallet Transaction
            $banker->holdWalletCredits(
                $walletID,
                $usdToCharge,
                $isoCurrency,
                null,
                $walletTransactionID,
                2,
                'Pago123',
                null,
                $inputs
            );

            Pusher::trigger(
                'my-channel',
                'transaction-order',
                ['message' => Auth::user()->name . ' has created a new transaction order.']
            );

            if (isset($inputs['bonus_amount'])) {

                $account = DestinationAccount::find($inputs['destination_id']);

                BonusCoupon::create([
                    'merchant_id'          => Auth::user()->id,
                    'transaction_id'       => $userNewTransaction->id,
                    'receiver_id_document' => $account->id_number,
                    'receiver_account'     => $account->account_number,
                ]);
            }

            $account = DestinationAccount::find($inputs['destination_id']);

            $this->sendDestinationMail(
                $account->email,
                $account->name . ' ' . $account->lastname,
                $userNewTransaction->receiver_fiat_amount,
                $inputs['receiver']
            );

            return Redirect::to('/transaction-success-cc/' . $userNewTransaction->id);

            /*FIN AQUI CUANDO SE HACE EL CARGO*/
        }

        $userNewTransaction->status     = 3;
        $userNewTransaction->is_revised = 1;
        $userNewTransaction->failed_at  = Carbon::now()->toDateTimeString();
        $userNewTransaction->failed_by  = 'Operación Fallida ' . $json->mensaje_respuesta;
        $userNewTransaction->update();

        return Redirect::to('/send-money')->with('error', $json->mensaje_respuesta);

        // dd($usdToCharge,$fees);
    }

    public function makePurchase(): RedirectResponse
    {

        //If has a chat active, redirect to it, else will retourn null and code continues.
        $this->validateActiveChat();


        $inputs                     = request()->all();
        $inputs['exchange_related'] = 1;
        $banker                     = new Banker;

        if ($inputs['paymentMethod'] === 'card') {
            if (tarjetaActiva($inputs) === null) {
                return redirect('confirm-card')->with(
                    'error',
                    'Esta tarjeta no esta confirmada para operar en nuestra plataforma'
                );
            }
        }


        if ($banker::amountValidation((float)str_replace(',', '', $inputs['to_send']), $inputs['sender'])) {
            return Redirect::back()->with('error', 'El monto a enviar debe ser igual o mayor a 15 USD o equivalente.');
        }

        if ((float)str_replace(',', '', $inputs['to_send']) < 1) {
            return Redirect::back()->with('error', 'Ingrese monto a enviar.');
        }

        //cross site scripting validation
        $inputs['to_send'] = (float)str_replace(',', '', $inputs['to_send']);
        $inputs['amount']  = $inputs['to_send'];
        $exchangePriceData = Banker::getPrice($inputs);
        $exchangePrice     = $exchangePriceData[0];
        $controlToReceive  = round($inputs['to_send'] * $exchangePriceData[0], 2);
        $to_receive_float  = (float)str_replace(',', '', $inputs['to_receive']);

        if ((string)$to_receive_float !== (string)$controlToReceive) {
            return Redirect::back()->with(
                'error',
                'El monto a recibir es incorrecto. Por favor, revise e intente de nuevamente'
            );
        }

        if (isset($inputs['bonus_amount'])) {

            $bonus_validation = $this->bonusCouponValidation($inputs['destination_id']);

            if ($bonus_validation['error']) {
                return Redirect::back()->with('error', $bonus_validation['msg']);
            }

            $inputs['to_receive'] = round(
                (float)str_replace(
                    ',',
                    '',
                    $inputs['to_receive']
                )
                    +
                    (float)str_replace(
                        ',',
                        '',
                        $inputs['bonus_amount']
                    ),
                2
            );
        }

        $banker::checkEnableToOperate($inputs);

        $userNewTransaction                      = new UserExchangeTransactions();
        $userNewTransaction->exchange_rate_id    = $exchangePriceData[2];
        $userNewTransaction->tracking_id         = $inputs['tracking_id'];
        $userNewTransaction->user_id             = Auth::user()->id;
        $userNewTransaction->destination_account = $inputs['destination_id'];

        //JSON registration
        $userNewTransaction->destination_account_json = DestinationAccount::find($inputs['destination_id']);
        //END

        $userNewTransaction->sender_fiat          = $inputs['sender'];
        $userNewTransaction->sender_fiat_amount   = round(
            (float)str_replace(',', '', $inputs['to_send']),
            2
        );
        $userNewTransaction->receiver_fiat        = $inputs['receiver'];
        $userNewTransaction->receiver_fiat_amount = round(
            (float)str_replace(',', '', $inputs['to_receive']),
            2
        );
        $userNewTransaction->exchange_rate        = round($exchangePrice, 2);
        $userNewTransaction->fee_at_moment        = $exchangePriceData[3];

        /**
         * Wallet Operation
         */
        //Create Wallet or get the existing one
        if ($inputs['sender'] === 'EUR' || $inputs['sender'] === 'GBP') {
            $isoCurrency = $inputs['sender'];
            $walletID    = $banker->createWallet($inputs['sender']);
        } else {
            $isoCurrency = 'USD';
            $walletID    = $banker->createWallet();
        }

        if ($inputs['paymentMethod'] === 'card') {


            return $this->paymentCard($inputs, $userNewTransaction, $banker, $walletID, $isoCurrency);
        }


        if ($inputs['paymentMethod'] === 'userWallet') {
            //Maybe No
            $userNewTransaction->payment_way    = $inputs['paymentMethod'];
            $userNewTransaction->metadata       = ['wallet_hash' => $inputs['walletHash']];
            $userNewTransaction->payment_source = 'User Wallet';
            $userNewTransaction->status         = 0;
            $userNewTransaction->is_revised     = 0;
            $userNewTransaction->is_payed       = 1;
            $userNewTransaction->payed_at       = Carbon::now()->toDateTimeString();
            $userNewTransaction->payed_by       = 'User Wallet: ' . $inputs['walletHash'];

            if ($inputs['sender'] !== 'USD') {
                $exchangeRates = HelpersController::getExchangeRates();
                $usdToCharge   = $inputs['to_send'] / $exchangeRates['rates'][$inputs['sender']];
            } else {
                $usdToCharge = $inputs['to_send'];
            }

            //Create hold Wallet Transaction
            $walletTransactionID = $banker->holdWalletCredits(
                $walletID,
                $usdToCharge,
                $isoCurrency,
                $userNewTransaction->payment_way,
                null,
                2
            );

            $userNewTransaction->wallet_transaction_id = $walletTransactionID;
            $userNewTransaction->save();

            Pusher::trigger(
                'my-channel',
                'transaction-order',
                ['message' => Auth::user()->name . ' has created a new transaction order.']
            );

            $account = DestinationAccount::find($inputs['destination_id']);

            $this->sendDestinationMail(
                $account->email,
                $account->name . ' ' . $account->lastname,
                $userNewTransaction->receiver_fiat_amount,
                $inputs['receiver']
            );

            return Redirect::to('/transaction-success/' . $userNewTransaction->id);
        }


        if ($inputs['paymentMethod'] !== 'card' && $inputs['paymentMethod'] !== 'cash' && $inputs['paymentMethod'] !== 'userWallet') {
            $userNewTransaction->payment_way    = $inputs['paymentMethod'];
            $userNewTransaction->payment_source = ucwords($inputs['paymentMethod']);
            $userNewTransaction->status         = 0;
            $userNewTransaction->is_revised     = 0;

            if ($inputs['sender'] !== 'USD') {
                $exchangeRates = HelpersController::getExchangeRates();
                $usdToCharge   = $inputs['to_send'] / $exchangeRates['rates'][$inputs['sender']];
            } else {
                $usdToCharge = $inputs['to_send'];
            }

            //Create hold Wallet Transaction
            $walletTransactionID = $banker->holdWalletCredits(
                $walletID,
                $usdToCharge,
                $isoCurrency,
                $userNewTransaction->payment_way,
                null,
                1,
                null,
                null,
                $inputs
            );

            $userNewTransaction->wallet_transaction_id = $walletTransactionID;
            $userNewTransaction->save();

            if (isset($inputs['bonus_amount'])) {

                $account = DestinationAccount::find($inputs['destination_id']);

                BonusCoupon::create([
                    'merchant_id'          => Auth::user()->id,
                    'transaction_id'       => $userNewTransaction->id,
                    'receiver_id_document' => $account->id_number,
                    'receiver_account'     => $account->account_number,
                ]);
            }

            Pusher::trigger(
                'my-channel',
                'transaction-order',
                ['message' => Auth::user()->name . ' has created a new transaction order.']
            );

            $account = DestinationAccount::find($inputs['destination_id']);

            $this->sendDestinationMail(
                $account->email,
                $account->name . ' ' . $account->lastname,
                $userNewTransaction->receiver_fiat_amount,
                $inputs['receiver']
            );

            return Redirect::to('/transaction-success/' . $userNewTransaction->id);
        }

        $userNewTransaction->payment_way    = 'cash_deposit';
        $userNewTransaction->payment_source = 'Cash Deposit';
        $userNewTransaction->status         = 0;

        if ($inputs['sender'] !== 'USD') {
            $exchangeRates = HelpersController::getExchangeRates();
            $usdToCharge   = $inputs['to_send'] / $exchangeRates['rates'][$inputs['sender']];
        } else {
            $usdToCharge = $inputs['to_send'];
        }

        //Create hold Wallet Transaction
        $walletTransactionID = $banker->holdWalletCredits(
            $walletID,
            $usdToCharge,
            $isoCurrency,
            'cash',
            null,
            1,
            null,
            null,
            $inputs
        );

        $userNewTransaction->wallet_transaction_id = $walletTransactionID;
        $userNewTransaction->save();

        /** @var integer|null $operatorID */
        $operatorID = Banker::assignExchangeOperator($userNewTransaction);

        Pusher::trigger(
            'my-channel',
            'transaction-order',
            ['message' => Auth::user()->name . ' has created a new transaction order.']
        );

        if ($operatorID !== null) {
            Pusher::trigger(
                'operator-' . $operatorID . '-channel',
                'transaction-order',
                [
                    'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                        $userNewTransaction->tracking_id,
                ]
            );

            Pusher::trigger(
                'admins-super-channel',
                'transaction-order',
                [
                    'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                        $userNewTransaction->tracking_id,
                ]
            );
        }

        //        $data2 = [
        //            'merchant' => Auth::user()->name,
        //            'url'      => URL::to('/exchange-transaction/' . $userNewTransaction->id),
        //        ];

        if (isset($inputs['bonus_amount'])) {
            $account = DestinationAccount::find($inputs['destination_id']);

            BonusCoupon::create([
                'merchant_id'          => Auth::user()->id,
                'transaction_id'       => $userNewTransaction->id,
                'receiver_id_document' => $account->id_number,
                'receiver_account'     => $account->account_number,
            ]);
        }

        //        Mail::to([
        //            'gdf@americankryptosbank.com',
        //            'gsq@americankryptosbank.com',
        //        ])->send(new NewTransaction($data2));

        $account = DestinationAccount::find($inputs['destination_id']);

        $this->sendDestinationMail(
            $account->email,
            $account->name . ' ' . $account->lastname,
            $userNewTransaction->receiver_fiat_amount,
            $inputs['receiver']
        );

        return Redirect::to('/send-cash/' . $userNewTransaction->id);
    }

    /**
     * @param $email
     * @param $receiver
     * @param $amount
     * @param $currency
     */
    private function sendDestinationMail($email, $receiver, $amount, $currency)
    {
        $data = [
            'sender'   => Auth::user()->name,
            'receiver' => $receiver,
            'amount'   => $amount,
            'currency' => $currency,
            'url'      => URL::to('/'),
        ];

        Mail::to([
            $email,
        ])->send(new SentYouMoney($data));
    }

    private function bonusCouponValidation($receiver_id)
    {
        //one time validation
        $bonus_count = BonusCoupon::where('merchant_id', Auth::user()->id)->count();

        if ($bonus_count !== 0) {
            return ['error' => true, 'msg' => 'Usted ya ha usado el bono de 5 USD.'];
        }

        $user             = Auth::user();
        $codeUse          = UserRegistrationCodeUse::where(['user_id' => $user->id])->first();
        $userTransactions = UserExchangeTransactions::where(['user_id' => $user->id])
            ->orderBy('created_at', 'DESC')
            ->with('walletTransaction')
            ->get();
        $higherSend       = $this->getHigherSend($userTransactions);

        if ($codeUse === null || ($userTransactions !== null && $higherSend > 100)) {
            return ['error' => true, 'msg' => 'Usted no puede disfrutar del bono de 5 USD.'];
        }

        //receiver validation
        $account = DestinationAccount::find($receiver_id);

        //number of coupons used with the same document id
        $same_id_doc_coupon = BonusCoupon::where('receiver_id_document', $account->id_number)->count();

        if ($same_id_doc_coupon !== 0) {
            return ['error' => true, 'msg' => 'La cuenta destino ya ha recibido el bono de 5 USD antes.'];
        }

        //number of coupons used with the same account number
        $same_account_coupon = BonusCoupon::where('receiver_account', $account->account_number)->count();

        if ($same_account_coupon !== 0) {
            return ['error' => true, 'msg' => 'La cuenta destino ya ha recibido el bono de 5 USD antes.'];
        }

        return ['error' => false];
    }

    public function sendCash($id)
    {
        //TODO extend to Wallets Transactions

        $transaction = UserExchangeTransactions::find($id);
        $destination = DestinationAccount::find($transaction->destination_account);
        $account     = BankAccount::where(['order_id' => $id, 'canceled' => null])->orderBy('id', 'ASC')->get();
        $sendCash    = true;

        if (Auth::user()->id !== $transaction->user_id) {
            return \redirect('/');
        }

        return view('user.send-cash')->with(compact('transaction', 'destination', 'account', 'sendCash'));
    }

    /**
     * Success view after transaction complete.
     *
     * @param $id
     *
     * @return Factory|View
     */
    public function transactionSuccess($id)
    {

        $userExchangeTransaction = UserExchangeTransactions::where(
            [
                'id'      => $id,
                'user_id' => Auth::user()->id,
            ]
        )
            ->with('destinationAccount')
            ->first();
        if ($userExchangeTransaction) {
            $notes = StatusNotes::with(
                'subject',
                'traderProfile'
            )->where('client_id', '=', $userExchangeTransaction->user_id)->get();
        }

        if ($userExchangeTransaction === null) {
            return redirect('/transactions-history')->with('error', 'No existe la transacción');
        }

        $userExchangeTransactions = UserExchangeTransactions::where([
            'user_id' => Auth::user()->id,
        ])
            ->where('id', '!=', $id)
            ->with('destinationAccount')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        $genericPaymentObject     = GenericPaymentsMethods::where('metadata', 'LIKE', '%' .
            $userExchangeTransaction->payment_way . '%')
            ->first();

        $userPersonProfile = Auth::user()->personProfileObject();

        if ($genericPaymentObject) {
            if ($genericPaymentObject->title === 'Zelle') {
                return view('user.transaction-success-zeller')->with(
                    compact(
                        'genericPaymentObject',
                        'userExchangeTransaction',
                        'userExchangeTransactions',
                        'userPersonProfile',
                        'notes'
                    )
                );
            }
        }

        //  dd($genericPaymentObject);
        //dd($userExchangeTransaction->destinationAccount); die;

        //dd($userExchangeTransaction);

        return view('user.transaction-success')->with(
            compact(
                'genericPaymentObject',
                'userExchangeTransaction',
                'userExchangeTransactions',
                'userPersonProfile',
                'notes'
            )
        );
    }

    public function transactionSuccessImage($id)
    {
        $userExchangeTransaction = UserExchangeTransactions::where('id', '=', $id)
            ->with('destinationAccount')
            ->first();

        $genericPaymentObject = GenericPaymentsMethods::where('metadata', 'LIKE', '%' .
            $userExchangeTransaction->payment_way . '%')
            ->first();

        $userPersonProfile = Auth::user()->personProfileObject();

        //dd($userExchangeTransaction->destinationAccount); die;

        return view('transactions.transaction-success-image')->with(
            compact(
                'genericPaymentObject',
                'userExchangeTransaction',
                'userPersonProfile'
            )
        );
    }

    /**
     * @return RedirectResponse
     */
    public function transactionReportPayment(Request $request): RedirectResponse
    {
        //Envio de saldo
        App::setLocale('es');

        $request->validate([

            'comprobante_de_pago'    => 'required|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:4000',
            'terminos'               => 'accepted',
            'numero_de_confirmacion' => 'required|unique:user_exchange_transactions,notes',

        ]);

        $inputs = request()->all();

        if (isset($inputs['comprobante_de_pago'])) {
            $file_name = strtolower(str_replace(
                ' ',
                '',
                $inputs['comprobante_de_pago']->getClientOriginalName()
            ));
            $file_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $file_name);

            $inputs['comprobante_de_pago']->move(
                base_path() . '/public/img/user-exchange-transactions-images/' . $inputs['id'] . '/' . '/',
                $file_name
            );

            $userExchangeTransaction        = UserExchangeTransactions::find($inputs['id']);
            $userExchangeTransaction->notes = $request->numero_de_confirmacion ?? null;

            $userExchangeTransaction->payment_support = '/img/user-exchange-transactions-images/' .
                $inputs['id'] . '/' . $file_name;
            unset($userExchangeTransaction['edtDate']);
            $userExchangeTransaction->save();
        }

        // $data['i'] = '';
        return redirect('transaction-success-fin/' . $userExchangeTransaction->id);

        //return Redirect::to('/transaction-success/' . $inputs['id']);
    }

    public function transactionSuccessFin($id)
    {
        $userExchangeTransaction = UserExchangeTransactions::find($id);

        $data['pago'] = $userExchangeTransaction;

        return view('user.transaction-success-fin', $data);
    }

    public function transactionSuccessCC($id)
    {
        $userExchangeTransaction = UserExchangeTransactions::find($id);

        $data['pago'] = $userExchangeTransaction;

        return view('user.transaction-success-cc', $data);
    }

    // public function transactionAll()
    // {
    //     $userExchangeTransaction = UserExchangeTransactions::orderBy('id', 'desc')->where('user_id', Auth::id())->paginate(5);

    //     //dd($userExchangeTransaction);

    //     $data['pagos'] = $userExchangeTransaction;
    //     return view('user.transaction-all', $data);
    // }

    public function getUsersList()
    {
        $users = User::with(
            'personProfile'
        )->where('role_id', 5);

        $usersId = User::where('role_id', 5)->pluck('id');

        $usersProfiles = UserPersonProfile::whereIn('user_id', $usersId);

        $inputs = request()->all();

        if (isset($inputs['user-name'])) {

            $userName = $inputs['user-name'];

            if ($userName) {

                $users = $users->where('name', 'LIKE', '%' . $userName . '%');
            }
        }

        if (isset($inputs['user-lastname'])) {

            $userLastname = $inputs['user-lastname'];

            if ($userLastname) {

                $users = $users->where('name', 'LIKE', '%' . $userLastname . '%');
            }
        }

        if (isset($inputs['user-email'])) {

            $userEmail = $inputs['user-email'];

            if ($userEmail) {
                //$carbonDate = Carbon::createFromFormat('Y-m-d', $transactionDate)->toDateTimeString();
                $users = $users->where('email', $userEmail);
            }
        }

        if (isset($inputs['status'])) {

            $userStatus = $inputs['status'];

            if ($userStatus) {
                if ($userStatus === 'block') {
                    $users = $users->where('session_id', 'block');
                } else {
                    $userByStatus = $usersProfiles->where('approval_status', $userStatus)->pluck('user_id');
                    $users        = $users->whereIn('id', $userByStatus);
                }
            }
        }

        if (isset($inputs['date-range'])) {

            $date = $inputs['date-range'];

            $dateArray = explode(" - ", $date);
            $dateStart = trim(Carbon::parse($dateArray[0])->format('Y-m-d'));
            $dateEnd   = trim(Carbon::parse($dateArray[1])->format('Y-m-d'));

            $dateNow = Carbon::now()->format('Y-m-d');

            if ($dateNow == $dateStart && $dateNow == $dateEnd) {
                $dateStart = null;
                $dateEnd   = null;
            }

            if ($dateStart && $dateEnd && $dateStart != null && $dateEnd != null) {
                $users = $users->where('created_at', '>=', $dateStart)
                    ->where('created_at', '<=', $dateEnd);
            }
        }

        $user_count = UserPersonProfile::whereIn('user_id', $usersId)
            ->count();

        $whitout_profile = UserPersonProfile::whereIn('user_id', $usersId)
            ->where('approval_status', '=', 0)
            ->count();

        $waiting_profile = UserPersonProfile::whereIn('user_id', $usersId)
            ->where('approval_status', '=', 1)
            ->count();

        $approve_profile = UserPersonProfile::whereIn('user_id', $usersId)
            ->where('approval_status', '=', 2)
            ->count();
        $reject_profile  = UserPersonProfile::whereIn('user_id', $usersId)
            ->where('approval_status', '=', 3)
            ->count();
        $block_profile   = User::whereIn('id', $usersId)
            ->where('session_id', '=', 'block')
            ->count();
        $users           = $users->paginate(10);

        return view('user.user-list')->with(compact(
            'users',
            'user_count',
            'whitout_profile',
            'waiting_profile',
            'approve_profile',
            'reject_profile',
            'block_profile',
            'userName',
            'userLastname',
            'userEmail',
            'userStatus',
            'date'
        ));
    }

    public function downloadCSV($status)
    {

        $now = Carbon::now()->format('d-m-Y g:i A');

        if ($status === 'all') {

            $table    = UserPersonProfile::all();
            $filename = 'usersAll' . $now . '.csv';
        } else {

            if ($status == 'block') {

                $table    = User::where('session_id', 'block')->get();
                $filename = 'allBlockedUsers' . $now . '.csv';
            } else {

                $table = UserPersonProfile::where('approval_status', $status)->get();

                if ($status == 0) {
                    $filename = 'allIncompleteUsers' . $now . '.csv';
                }

                if ($status == 1) {
                    $filename = 'allPendingUsers' . $now . '.csv';
                }

                if ($status == 2) {
                    $filename = 'allPpprovedUsers' . $now . '.csv';
                }

                if ($status == 3) {
                    $filename = 'allRejectedUsers' . $now . '.csv';
                }
            }
        }

        $csvExporter = new \Laracsv\Export();
        if ($status != 'block') {
            $csvExporter->beforeEach(function ($transCsv) {
                $transCsv->name = $transCsv->first_name . ' ' . $transCsv->last_name;
            });
        }

        $csvExporter->build(
            $table,
            [
                'name'       => 'Full Name',
                'email'      => 'Email',
                'created_at' => 'Fecha'
            ]
        )->download($filename);
    }

    public function userProfile($id)
    {
        $personProfile  = UserPersonProfile::where(['user_id' => $id])->with('user')->first();
        $companyProfile = UserCompanyProfile::where(['user_id' => $id])->with('user')->first() ??
            new UserCompanyProfile();

        $userExchangeTransactions = UserExchangeTransactions::where([
            'user_id' => $id,
        ]);

        $userHasUsedRegistrationCode = UserRegistrationCodeUse::where('user_id', $id)->with('code')->first();

        $userWallets = CurrencyWallet::where([
            'user_id'  => $id,
            'status'   => 1,
            'currency' => 'USD',
        ])
            ->get()
            ->toArray();

        $inputs = request()->all();

        if (isset($inputs['dest-name']) || isset($inputs['dest-lastname']) || isset($inputs['transaction-date'])) {
            $destName        = $inputs['dest-name'];
            $destLastname    = $inputs['dest-lastname'];
            $transactionDate = $inputs['transaction-date'];

            if ($destName) {
                $userExchangeTransactions = $userExchangeTransactions->with('destinationAccount')
                    ->whereHas(
                        'destinationAccount',
                        function ($query) use ($destName) {
                            $query->where(
                                'destination_accounts.name',
                                'LIKE',
                                '%' . $destName . '%'
                            );
                        }
                    );
            }
            if ($destLastname) {
                $userExchangeTransactions = $userExchangeTransactions->with('destinationAccount')
                    ->whereHas(
                        'destinationAccount',
                        function ($query) use ($destLastname) {
                            $query->where(
                                'destination_accounts.lastname',
                                'LIKE',
                                '%' . $destLastname . '%'
                            );
                        }
                    );
            }
            if (!$destName && !$destLastname) {
                $userExchangeTransactions = $userExchangeTransactions->with('destinationAccount');
            }

            if ($transactionDate) {
                //$carbonDate = Carbon::createFromFormat('Y-m-d', $transactionDate)->toDateTimeString();
                $userExchangeTransactions = $userExchangeTransactions->where(
                    'created_at',
                    'LIKE',
                    '%' . $transactionDate . '%'
                );
            }
        } else {
            $userExchangeTransactions = $userExchangeTransactions->with('destinationAccount');
        }

        $userExchangeTransactions = $userExchangeTransactions->orderBy('created_at', 'DESC')
            ->paginate(20);

        $subjetcs = SubjectsRejectProfile::all();

        $notes    = NotesUserProfile::with(
            'traderProfile'
        )->where('client_id', '=', $id)->get();
        $tiers = TierLevel::all();
        $tierFiles = TierFile::where('user_id', $id)->get();

        // //support tickets
        // $tickets = User::join('tickets','tickets.user_id','=','users.id')
        // ->where('user_id', '=', $id)
        // ->join('departments', 'departments.id', '=', 'tickets.dept_id')
        // ->join('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.priority_id')
        // ->join('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.status')
        // // ->join('ticket_threads', 'ticket_threads.ticket_id', '=', 'tickets.id')
        // ->select('tickets.ticket_number','tickets.id','ticket_statuses.state as status','tickets.created_at','departments.name as department')
        // ->orderBy('tickets.created_at','desc')
        // ->groupBy('tickets.id')
        // ->get();

        return view('user.user-profile')->with(compact(
            'personProfile',
            'companyProfile',
            'userExchangeTransactions',
            'subjetcs',
            'userWallets',
            'notes',
            'userHasUsedRegistrationCode',
            // 'tickets',
            'tiers',
            'tierFiles'
        ));
    }

    public function newInternalNote(Request $request, $id)
    {
        $inputs          = request()->all();
        $user            = Auth::user();
        $note            = new NotesUserProfile();
        $note->msg       = $inputs['notes'];
        $note->client_id = $id;
        $note->ip        = $request->ip();
        $note->trader_id = $user->id;

        $files = "";
        if ($request->hasfile('file')) {
            foreach ($request->file('file') as $file) {
                $name = strtolower(str_replace(
                    ' ',
                    '',
                    $file->getClientOriginalName()
                ));

                $name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $name);

                $file->move(
                    base_path() . '/public/img/internal_notes/' . $id . '/' . '/',
                    $name
                );

                $routePath = '/img/internal_notes/' . $id . '/' . $name;
                $files     .= $routePath . " , ";
            }
        }
        $note->file = $files;

        $note->save();

        return Redirect::back()->with('success', 'Note has been saved.');
    }

    public function newSubject()
    {

        $inputs           = request()->all();
        $subject          = new SubjectsRejectProfile();
        $subject->status  = 0;
        $subject->subject = $inputs['subject'];
        $subject->details = $inputs['detail_subject'];
        $subject->save();

        return Redirect::back()->with('success', 'Se agrego el asunto');
    }

    public function editSubject($id)
    {

        $inputs           = request()->all();
        $subject          = SubjectsRejectProfile::find($id);
        $subject->subject = $inputs['subject'];
        $subject->details = $inputs['detail_subject'];
        $subject->save();

        return Redirect::back()->with('success', 'Se actualizo el asunto');
    }

    public function deleteSubject($id)
    {

        $subject = SubjectsRejectProfile::find($id);
        $subject->delete();

        return Redirect::back()->with('success', 'Se elimino el asunto');
    }

    public function verifyPerson($id)
    {
        $inputs            = request()->all();
        $userPersonProfile = null;

        if (isset($inputs['UserPersonProfile'])) {
            if (isset($inputs['UserPersonProfile']['id_type'])) {
                $userPersonProfile = UserPersonProfile::where(['id' => $inputs['UserPersonProfile']['id']])->first();
                $userPersonProfile->fill($inputs['UserPersonProfile']);

                if (isset($inputs['UserPersonProfile']['id_number'])) {

                    $exist_user = UserPersonProfile::where(['id_number' => $inputs['UserPersonProfile']['id_number']])
                        ->where(['id_type' => $inputs['UserPersonProfile']['id_type']])
                        ->first();
                    if (is_object($exist_user)) {
                        $exist_user_block = User::where([
                            'id'         => $exist_user->user_id,
                            'session_id' => 'block'
                        ])->first();

                        if (is_object($exist_user_block)) {

                            return Redirect::back()->with(
                                'error',
                                '¡El tipo y numero de identificación ingresado han sido previamente bloqueados!'
                            );
                        }
                    }
                }

                if (isset($inputs['UserPersonProfile']['selfie_id'])) {
                    $path                         = Storage::disk('public')->putFile(
                        'profile-files',
                        $inputs['UserPersonProfile']['selfie_id']
                    );
                    $userPersonProfile->selfie_id = 'storage/' . $path;
                }

                if (isset($inputs['UserPersonProfile']['id_confirmation'])) {
                    $path                               = Storage::disk('public')->putFile(
                        'profile-files',
                        $inputs['UserPersonProfile']['id_confirmation']
                    );
                    $userPersonProfile->id_confirmation = 'storage/' . $path;
                }
            }

            if (isset($inputs['UserPersonProfile']['first_name'])) {
                $userPersonProfile = UserPersonProfile::where(['id' => $inputs['UserPersonProfile']['id']])->first();
                $userPersonProfile->fill($inputs['UserPersonProfile']);


                // $userPersonProfile->mobile      = $inputs['UserPersonProfile']['mobile'];
                // $userPersonProfile->local_phone = $inputs['UserPersonProfile']['local'];


                if (isset($inputs['UserPersonProfile']['selfie'])) {
                    $path                      = Storage::disk('public')->putFile(
                        'profile-files',
                        $inputs['UserPersonProfile']['selfie']
                    );
                    $userPersonProfile->selfie = 'storage/' . $path;
                }
            }

            if ($userPersonProfile !== null) {
                /** @var UserPersonProfile $userPersonProfile */
                $verificationCount = 0;
                foreach ($userPersonProfile->getAttributes() as $attribute) {
                    if ($attribute !== null) {
                        $verificationCount++;
                    }
                }

                if (($userPersonProfile->approval_status === 0 || $userPersonProfile->approval_status === 3)
                    && $verificationCount >= count($userPersonProfile->getAttributes())
                ) {
                    $userPersonProfile->approval_status = 1;

                    Pusher::trigger(
                        'my-channel',
                        'new-profile',
                        ['message' => Auth::user()->name . ' ha completado su perfil para la verificación.']
                    );
                }
                $userPersonProfile->save();
            }

            if (isset($inputs['User'])) {
                $user = User::where('email', Auth::user()->email)->first();
                if (isset($inputs['User']['email']) && $inputs['User']['email'] !== Auth::user()->email) {
                    $user->email = $inputs['User']['email'];
                    $user->save();
                }

                if (isset($inputs['User']['password']) && $inputs['User']['password'] !== null) {
                    $user->password = Hash::make($inputs['User']['password']);
                    $user->save();
                }
            }
        }

        return Redirect::back()->with('success', 'El usuario ha sido Actualizado.');
    }

    public function verifyCompany($id)
    {
        $inp = request()->all();

        $prof = UserCompanyProfile::find($id);
        $prof->update($inp);

        return Redirect::to('/user-profile/' . $prof->user_id)->with('success', 'El usuario ha sido Actualizado.');
    }

    /**
     * @return RedirectResponse
     */
    public function sendContactMessage()
    {
        $inputs = request()->all();

        ContactUsMessage::create($inputs);

        Mail::to(['csupport@americankryptosbank.com', 'customercare@americankryptosbank.com'])
            ->send(new ContactUsMessageEmail($inputs));

        return Redirect::to('/contact')->with('success', 'El mensaje ha sido enviado.');
    }

    public function getForgotPassword()
    {
        $token = request()->get('token');

        $user = User::where('verification_token', $token)->first();

        if (is_null($user)) {
            return Redirect::to('/signin')->with('error', 'Este link ha expirado.');
        }

        return view('forgot-password-form')->with(compact('token'));
    }

    public function postForgotPassword()
    {
        $inputs = request()->all();

        $user = User::where('verification_token', $inputs['verification_token'])->first();

        if (is_null($user)) {
            return Redirect::to('/signin')->with('error', 'Este link ha expirado.');
        }

        if ($inputs['password'] === $inputs['password-confirmation']) {
            $user->password           = Hash::make($inputs['password']);
            $user->verification_token = '';
            $user->save();
        } else {
            return Redirect::back()->with('error', 'Las contraseñas no coinciden.');
        }

        return Redirect::to('/signin')->with('success', 'Tu password ha sido cambiado exitosamente.');
    }

    /**
     * @param array $inputs
     *
     * @return bool|RedirectResponse
     */
    public function checkEnableToOperate(array $inputs)
    {
        $usdEquivalent = $inputs['to_send'];

        if ($inputs['sender'] !== 'USD') {
            $exchangeRates = HelpersController::getExchangeRates();
            $usdEquivalent = (float)str_replace(
                ',',
                '',
                $inputs['to_send']
            ) / $exchangeRates['rates'][$inputs['sender']];
        }

        $userProfile = Auth::user()->personProfileObject();

        if ($usdEquivalent > 100 && $userProfile->profileCompletition !== 1) {
            return Redirect::to('/')->with('error', 'Método equivocado');
        }

        if ($this->checkHours()) {
            return Redirect::to('/')->with('error', 'Método equivocado');
        }

        return true;
    }

    /**
     * Check if the site is open.
     *
     * @return int
     */
    private function checkHours(): int
    {
        $closeHour       = Carbon::createFromTime(17, 0, 0, 'UTC')->getTimestamp();
        $openHour        = Carbon::createFromTime(13, 0, 0, 'UTC')->getTimestamp();
        $currentHour     = Carbon::now('UTC')->getTimestamp();
        $currentDay      = Carbon::now('UTC');
        $currentDay      = $currentDay->dayOfWeek;
        $websiteSettings = WebsiteSettings::find(1);

        if (($currentDay !== 0 && $currentDay !== 6 && $currentDay !== 1 && $websiteSettings['settings']['is_active'] !== 0) ||
            ($currentDay === 6 && $currentHour < $closeHour && $websiteSettings['settings']['is_active'] !== 0) ||
            ($currentDay === 1 && $currentHour > $openHour && $websiteSettings['settings']['is_active'] !== 0)
        ) {
            return 0;
        }

        return 1;
    }

    public function getActivate2FAuth()
    {
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        //generating secret key
        $secret = $google2fa->generateSecretKey();

        //setting up
        $QR_image = $google2fa->getQRCodeInline(
            'American Kryptos Bank',
            Auth::user()->email,
            $secret
        );

        return view('user.2fa')->with(compact('QR_image', 'secret'));
    }

    public function postActivate2FAuth()
    {
        $inputs = request()->all();

        $user                   = Auth::user();
        $user->google2fa_secret = $inputs['secret'];
        $user->save();

        return Redirect::to('/settings')->with('success', 'Two Factor Authentication ha sido establecido.');
    }

    public function verify2f()
    {
        $inputs = request()->all();

        $user = User::where('email', $inputs['email'])->first();

        return $user !== null ? $user->google2fa_secret : null;
    }

    public function verify2fCode()
    {
        $inputs = request()->all();
        $user   = User::where('email', $inputs['email'])->first();

        if ($user !== null) {
            $google2fa = app('pragmarx.google2fa');

            $valid = $google2fa->verifyKey($user->google2fa_secret, $inputs['code']);

            return ['valid' => $valid];
        }

        return ['invalid' => true];
    }

    public function transactionsHistory()
    {
        $inputs                   = request()->all();
        $userExchangeTransactions = UserExchangeTransactions::where([
            'user_id' => Auth::user()->id,
        ]);

        $notes = StatusNotes::with(
            'subject',
            'traderProfile',
            'transaction'
        )->where('client_id', '=', Auth::user()->id)->get();

        if ($inputs) {
            $destName        = $inputs['dest-name'];
            $destLastname    = $inputs['dest-lastname'];
            $transactionDate = $inputs['transaction-date'];

            if ($destName) {
                $userExchangeTransactions = $userExchangeTransactions->with('destinationAccount')
                    ->whereHas(
                        'destinationAccount',
                        function ($query) use ($destName) {
                            $query->where(
                                'destination_accounts.name',
                                'LIKE',
                                '%' . $destName . '%'
                            );
                        }
                    );
            }
            if ($destLastname) {
                $userExchangeTransactions = $userExchangeTransactions->with('destinationAccount')
                    ->whereHas(
                        'destinationAccount',
                        function ($query) use ($destLastname) {
                            $query->where(
                                'destination_accounts.lastname',
                                'LIKE',
                                '%' . $destLastname . '%'
                            );
                        }
                    );
            }
            if (!$destName && !$destLastname) {
                $userExchangeTransactions = $userExchangeTransactions->with('destinationAccount');
            }

            if ($transactionDate) {
                //$carbonDate = Carbon::createFromFormat('Y-m-d', $transactionDate)->toDateTimeString();
                $userExchangeTransactions = $userExchangeTransactions->where(
                    'created_at',
                    'LIKE',
                    '%' . $transactionDate . '%'
                );
            }
        } else {
            $userExchangeTransactions = $userExchangeTransactions->with('destinationAccount');
        }

        $userExchangeTransactions = $userExchangeTransactions->orderBy('created_at', 'DESC')
            ->paginate(20);

        $userPersonProfile = Auth::user()->personProfileObject();

        //dd($userExchangeTransaction->destinationAccount); die;

        return view('user.transactions-history')->with(
            compact(
                'userExchangeTransactions',
                'userPersonProfile',
                'notes'
            )
        );
    }

    public function replyNote(Request $request, $id)
    {

        $note           = StatusNotes::find($id);
        $inputs         = request()->all();
        $note->reply    = $inputs['reply'];
        $note->ip_reply = $request->ip();
        $note->is_reply = 1;

        $files = "";

        if ($request->hasfile('reply_file')) {
            foreach ($request->file('reply_file') as $file) {
                $name = strtolower(str_replace(
                    ' ',
                    '',
                    $file->getClientOriginalName()
                ));

                $name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $name);
                echo $name . '<br>';
                $file->move(
                    base_path() . '/public/img/status_notes/' . $id . '/' . '/',
                    $name
                );

                $routePath = '/img/status_notes/' . $id . '/' . $name;
                $files     .= $routePath . " , ";
            }
        }
        $note->reply_file = $files;

        $note->save();

        return Redirect::back()->with('success', 'Se agrego la respuesta');
    }

    public function replyNoteIncoming(Request $request, $id)
    {

        $note           = StatusNotesIncoming::find($id);
        $inputs         = request()->all();
        $note->reply    = $inputs['reply'];
        $note->ip_reply = $request->ip();
        $note->is_reply = 1;
        if (isset($inputs['reply_file'])) {
            $file_name = strtolower(str_replace(
                ' ',
                '',
                $inputs['reply_file']->getClientOriginalName()
            ));
            $file_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $file_name);

            $inputs['reply_file']->move(
                base_path() . '/public/img/status_notes_incoming/' . $id . '/' . '/',
                $file_name
            );

            $note->reply_file = '/img/status_notes_incoming/' .
                $id . '/' . $file_name;
        }
        $note->save();

        return Redirect::back()->with('success', 'Se agrego la respuesta');
    }

    /**
     * @return Factory|View
     */
    public function servicesSendMoney()
    {
        return view('user.services-send-money');
    }

    /**
     * @return Factory|View
     */
    public function convertMoney()
    {
        $user = Auth::user();

        if ($user->personProfile->approval_status !== 2) {
            return Redirect::to('/user-info');
        }

        return view('user.services-convert-money');
    }

    /**
     * @return Factory|View
     */
    public function servicesExchangeMoney()
    {
        return view('user.services-exchange-money');
    }

    /**
     * @return Factory|View
     */
    public function servicesTransferMoney()
    {
        return view('user.services-transfer-money');
    }

    /**
     * @return Factory|View
     */
    public function servicesCryptoMarket()
    {
        return view('user.services-crypto-market');
    }

    /**
     * @return Factory|View
     */
    public function servicesSavings()
    {
        return view('user.services-savings');
    }

    /**
     * @return Factory|View
     */
    public function servicesInvestments()
    {
        return view('user.services-investments');
    }

    /**
     * @return bool|RedirectResponse
     */
    private function validateActiveChat()
    {

        $user              = Auth::user();
        $activeTransaction = UserExchangeTransactions::where(
            [
                'user_id' => $user->id,
                'status'  => 0,
            ]
        )->orderBy('created_at', 'DESC')->first();

        if ($activeTransaction && $activeTransaction->payment_way === 'cash_deposit') {
            return Redirect::to('/send-cash/' . $activeTransaction->id)
                ->with('error', 'Posee una transacción activa.');
        }

        return null;
    }

    public function getTradersList()
    {
        $users = User::with('assignedExchanges')->where('role_id', '!=', 5);

        $inputs = request()->all();

        if (isset($inputs['user-name']) || isset($inputs['user-lastname']) || isset($inputs['user-email'])) {
            $userName     = $inputs['user-name'];
            $userLastname = $inputs['user-lastname'];
            $userEmail    = $inputs['user-email'];

            if ($userName) {

                $users = $users->where('name', 'LIKE', '%' . $userName . '%');
            }
            if ($userLastname) {

                $users = $users->where('name', 'LIKE', '%' . $userLastname . '%');
            }

            if ($userEmail) {
                //$carbonDate = Carbon::createFromFormat('Y-m-d', $transactionDate)->toDateTimeString();
                $users = $users->where('email', $userEmail);
            }
        }

        $users = $users->paginate(10);

        return view('user.traders-list')->with(compact('users'));
    }

    public function getTrader($id)
    {
        $trader = User::find($id);

        $transactions = UserExchangeTransactions::where('trader_id', $id);

        $inputs = request()->all();

        if (isset($inputs['trans-status']) || isset($inputs['transaction-date'])) {
            $newStatus       = $inputs['trans-status'];
            $transactionDate = $inputs['transaction-date'];
            //return dd($newStatus);

            if (isset($newStatus) && $newStatus !== 'all') {
                //if ($newStatus == 0) $newStatus = null;
                $transactions = $transactions->where('status', $newStatus);
            }

            if ($transactionDate) {
                //$carbonDate = Carbon::createFromFormat('Y-m-d', $transactionDate)->toDateTimeString();
                $transactions = $transactions->where(
                    'created_at',
                    'LIKE',
                    '%' . $transactionDate . '%'
                );
            }
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(20);

        return view('user.trader-details')->with(compact('trader', 'transactions'));
    }

    public function getUserInfo()
    {
        $personProfile  = UserPersonProfile::where(['user_id' => Auth::user()->id])->with('user')->first();
        $companyProfile = UserCompanyProfile::where(['user_id' => Auth::user()->id])->with('user')->first() ??
            new UserCompanyProfile();

        if ($personProfile->approval_status === 1) {
            return redirect('user-succcess');
        }

        return view('profile.general-info')->with(compact('personProfile', 'companyProfile'));
    }

    public function userVerifyPhone(Request $request)
    {


        $code = rand(1000, 9999);

        Session::put('phone', $request->phone[0]);
        Session::put('code', $code);
        sms('American Kryptos Bank Codigo:' . $code, $request->phone[0]);

        return view('profile.codephone');
    }

    public function userVerifyPhonePost(Request $request)
    {

        $code_save = Session::get('code');
        if ($request->codigo == $code_save) {

            $user       = User::find(Auth::id());
            $id_profile = $user->personProfile->id;
            $profile    = UserPersonProfile::find($id_profile);

            $profile->mobile_verified = true;
            $profile->mobile          = Session::get('phone');
            $profile->update();

            if ($profile->identity_verified == true && $profile->datos_verified == true && $profile->mobile_verified == true) {
                $profile->approval_status = 1;
                $profile->save();

                return redirect('user-succcess');
            }

            return Redirect::to('/user-info#tab_telefono')->with('success', 'Tu celular ha sido verificado.');
        } else {
            return Redirect::to('/user-info#tab_telefono')->with('error', 'Codigo invalido intente nuevamente.');
        }
    }

    public function VeryficacionIdentidad(Request $request)
    {

        App::setLocale('es');
        $validator = Validator::make($request->all(), [
            'tipo_de_documento'   => 'required|max:191',
            'numero_de_documento' => 'required|max:191',
            'fecha_de_emision'    => 'required|date|max:191',
            'fecha_de_caducidad'  => 'required|date|max:191',
            'escrito_en_mano'     => 'accepted',
        ]);

        if ($validator->fails()) {

            return redirect('/user-info#tab_identity')
                ->withErrors($validator)
                ->withIdentidad('true')
                ->withInput();
        }

        $user       = User::find(Auth::id());
        $id_profile = $user->personProfile->id;
        $profile    = UserPersonProfile::find($id_profile);

        if ($profile->selfie_id === null) {

            $validator = Validator::make($request->all(), [
                'selfie_id' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            ]);

            if ($validator->fails()) {

                return redirect('user-info#tab_identity')
                    ->withErrors($validator)
                    ->withIdentidad('true')
                    ->withInput();
            }
        }

        if ($profile->id_confirmation === null) {
            $validator = Validator::make($request->all(), [
                'id_confirmation' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            ]);
            if ($validator->fails()) {

                return redirect('user-info#tab_identity')
                    ->withErrors($validator)
                    ->withIdentidad('true')
                    ->withInput();
            }
        }

        if ($profile->id_confirmation_back === null) {
            $validator = Validator::make($request->all(), [
                'id_confirmation_back' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            ]);
            if ($validator->fails()) {

                return redirect('user-info#tab_identity')
                    ->withErrors($validator)
                    ->withIdentidad('true')
                    ->withInput();
            }
        }

        if (isset($request->selfie_id)) {
            $path               = Storage::disk('public')->putFile(
                'profile-files',
                $request->selfie_id
            );
            $profile->selfie_id = 'storage/' . $path;
        }

        if (isset($request->id_confirmation)) {
            $path                     = Storage::disk('public')->putFile(
                'profile-files',
                $request->id_confirmation
            );
            $profile->id_confirmation = 'storage/' . $path;
        }

        if (isset($request->id_confirmation_back)) {
            $path                          = Storage::disk('public')->putFile(
                'profile-files',
                $request->id_confirmation_back
            );
            $profile->id_confirmation_back = 'storage/' . $path;
        }

        if (isset($request->numero_de_documento)) {
            $exist_user = UserPersonProfile::where([
                'id_number' => $request->numero_de_documento,
                'id_type'   => $request->tipo_de_documento
            ])
                ->first();


            if (is_object($exist_user)) {
                $exist_user_block = User::where(['id' => $exist_user->user_id, 'session_id' => 'block'])->first();

                if (is_object($exist_user_block)) {

                    return Redirect::back()->with(
                        'error',
                        '¡El tipo y numero de identificación ingresado han sido previamente bloqueados!'
                    );
                }
            }
        }

        $profile->id_type            = $request->tipo_de_documento;
        $profile->id_number          = $request->numero_de_documento;
        $profile->id_creation_date   = $request->fecha_de_emision;
        $profile->id_expiration_date = $request->fecha_de_caducidad;
        $profile->identity_verified  = true;
        $profile->save();

        if ($profile->identity_verified == true && $profile->datos_verified == true && $profile->mobile_verified == true) {
            $profile->approval_status = 1;
            $profile->save();

            return redirect('user-succcess');
        }

        return Redirect::to('/user-info#tab_telefono')->with(
            'success',
            'Tu verificación de identidad ha sido actualizado.'
        )->withIdentidad('true');
    }

    public function postUserInfo(UserInfoRequest $request)
    {


        // $userPersonProfile = UserPersonProfile::where(['id' => $inputs['UserPersonProfile']['id']])->first();
        $user       = User::find(Auth::id());
        $id_profile = $user->personProfile->id;
        $profile    = UserPersonProfile::find($id_profile);

        if ($profile->selfie === null) {
            $request->validate([
                'selfie' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            ]);
        }

        if (isset($request->selfie)) {
            $path            = Storage::disk('public')->putFile(
                'profile-files',
                $request->selfie
            );
            $profile->selfie = 'storage/' . $path;
        }

        //dd($request->fecha_de_nacimiento);
        $fullBirthday = explode('-', $request->fecha_de_nacimiento);

        $profile->first_name       = $request->primer_nombre;
        $profile->second_name      = $request->segundo_nombre ?? '';
        $profile->last_name        = $request->primer_apellido;
        $profile->second_last_name = $request->segundo_apellido ?? '';
        $profile->birth_month      = $fullBirthday[1];
        $profile->birth_day        = $fullBirthday[2];
        $profile->birth_year       = $fullBirthday[0];

        $profile->local_phone          = $request->phone[0];
        $profile->country              = $request->pais;
        $profile->state                = $request->estado_departamento;
        $profile->city                 = $request->ciudad;
        $profile->zip_code             = $request->codigo_postal;
        $profile->street               = $request->direccion;
        $profile->address_place_type   = $request->tipo;
        $profile->address_floor        = $request->piso;
        $profile->address_place_number = $request->habitacion;
        $profile->gps                  = $request->gps ?? null;
        $profile->datos_verified       = true;

        $profile->save();

        if ($profile->identity_verified == true && $profile->datos_verified == true && $profile->mobile_verified == true) {

            $profile->approval_status = 1;
            $profile->save();

            return redirect('user-succcess');
        }

        return Redirect::to('/user-info#tab_identity')->with(
            'success',
            'Se completado con exito el primer paso de verificación continua con el segundo paso.'
        );
    }

    public function postUserInfoOld()
    {

        $inputs            = request()->all();
        $userPersonProfile = null;

        if (isset($inputs['UserPersonProfile'])) {
            if (isset($inputs['UserPersonProfile']['id_type'])) {
                $userPersonProfile = UserPersonProfile::where(['id' => $inputs['UserPersonProfile']['id']])->first();
                $userPersonProfile->fill($inputs['UserPersonProfile']);

                if (isset($inputs['UserPersonProfile']['id_number'])) {

                    $exist_user = UserPersonProfile::where(['id_number' => $inputs['UserPersonProfile']['id_number']])
                        ->where(['id_type' => $inputs['UserPersonProfile']['id_type']])
                        ->first();

                    if (is_object($exist_user)) {
                        $exist_user_block = User::where(['session_id' => 'block']);

                        if (is_object($exist_user_block)) {

                            $user                 = User::where(['id' => $exist_user_block->id])->first();
                            $user->password       = $user->password . '--Block--Block';
                            $user->email          = $user->email . '--Block--Block';
                            $user->session_id     = 'block';
                            $user->remember_token = null;
                            $user->is_logged_in   = 0;
                            $user->save();

                            return Redirect::to('/logout')->with(
                                'error',
                                'El usuario fue bloqueado por tener una cuenta existente bloqueada.'
                            );
                        }
                    }
                }

                if (isset($inputs['UserPersonProfile']['selfie_id'])) {
                    $path                         = Storage::disk('public')->putFile(
                        'profile-files',
                        $inputs['UserPersonProfile']['selfie_id']
                    );
                    $userPersonProfile->selfie_id = 'storage/' . $path;
                }

                if (isset($inputs['UserPersonProfile']['id_confirmation'])) {
                    $path                               = Storage::disk('public')->putFile(
                        'profile-files',
                        $inputs['UserPersonProfile']['id_confirmation']
                    );
                    $userPersonProfile->id_confirmation = 'storage/' . $path;
                }
            }

            if (isset($inputs['UserPersonProfile']['first_name'])) {
                $userPersonProfile = UserPersonProfile::where(['id' => $inputs['UserPersonProfile']['id']])->first();
                $userPersonProfile->fill($inputs['UserPersonProfile']);

                if ($userPersonProfile->mobile_verified === false || $userPersonProfile->mobile_verified === 0) {
                    $userPersonProfile->mobile = null;
                }

                if (isset($inputs['UserPersonProfile']['pre-local'])) {
                    $mainLocal                      = preg_replace(
                        '/\D/',
                        '',
                        $inputs['UserPersonProfile']['main-local']
                    );
                    $userPersonProfile->local_phone = $inputs['UserPersonProfile']['pre-local'] . ' ' . $mainLocal;
                }

                $fullBirthday                   = explode('-', $inputs['UserPersonProfile']['full_birth']);
                $userPersonProfile->birth_year  = $fullBirthday[0];
                $userPersonProfile->birth_month = $fullBirthday[1];
                $userPersonProfile->birth_day   = $fullBirthday[2];

                if (isset($inputs['UserPersonProfile']['selfie'])) {
                    $path                      = Storage::disk('public')->putFile(
                        'profile-files',
                        $inputs['UserPersonProfile']['selfie']
                    );
                    $userPersonProfile->selfie = 'storage/' . $path;
                }
            }

            if ($userPersonProfile !== null) {
                /** @var UserPersonProfile $userPersonProfile */
                $verificationCount = 0;
                foreach ($userPersonProfile->getAttributes() as $attribute) {
                    if ($attribute !== null) {
                        $verificationCount++;
                    }
                }

                if (($userPersonProfile->approval_status === 0 || $userPersonProfile->approval_status === 3)
                    && $verificationCount >= count($userPersonProfile->getAttributes())
                ) {
                    $userPersonProfile->approval_status = 1;

                    Pusher::trigger(
                        'my-channel',
                        'new-profile',
                        ['message' => Auth::user()->name . ' ha completado su perfil para la verificación.']
                    );
                }
                $userPersonProfile->save();
            }

            if (isset($inputs['User'])) {
                $user = User::where('email', Auth::user()->email)->first();
                if (isset($inputs['User']['email']) && $inputs['User']['email'] !== Auth::user()->email) {
                    $user->email = $inputs['User']['email'];
                    $user->save();
                }

                if (isset($inputs['User']['password']) && $inputs['User']['password'] !== null) {
                    $user->password = Hash::make($inputs['User']['password']);
                    $user->save();
                }
            }
        }

        return Redirect::to('/user-info')->with('success', 'Tu perfil ha sido actualizado.');
    }

    public function getUbicationInfo()
    {
        $personProfile  = UserPersonProfile::where(['user_id' => Auth::user()->id])->with('user')->first();
        $companyProfile = UserCompanyProfile::where(['user_id' => Auth::user()->id])->with('user')->first() ??
            new UserCompanyProfile();

        return view('profile.ubication-info')->with(compact('personProfile', 'companyProfile'));
    }

    public function postUbicationInfo()
    {
        $inputs = request()->all();

        $userPersonProfile = UserPersonProfile::where(['id' => $inputs['UserPersonProfile']['id']])->first();
        $userPersonProfile->fill($inputs['UserPersonProfile']);

        $userPersonProfile->save();

        return Redirect::to('/ubication-info')->with('success', 'Tu ubicación ha sido actualizada.');
    }

    public function getBusinessInfo()
    {
        $personProfile  = UserPersonProfile::where(['user_id' => Auth::user()->id])->with('user')->first();
        $companyProfile = UserCompanyProfile::where(['user_id' => Auth::user()->id])->with('user')->first() ??
            new UserCompanyProfile();

        return view('profile.business-info')->with(compact('personProfile', 'companyProfile'));
    }

    public function postBusinessInfo()
    {
        $inputs = request()->all();

        $userCompanyProfile = UserCompanyProfile::where(['user_id' => Auth::user()->id])->with('user')->first() ??
            new UserCompanyProfile();
        $userCompanyProfile->fill($inputs['UserCompanyProfile']);
        $userCompanyProfile->user_id = Auth::user()->id;
        $userCompanyProfile->status  = 0;

        if (isset($inputs['UserCompanyProfile']['pre-mobile'])) {
            $mainMobile                 = preg_replace('/\D/', '', $inputs['UserCompanyProfile']['main-mobile']);
            $userCompanyProfile->mobile = $inputs['UserCompanyProfile']['pre-mobile'] . ' ' . $mainMobile;
        }

        if (isset($inputs['UserCompanyProfile']['pre-office-phone'])) {
            $mainOfficePhone                  = preg_replace(
                '/\D/',
                '',
                $inputs['UserCompanyProfile']['main-office-phone']
            );
            $userCompanyProfile->office_phone = $inputs['UserCompanyProfile']['pre-office-phone'] . ' ' . $mainOfficePhone;
        }

        if (isset($inputs['UserCompanyProfile']['id_confirmation'])) {
            $path                                = Storage::disk('public')->putFile(
                'profile-files',
                $inputs['UserCompanyProfile']['id_confirmation']
            );
            $userCompanyProfile->id_confirmation = 'storage/' . $path;
        }

        if (isset($inputs['UserCompanyProfile']['public_service_doc'])) {
            $path                                   = Storage::disk('public')->putFile(
                'profile-files',
                $inputs['UserCompanyProfile']['public_service_doc']
            );
            $userCompanyProfile->public_service_doc = 'storage/' . $path;
        }

        if (isset($inputs['UserCompanyProfile']['tax_id_doc'])) {
            $path                           = Storage::disk('public')->putFile(
                'profile-files',
                $inputs['UserCompanyProfile']['tax_id_doc']
            );
            $userCompanyProfile->tax_id_doc = 'storage/' . $path;
        }

        $userCompanyProfile->save();

        return Redirect::to('/business-info')->with('success', 'Tu perfil de empresa ha sido actualizado.');
    }

    public function getChangePassword()
    {
        $personProfile  = UserPersonProfile::where(['user_id' => Auth::user()->id])->with('user')->first();
        $companyProfile = UserCompanyProfile::where(['user_id' => Auth::user()->id])->with('user')->first() ??
            new UserCompanyProfile();

        return view('profile.change-password')->with(compact('personProfile', 'companyProfile'));
    }

    /**
     * @return RedirectResponse
     */
    public function postChangePassword(): RedirectResponse
    {
        $inputs = request()->all();

        $user = Auth::user();

        if (Hash::check($inputs['password'], $user->password)) {

            if (strlen($inputs['password-new']) > 6 && $this->passwordStrong($inputs['password-new'])) {

                if ($inputs['password-new'] === $inputs['password-confirmation']) {

                    $user->password = Hash::make($inputs['password-new']);
                    $user->save();

                    return Redirect::to('/change-password')->with('success', 'La contraseña ha sido cambiada.');
                }

                return Redirect::to('/change-password')->with('error', 'La confirmación de contraseña no coincide.');
            }

            return Redirect::to('/change-password')->with('error', 'La nueva contraseña es demasiado corta o debil.');
        }

        return Redirect::to('/change-password')->with('error', 'La contraseña actual ingresada es incorrecta.');
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    private function passwordStrong(string $password): bool
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@\d@', $password);

        return !(!$uppercase || !$lowercase || !$number);
    }

    public function getAuthConfig()
    {
        $personProfile  = UserPersonProfile::where(['user_id' => Auth::user()->id])->with('user')->first();
        $companyProfile = UserCompanyProfile::where(['user_id' => Auth::user()->id])->with('user')->first() ??
            new UserCompanyProfile();

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        if (Auth::user()->google2fa_secret !== null && Auth::user()->google2fa_secret !== '') {
            $secret = Auth::user()->google2fa_secret;
            //setting up
            $QR_image = $google2fa->getQRCodeInline(
                'American Kryptos Bank',
                Auth::user()->email,
                Auth::user()->google2fa_secret
            );
        } else {
            //generating secret key
            $secret = $google2fa->generateSecretKey();

            //setting up
            $QR_image = $google2fa->getQRCodeInline(
                'American Kryptos Bank',
                Auth::user()->email,
                $secret
            );
        }

        return view('profile.auth-config')->with(compact('QR_image', 'personProfile', 'secret', 'companyProfile'));
    }

    public function postAuthConfig()
    {
        $inputs = request()->all();

        $user = Auth::user();

        $google2fa = app('pragmarx.google2fa');

        if ($google2fa->verifyKey($inputs['secret'], $inputs['code'])) {

            $user->google2fa_secret = $inputs['secret'];
            $user->save();

            return Redirect::to('/auth-config')->with('success', 'Se ha activado la verificación a dos pasos.');
        }

        return Redirect::back()->with(
            'error',
            'Usted introdujo un código invalido, por favor vuelva a escanear la imagen y a introducir el código de seis digitos.'
        );
    }

    public function postDisable2fa()
    {
        $inputs = request()->all();

        $user = Auth::user();

        $google2fa = app('pragmarx.google2fa');

        if ($google2fa->verifyKey($inputs['secret'], $inputs['code'])) {

            $user->google2fa_secret = null;
            $user->save();

            return Redirect::to('/auth-config')->with('success', 'Se ha desactivado la verificación a dos pasos.');
        }

        return Redirect::back()->with('error', 'Usted introdujo un código invalido, por favor vuelva a intentar.');
    }

    public function getProfilesToApprove()
    {
        $profiles = UserPersonProfile::with('user')
            ->where('approval_status', 1)
            ->paginate(20);

        return view('profile.users-list')->with(compact('profiles'));
    }

    public function confirmCardAdmin()
    {
        $profiles = Tarjeta::with('user')
            ->where('verified', false)
            ->paginate(20);


        return view('profile.users-list-card')->with(compact('profiles'));
    }

    public function confirmCardAdminAction(Request $request, $tarjeta_id)
    {
        $tarjeta = Tarjeta::find($tarjeta_id);


        if ($request->proceso === 'Aprobado') :
            $tarjeta->verified = true;
            $tarjeta->active   = true;

            $data = [
                'url'       => URL::to('/'),
                'user_name' => $tarjeta->user->name,
                'subjects'  => 'Aprobar tu tarjeta **** **** **** ' . $tarjeta->numero,
                'message'   => $request->comentario ?? 'Felicidades',
            ];

            Mail::to($tarjeta->user->email)->send(new CardConfirm($data));
        else :
            $tarjeta->verified   = true;
            $tarjeta->active     = false;
            $tarjeta->comentario = $request->comentario;

            $data = [
                'url'       => URL::to('/'),
                'user_name' => $tarjeta->user->name,
                'subjects'  => 'rechazar tu tarjeta **** **** **** ' . $tarjeta->numero,
                'message'   => $request->comentario,
            ];

            Mail::to($tarjeta->user->email)->send(new CardConfirm($data));
        endif;

        $tarjeta->update();

        return Redirect::back()->with('success', 'La tarjeta se ha ' . $request->proceso);
    }


    public function getApproveProfile(Request $request, $id)
    {
        $profile = UserPersonProfile::with('user')->find($id);
        $user    = Auth::user();

        $profile->approval_status = 2;
        $profile->id_approved     = $user->id;
        $profile->approved_by_gps = $request->gps;
        $profile->save();

        $note            = new NotesUserProfile();
        $note->msg       = 'El perfil fue Aprobado';
        $note->client_id = $profile->user_id;
        $note->ip        = $request->ip();
        $note->trader_id = $user->id;
        $note->save();

        //$verification_link = URL::to('verify-account?token=' . $user->verification_token);
        $data = [
            'url'       => URL::to('/login'),
            'user_name' => $profile->first_name . ' ' . $profile->last_name,
        ];

        Mail::to($profile->user->email)->send(new ApproveProfile($data));

        return Redirect::to('/user-profile/' . $profile->user_id)->with('success', 'El perfil ha sido aprobado.');
    }

    /**
     * @param $id
     *
     * @return RedirectResponse
     */
    public function getBlockUser(Request $request, $id): RedirectResponse
    {
        $user = User::find($id);
        // $user->update([
        //     'password'       => $user->password . '--Block--Block',
        //     'email'          => $user->email . '--Block--Block',
        //     'session_id'     => 'block',
        //     'remember_token' => null,
        //     'is_logged_in'   => 0
        // ]);

        $user->password       = $user->password . '--Block--Block';
        $user->email          = $user->email . '--Block--Block';
        $user->session_id     = 'block';
        $user->remember_token = null;
        $user->is_logged_in   = 0;
        $user->save();

        $userProfile                  = UserPersonProfile::where('user_id', $id)->first();
        $userProfile->approval_status = 0;
        $userProfile->save();

        $userTrader      = Auth::user();
        $note            = new NotesUserProfile();
        $note->msg       = 'Ha Bloqueado al usuario';
        $note->client_id = $user->id;
        $note->ip        = $request->ip();
        $note->trader_id = $userTrader->id;
        $note->save();

        return Redirect::to('/user-profile/' . $user->id)->with('success', 'El usuario ha sido Bloqueado.');
    }

    /**
     * @param $id
     *
     * @return RedirectResponse
     */
    public function getUnblockUser(Request $request, $id): RedirectResponse
    {
        $user          = User::find($id);
        $cleanPassword = str_replace('--Block', '', $user->password);
        $cleanEmail    = str_replace('--Block', '', $user->email);
        // $user->update([
        //     'email'      => $cleanEmail,
        //     'password'   => $cleanPassword,
        //     'session_id' => null
        // ]);

        $userProfile                  = UserPersonProfile::where('user_id', $id)->first();
        $userProfile->approval_status = 1;
        $userProfile->save();

        $user->email      = $cleanEmail;
        $user->password   = $cleanPassword;
        $user->session_id = null;
        $user->save();

        $userTrader      = Auth::user();
        $note            = new NotesUserProfile();
        $note->msg       = 'Ha Desbloqueado al usuario';
        $note->client_id = $user->id;
        $note->ip        = $request->ip();
        $note->trader_id = $userTrader->id;
        $note->save();

        return Redirect::to('/user-profile/' . $user->id)->with('success', 'El usuario ha sido Desbloqueado.');
    }

    public function postRefuseProfile(Request $request, $id)
    {
        $inputs  = request()->all();
        $profile = UserPersonProfile::with('user')->find($id);
        $profile->update([
            'approval_status'   => 3,
            'datos_verified'    => 0,
            'identity_verified' => 0,
            'mobile_verified'   => 1,
        ]);

        $user     = Auth::user();
        $note     = new NotesUserProfile();
        $subjects = $inputs['subjects'];
        $msgArray = [];
        $msg      = 'Ha rechazado el perfil. ';

        $cont = 1;

        foreach ($subjects as $subject) {
            $subject = SubjectsRejectProfile::find($subject);

            $msgArray[] = $cont . ': ' . $subject->subject . '. Sugerencia: ' .
                $subject->details;

            $msg .= 'Movito ' . $cont . ' del rechazo: ' . $subject->subject . '. Sugerencia: ' .
                $subject->details . '.  ';

            $cont++;
        }

        $msg .= 'Con el mensaje: ' . $inputs['reasons'];

        $note->msg       = $msg;
        $note->client_id = $profile->user_id;
        $note->ip        = $request->ip();
        $note->trader_id = $user->id;
        $note->save();

        $data = [
            'url'       => URL::to('/user-info'),
            'user_name' => $profile->first_name . ' ' . $profile->last_name,
            'details'   => $msgArray,
            'message'   => $inputs['reasons'],
        ];

        Mail::to($profile->user->email)->send(new RefuseProfile($data));

        // return view('emails.refuse-profile')->with(compact('data'));

        return Redirect::to('/user-profile/' . $profile->user_id)->with('success', 'El perfil ha sido rechazado.');
    }

    /**
     * Sends confirmation SMS to mobile phone number.
     *
     * TODO set time
     */
    public function sendSmsConfirmation()
    {
        //POST on future
        /**
         * Post user ID
         * Check profile by user ID
         */
        $inputs        = request()->all();
        $destinyNumber = $inputs['phone_number'];
        $originNumber  = config('app.twilio_phone_number');
        $sid           = config('app.twilio_account_sid');
        $token         = config('app.twilio_auth_token');
        $userProfile   = UserPersonProfile::where(
            'user_id',
            $inputs['user_id']
        )
            ->first();

        if ($userProfile === null) {
            return response()->json(
                [
                    'error' => 'No existe el perfil del usuario',
                ]
            );
        }

        try {
            $twilioClient = new TwilioClient($sid, $token);
            $randomNumber = substr(str_shuffle('0123456789'), 0, 5);

            $twilioClient->messages->create(
                $destinyNumber,
                [
                    'from' => $originNumber,
                    'body' => 'Este es tu cod. de verificacion para usar en American Kryptos Bank: ' . $randomNumber,
                ]
            );

            $userProfile->mobile_verified          = false;
            $userProfile->mobile_verification_code = $randomNumber;
            $userProfile->save();

            return response()->json(
                [
                    'success' => 'Código envíado con éxtio',
                ]
            );
        } catch (ConfigurationException $e) {
            return response()->json(
                [
                    'error' => 'Error al configurar Twilio',
                ]
            );
        } catch (TwilioException $e) {
            return response()->json(
                [
                    'error' => 'Error al enviar el mensaje: ' . $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Verifies the code and confirm the phone.
     *
     * On Verification, save the number phone for the user profile
     * by user_id and verify. On return, the field must be readOnly
     */
    public function confirmSmsCode()
    {
        $inputs      = request()->all();
        $code        = $inputs['sms_code'];
        $userProfile = UserPersonProfile::where(
            'mobile_verification_code',
            $code
        )
            ->first();

        if ($userProfile === null) {
            return response()->json(
                [
                    'error' => 'No existe el perfil del usuario',
                ]
            );
        }

        $mobileParts                  = explode(' ', $inputs['phone_number']);
        $mainMobile                   = preg_replace('/\D/', '', $mobileParts[1]);
        $userProfile->mobile          = $mobileParts[0] . ' ' . $mainMobile;
        $userProfile->mobile_verified = true;
        $userProfile->save();

        return response()->json(
            [
                'success' => 'Número validado con éxito',
            ]
        );
    }
}
