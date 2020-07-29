<?php

namespace App\Http\Controllers;

use Response;
use App\Akb\Banker;
use App\User;
use App\UserPersonProfile;
Use App\UserRegistrationCode;
Use App\UserRegistrationCodeUse;
use App\UserExchangeTransactions;
use App\PromotorPayment;
use App\UserWalletsTransactions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;


function truncate_text($text)
{
    $parts       = explode("@", $text);
    $percent     = (int)(strlen($parts[0]) * 0.5);
    $replacement = str_repeat("*", $percent);

    for ($i = 0; $i < strlen($replacement); $i++) {
        $text[$i] = $replacement[$i];
    }

    return $text;
}


class ReferralsController extends Controller
{
    /**
     * Signing logic with referral/promotion code checking.
     */
    public function showSignin()
    {
        if (request()->has('code')) {
            $code          = request()->code;
            $code_instance = UserRegistrationCode::where('code', $code)->firstOrFail();

            return \View::make('signin')->with('code', $code_instance);
        }

        return view('signin');
    }

    /**
     * Shows the main index view for referral information.
     */
    public function showIndex()
    {
        $code              = self::retrieveReferralCode();
        $referrals         = self::retrieveReferralsCodeUses($code)
            ->limit(4)
            ->get();
        $referrals_data    = self::augmentReferralsData($referrals, $code);
        $transactions      = self::retrieveReferralsTransactions($code)
            ->orderBy('id', 'DESC')
            ->limit(4)
            ->get();
        $transactions_data = self::augmentTransactionsData($transactions, $code);
        $totalPayed        = $code->totalPayed();

//        foreach ($referrals_data['referrals'] as $referral) {
//            $totalEarned += $referral['fiat_percentage'];
//        }

        //AllUsers
        $totalEarnings     = 0;
        $currentDebt       = 0;
        $lastPayments      = PromotorPayment::where(['code_id' => $code->id])//Only operations after the last payment date for calculate Debt.
        ->where('payment_total', '>', 0)
            ->orderBy('id', 'desc')
            ->get();
        $lastPaymentDate   = count($lastPayments) > 0 ? $lastPayments[0]->payment_date : null;
        $codeDisabledDate  = $code->disabled_at;
        $referred_user_ids = UserRegistrationCodeUse
            ::where('code_id', $code->id)
            ->pluck('user_id');

        $all_referred_users = User::whereIn('id', $referred_user_ids)
            ->with([
                'exchangeTransactions' => static function ($query) use (
                    $lastPaymentDate,
                    $codeDisabledDate
                ) {
                    $query->where(['status' => 1])
                        ->where('payment_way', '!=', 'userWallet');

                    //If there's a date from the last payment
                    if ($lastPaymentDate) {
                        $query->where('approved_at', '>', $lastPaymentDate);
                    }

                    if ($codeDisabledDate) {
                        $query->where('approved_at', '<', $codeDisabledDate);
                    }

                    $query->with('walletTransaction');
                }
            ])
            ->with([
                'walletsTransactions' => static function ($query) use (
                    $lastPaymentDate,
                    $codeDisabledDate
                ) {
                    $query->where(['status' => 1])
                        ->where(['exchange_related' => 0])
                        ->where(['type' => 1]);

                    //If there's a date from the last payment
                    if ($lastPaymentDate) {
                        $query->where('approved_at', '>', $lastPaymentDate);
                    }

                    if ($codeDisabledDate) {
                        $query->where('approved_at', '<', $codeDisabledDate);
                    }
                }
            ])
            ->with([
                'codeRegistration' => static function ($query) use ($codeDisabledDate) {
//                    $query->where('is_payed', '!=', 1);

                    if ($codeDisabledDate) {
                        $query->where('created_at', '<', $codeDisabledDate);
                    }
                }
            ])->chunk(5000, static function ($referred_users) use ($code, &$currentDebt, $lastPaymentDate) {
                foreach ($referred_users as $referred_user) {
                    /** @var UserRegistrationCode $code */
                    $userDebt    = $code->getUserEarnings($referred_user, true, $lastPaymentDate);
                    $currentDebt += $userDebt;
                }
            });

        foreach ($lastPayments as $passPayment) {
            $totalEarnings += $passPayment['payment_total'];
        }

        return \View::make('profile.referrals-index')->with([
            'code'                        => $code,
            'totalDebt'                   => $currentDebt,
            'referrals_data'              => $referrals_data,
            'referrals_count'             => count($referred_user_ids),
            'referrals_transaction_count' => self::retrieveReferralsTransactionCount($code),
            'transactions_data'           => $transactions_data,
            'totalPayed'                  => $totalPayed,
            'totalEarned'                 => $totalEarnings
        ]);
    }

    /**
     * View to show which users have registered with the currently logged in users'
     * promotional code.
     */
    public function showReferralsList()
    {
        $code      = self::retrieveReferralCode();
        $uses      = self::retrieveReferralsCodeUses($code)->paginate(10);
        $referrals = self::augmentReferralsData($uses, $code);

        return \View::make('profile.referrals-list')->with([
            'referrals' => $referrals['referrals'],
            'uses'      => $uses,
            'code'      => $code->code
        ]);
    }

    /**
     * Shows the transanctions made by the logged in-user's referred users.
     */
    public function showReferralsTransactions()
    {
        $code                = self::retrievereferralcode();
        $transactions        = self::retrieveReferralsTransactions($code)->orderBy('id', 'DESC')->paginate(8);
        $walletsTransactions = self::retrieveReferralsWalletsTransactions($code)->orderBy('id', 'DESC')->paginate(8);
        $data                = self::augmentTransactionsData($transactions, $code);
        $walletsData         = self::augmentTransactionsData($walletsTransactions, $code);

        return \View::make('profile.referrals-transaction-list')->with([
            'data'              => $data,
            'code'              => $code->code,
            'paginator'         => $transactions,
            'wallets_data'      => $walletsData,
            'wallets_paginator' => $walletsTransactions
        ]);
    }

    /**
     * Shows the transanctions made by the logged in-user's referred users.
     */
    public function showReferralsPayments()
    {
        $code     = self::retrievereferralcode();
        $payments = PromotorPayment::where(['code_id' => $code->id])->paginate(8);

        return \View::make('profile.referrals-payments-list')->with([
            'code'     => $code->code,
            'payments' => $payments
        ]);
    }

    /**
     * Shows the publicists view of the admin.
     */
    public function showAdminPublicists()
    {
        $all_codes        = UserRegistrationCode::paginate(8);
        $publicists       = self::augmentAdminPublicistData($all_codes);
        $totalCurrentDebt = self::getTotalCurrentDebt($publicists);

        return \View::make('admin.publicists')->with([
            'publicist_count'           => self::countPublicists(),
            'total_referred_user_count' => self::countTotalReferredUsers(),
            'publicists'                => $publicists,
            'paginator'                 => $all_codes,
            'total_current_debt'        => $totalCurrentDebt
        ]);
    }

    /**
     * Shows the individual publicist detail admin view.
     *
     * @param int $user_id
     * @param int $code_id
     *
     * @return
     */
    public function showAdminUser(int $user_id, int $code_id)
    {
        /** @var UserRegistrationCode $code */
        $user              = User::where('id', $user_id)->firstOrFail();
        $code              = UserRegistrationCode::where('id', $code_id)->firstOrFail();
        $profile           = UserPersonProfile::where('user_id', $user_id)->firstOrFail();
        $lastPayments      = PromotorPayment::where(['code_id' => $code->id])//Only operations after the last payment date for calculate Debt.
        ->where('payment_total', '>', 0)
            ->orderBy('id', 'desc')
            ->get();
        $totalEarnings     = 0;
        $currentDebt       = 0;
        $lastPaymentDate   = count($lastPayments) > 0 ? $lastPayments[0]->payment_date : null;
        $codeDisabledDate  = $code->disabled_at;
        
        $referred_user_ids_all = UserRegistrationCodeUse
        ::where('code_id', $code->id)
        ->pluck('user_id');

        $referred_user_ids = UserRegistrationCodeUse
        ::where('code_id', $code->id)
        ->pluck('user_id');
        
        $inputs = request()->all();

        if ( isset($inputs['status']) ) {
           
            $status       = $inputs['status'];
            
            if ($status) {
                $referred_user_ids  = UserPersonProfile::whereIn('user_id', $referred_user_ids)
                                                        ->where('approval_status', '=', $status)        
                                                        ->pluck('user_id');
            }
        }

        if ( isset($inputs['date-range']) ) {
           
            $date         = $inputs['date-range'];            

            $dateArray    = explode(" - ", $date);
            $dateStart    = trim(Carbon::parse($dateArray[0])->format('Y-m-d'));
            $dateEnd      = trim(Carbon::parse($dateArray[1])->format('Y-m-d'));
            
            $dateNow = Carbon::now()->format('Y-m-d');
            
            if ($dateNow == $dateStart && $dateNow == $dateEnd){
                $dateStart    = null;
                $dateEnd      = null;
            }

            if ($dateStart && $dateEnd && $dateStart != null && $dateEnd != null){
                $referred_user_ids = UserPersonProfile::whereIn('user_id', $referred_user_ids)
                                            ->where('created_at', '>=', $dateStart)
                                            ->where('created_at', '<=', $dateEnd)
                                            ->pluck('user_id');
            }
        }
        
        if ( isset($inputs['country']) ) {
           
            $country       = $inputs['country'];
            
            if ($country) {
                $referred_user_ids  = UserPersonProfile::whereIn('user_id', $referred_user_ids)
                                                        ->where('country', '=', $country)        
                                                        ->pluck('user_id');
            }
        }


        $usersWithTransactions = UserExchangeTransactions::select('user_id', DB::raw('count(user_id) AS Cantidad'))
                                                        ->whereIn('user_id', $referred_user_ids)
                                                        ->groupBy('user_id')
                                                        ->orderBy('Cantidad', 'DESC')
                                                        ->get();
                                                        
        $usersWithTransactionsWallet = UserWalletsTransactions::select('user_id', DB::raw('count(user_id) AS Cantidad'))
                                                        ->whereIn('user_id', $referred_user_ids)
                                                        ->where(['status' => 1])
                                                        ->where(['exchange_related' => 0])
                                                        ->where(['type' => 1])
                                                        ->groupBy('user_id')
                                                        ->orderBy('Cantidad', 'DESC')
                                                        ->get();
                                                        
                                                        
        
        $userid = $usersWithTransactions->pluck('user_id');
        $useridWallet = $usersWithTransactionsWallet->pluck('user_id');

        $userCount = $userid->count();
        $userCountWallet = $useridWallet->count();

        if( isset($inputs['transactions']) ){    

            if ( $inputs['transactions'] == 1)  {

                $referred_users    = User::whereIn('id', $userid)
                                        ->with([
                                            'exchangeTransactions' => static function ($query) {
                                                $query->where(['status' => 1]);

                                                $query->with('walletTransaction');
                                            }
                                        ])
                                        ->with([
                                            'walletsTransactions' => static function ($query) {
                                                $query->where(['status' => 1])
                                                    ->where(['exchange_related' => 0])
                                                    ->where(['type' => 1]);
                                            }
                                        ])
                                        ->with('codeRegistration')
                                        ->paginate(10);

            }elseif( $inputs['transactions'] == 2){

                $referred_users    = User::whereIn('id', $useridWallet)
                                        ->with([
                                            'exchangeTransactions' => static function ($query) {
                                                $query->where(['status' => 1]);

                                                $query->with('walletTransaction');
                                            }
                                        ])
                                        ->with([
                                            'walletsTransactions' => static function ($query) {
                                                $query->where(['status' => 1])
                                                    ->where(['exchange_related' => 0])
                                                    ->where(['type' => 1]);
                                            }
                                        ])
                                        ->with('codeRegistration')
                                        ->paginate(10);
            }

        }else{

            $referred_users    = User::whereIn('id', $referred_user_ids)
                                    ->with([
                                        'exchangeTransactions' => static function ($query) {
                                            $query->where(['status' => 1]);

                                            $query->with('walletTransaction');
                                        }
                                    ])
                                    ->with([
                                        'walletsTransactions' => static function ($query) {
                                            $query->where(['status' => 1])
                                                ->where(['exchange_related' => 0])
                                                ->where(['type' => 1]);
                                        }
                                    ])
                                    ->with('codeRegistration')
                                    ->paginate(10);
        }
        

//        $debugUsers = [];

        User::whereIn('id', $referred_user_ids)
            ->with([
                'exchangeTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where('payment_way', '!=', 'userWallet');

                    $query->with('walletTransaction');
                }
            ])
            ->with([
                'walletsTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where(['exchange_related' => 0])
                        ->where(['type' => 1]);
                }
            ])
            ->with([
                'codeRegistration' => static function ($query) use ($codeDisabledDate) {
                //    $query->where('is_payed', '!=', 1);

                    if ($codeDisabledDate !== null) {
                        $query->where('created_at', '<', $codeDisabledDate);
                    }
                }
            ])->chunk(5000,
                static function ($referred_users) use ($code, &$currentDebt, &$totalEarnings, $lastPaymentDate) {
                    foreach ($referred_users as $referred_user) {
                        $userDebt = $code->getUserEarnings($referred_user, true, $lastPaymentDate);
                    //    if ($userDebt > 0) {
                    //        $debugUsers[$referred_user->id] = $userDebt;
                    //    }
                        $currentDebt += $userDebt;
                    }
                });

//        dd($currentDebt);

        foreach ($lastPayments as $passPayment) {
            $totalEarnings += $passPayment['payment_total'];
        }

        $totalEarnings += $currentDebt;
        $data          = [];

        $whitout_profile = UserPersonProfile::whereIn('user_id', $referred_user_ids_all)
                                                    ->where('approval_status', '=', 0)        
                                                          ->count();
        $waiting_profile = UserPersonProfile::whereIn('user_id', $referred_user_ids_all)
                                                    ->where('approval_status', '=', 1)        
                                                          ->count();
        $approve_profile = UserPersonProfile::whereIn('user_id', $referred_user_ids_all)
                                                    ->where('approval_status', '=', 2)        
                                                          ->count();
        $reject_profile  = UserPersonProfile::whereIn('user_id', $referred_user_ids_all)
                                                    ->where('approval_status', '=', 3)        
                                                          ->count();

        foreach ($referred_users as $referred_user) {
            $userEarnings = $code->getUserEarnings($referred_user);
            $UserWalletTransactionsCount     = self::retrieveUserWalletsTransactions($referred_user->id)->count();
            $userTransactionsCount       = self::retrieveUserTransactions($referred_user->id)->count();
            $data[]       = [
                'user'               => $referred_user,
                'total_transactions' => $UserWalletTransactionsCount + $userTransactionsCount,
                'total_earnings'     => $userEarnings,
                'profile_user'       => UserPersonProfile::with('user')->where('user_id', '=', $referred_user->id)->first()
            ];
        }

        return \View::make('admin.user')->with([
            'user'                => $user,
            'userCount'           => $userCount,
            'userCountWallet'     => $userCountWallet,
            'code'                => $code,
            'profile'             => $profile,
            'list_data'           => $data,
            'paginator'           => $referred_users,
            'current_debt'        => $currentDebt,
            'total_earnings'      => $totalEarnings,
            'approve_profile'     => $approve_profile,
            'whitout_profile'     => $whitout_profile,
            'waiting_profile'     => $waiting_profile,
            'reject_profile'      => $reject_profile,
            'referred_user_count' => self::retrieveReferralsCodeUses($code)->count()
        ]);
    }

    public function showUserChart(int $code_id){
        
        $code              = UserRegistrationCode::where('id', $code_id)->firstOrFail();
        
        $referred_user_ids = UserRegistrationCodeUse
        ::where('code_id', $code_id)
        ->pluck('user_id');

        $inputs = request()->all();

        if(isset($inputs['chart'])){

            $date               = $inputs['chart'];
            $dateArray          = explode(" - ", $date);
            $dateStart          = trim(Carbon::parse($dateArray[0])->format('Y-m-d'));
            $dateEnd            = trim(Carbon::parse($dateArray[1])->format('Y-m-d'));
            $dateStartChart     = '2019-09-01';
            $dateEndChart       = '2019-10-01';
            $chartInfo          = User::whereIn('id', $referred_user_ids)
                                    ->select(DB::raw('DATE(created_at) AS created_date, COUNT(*) AS total'))
                                    ->whereBetween('created_at', [$dateStart, $dateEnd])
                                    ->groupBy('created_date')
                                    ->get();
        }

        return response(json_encode($chartInfo),200)->header('Content-type', 'text/plain');
    }

    public function downloadCSV(int $code_id){
        
        $now                    = Carbon::now()->format('d-m-Y g:i A');

        $code                   = UserRegistrationCode::where('id', $code_id)->firstOrFail();

        $referred_user_ids_all  = UserRegistrationCodeUse::where('code_id', $code->id)
                                                        ->pluck('user_id');

        $table                  = UserPersonProfile::whereIn('user_id', $referred_user_ids_all)
                                                    ->where('approval_status', '=', 3)->get();

        $filename               = 'usersRejectsFromCode'. $code->code . '-' .$now. '.csv';
        $handle                 = fopen($filename, 'w+');

        fputcsv($handle, array('Name', 'email', 'created at'));
    
        foreach($table as $row) {
            
            $fullname = $row['first_name'] . ' ' . $row['last_name'];

            fputcsv($handle, array($fullname, $row['email'], $row['created_at']));
        }

        fclose($handle);
    
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        return Response::download($filename, 'usersRejectsFromCode'. $code->code . '-' .$now. '.csv', $headers);
    }

    /**
     * @param int $user_id
     * @param int $code_id
     *
     * @return mixed
     */
    public function showAdminUserPayments(int $user_id, int $code_id)
    {
        $user              = User::where('id', $user_id)->firstOrFail();
        $code              = UserRegistrationCode::where('id', $code_id)->firstOrFail();
        $profile           = UserPersonProfile::where('user_id', $user_id)->firstOrFail();
        $promotor_payments = PromotorPayment::where('code_id', $code->id)->paginate(8);
        $referred_user_ids = UserRegistrationCodeUse
            ::where('code_id', $code->id)
            ->pluck('user_id');
        $currentDebt       = 0;
        $lastPaymentDate   = PromotorPayment::where(['code_id' => $code->id])//Only operations after the last payment date for calculate Debt.
        ->where('payment_total', '>', 0)
            ->orderBy('id', 'desc')
            ->first();
        $lastPaymentDate   = $lastPaymentDate ? $lastPaymentDate->payment_date : null;
        $codeDisabledDate  = $code->disabled_at;

        User::whereIn('id', $referred_user_ids)
            ->with([
                'exchangeTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where('payment_way', '!=', 'userWallet');

                    $query->with('walletTransaction');
                }
            ])
            ->with([
                'walletsTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where(['exchange_related' => 0])
                        ->where(['type' => 1]);
                }
            ])
            ->with([
                'codeRegistration' => static function ($query) use ($codeDisabledDate) {
//                    $query->where('is_payed', '!=', 1);

                    if ($codeDisabledDate) {
                        $query->where('created_at', '<', $codeDisabledDate);
                    }
                }
            ])->chunk(5000,
                static function ($referred_users) use ($code, &$currentDebt, $lastPaymentDate) {
                    foreach ($referred_users as $referred_user) {
                        /** @var UserRegistrationCode $code */
                        $userDebt    = $code->getUserEarnings($referred_user, true, $lastPaymentDate);
                        $currentDebt += $userDebt;
                    }
                });

        return \View::make('admin.user-payments')->with([
            'user'         => $user,
            'code'         => $code,
            'profile'      => $profile,
            'list_data'    => $promotor_payments,
            'paginator'    => $promotor_payments,
            'current_debt' => $currentDebt
        ]);
    }

    /**
     * Shows the individual user view of the publicist.
     *
     * @param int $user_id
     *
     * @return
     */
    public function showPublicistsUser(int $user_id)
    {
        /** @var UserRegistrationCode $code */
        // Cant view if the user_id is not a referral made by the logged-in user
        if (!self::isReferralOf(\Auth::user()->id, $user_id)) {
            abort(404);
        }

        $user                       = User::where('id', $user_id)
            ->with('codeRegistration')
            ->with([
                'exchangeTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where('payment_way', '!=', 'userWallet')
                        ->with('walletTransaction');
                }
            ])
            ->with([
                'walletsTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where(['exchange_related' => 0])
                        ->where(['type' => 1]);
                }
            ])
            ->firstOrFail();
        $code                       = UserRegistrationCode::find($user->codeRegistration->code_id);
        $profile                    = UserPersonProfile::where('user_id', $user->id)->firstOrFail();
        $transactions               = self::retrieveUserTransactions($user->id)->orderBy('id', 'DESC')->paginate(8);
        $walletsTransactions        = self::retrieveUserWalletsTransactions($user->id)->orderBy(
            'id',
            'DESC'
        )->paginate(8);
        $allTransactions            = self::retrieveUserTransactions($user->id)->orderBy('id', 'DESC')->get();
        $allWalletsTransactions     = self::retrieveUserWalletsTransactions($user->id)->orderBy(
            'id',
            'DESC'
        )->get();
        $data                       = [];
        $walletsData                = [];
        $total_earnings             = $code->getUserEarnings($user);
        $total_processed_money      = 0;
        $concreted_transactions     = 0;
        $non_concreted_transactions = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->approved_at !== null) {
                $approved = false;
            } else {
                $approved = true;
            }

            if ($transaction->rejected_at !== null) {
                $rejected = false;
            } else {
                $rejected = true;
            }

            if ($transaction->status === 5) {
                $badge      = 'badge-info';
                $badge_text = 'Refund';
                $strike     = '';
            } elseif ($transaction->status === 2) {
                $badge      = 'badge-danger';
                $badge_text = 'Rechazada';
                $strike     = 'text-strike text-danger';
            } elseif ($transaction->status === 3) {
                $badge      = 'badge-danger';
                $badge_text = 'Fallida';
                $strike     = 'text-strike text-danger';
            } else {
                $badge      = 'badge-success';
                $badge_text = 'Completada';
                $strike     = '';
            }

            $commissionTotal = $transaction->walletTransaction ?
                ($code->profit_percent / 100) * $transaction->walletTransaction->amount :
                ($code->profit_percent / 100) * $transaction->amount;

            $data[] = [
                'approved'              => $approved,
                'rejected'              => $rejected,
                'currency'              => $transaction->sender_fiat,
                'transaction'           => $transaction,
                'commission_total'      => $commissionTotal,
                'commission_percentage' => $code->profit_percent . '%',
                'badge'                 => $badge,
                'badge_text'            => $badge_text,
                'strike'                => $strike,
            ];
        }

        foreach ($allTransactions as $transaction) {
            if ($transaction->status === 5) {
                ++$non_concreted_transactions;
            } elseif ($transaction->status === 2) {
                ++$non_concreted_transactions;
            } elseif ($transaction->status === 3) {
                ++$non_concreted_transactions;
            } else {
                ++$concreted_transactions;
                $amountToSum           = $transaction->walletTransaction ?
                    $transaction->walletTransaction->amount : $transaction->amount;
                $total_processed_money += $amountToSum;
            }
        }

        foreach ($walletsTransactions as $transaction) {
            if ($transaction->approved_at !== null) {
                $approved = false;
            } else {
                $approved = true;
            }

            if ($transaction->rejected_at !== null) {
                $rejected = false;
            } else {
                $rejected = true;
            }

            if ($transaction->status === 5) {
                $badge      = 'badge-info';
                $badge_text = 'Refund';
                $strike     = '';
            } elseif ($transaction->status === 2) {
                $badge      = 'badge-danger';
                $badge_text = 'Rechazada';
                $strike     = 'text-strike text-danger';
            } elseif ($transaction->status === 3) {
                $badge      = 'badge-danger';
                $badge_text = 'Fallida';
                $strike     = 'text-strike text-danger';
            } else {
                $badge      = 'badge-success';
                $badge_text = 'Completada';
                $strike     = '';
            }

            $commissionTotal = $transaction->walletTransaction ?
                ($code->profit_percent / 100) * $transaction->walletTransaction->amount :
                ($code->profit_percent / 100) * $transaction->amount;

            $walletsData[] = [
                'approved'              => $approved,
                'rejected'              => $rejected,
                'currency'              => $transaction->sender_fiat,
                'transaction'           => $transaction,
                'commission_total'      => $commissionTotal,
                'commission_percentage' => $code->profit_percent . '%',
                'badge'                 => $badge,
                'badge_text'            => $badge_text,
                'strike'                => $strike,
            ];
        }

        foreach ($allWalletsTransactions as $transaction) {
            if ($transaction->status === 5) {
                ++$non_concreted_transactions;
            } elseif ($transaction->status === 2) {
                ++$non_concreted_transactions;
            } elseif ($transaction->status === 3) {
                ++$non_concreted_transactions;
            } else {
                ++$concreted_transactions;
                $amountToSum           = $transaction->walletTransaction ?
                    $transaction->walletTransaction->amount : $transaction->amount;
                $total_processed_money += $amountToSum;
            }
        }

        return \View::make('profile.referrals-publicist-user')->with([
            'user'                           => $user,
            'code'                           => $code,
            'profile'                        => $profile,
            'truncated_email'                => truncate_text($user->email),
            'data'                           => $data,
            'paginator'                      => $transactions,
            'wallets_data'                   => $walletsData,
            'wallets_paginator'              => $walletsTransactions,
            'total_processed_money'          => $total_processed_money,
            'total_processed_money_currency' => 'USD',
            'total_earnings'                 => $total_earnings,
            'total_earnings_currency'        => 'USD',
            'total_earnings_percentage'      => $code->profit_percent . '%',
            'concreted_transactions'         => $concreted_transactions,
            'non_concreted_transactions'     => $non_concreted_transactions
        ]);
    }

    /**
     * Checks if the given promotor_id did refer the given referred_id.
     */
    public static function isReferralOf($promotor_id, $referred_id)
    {
        $use = UserRegistrationCodeUse::where('user_id', $referred_id)->first();

        if (!$use) {
            return false;
        }

        if ($use->code->user_id === $promotor_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retrieves the user registration code instance of the logged-in user.
     */
    public static function retrieveReferralCode(): UserRegistrationCode
    {
        $user_id   = \Auth::user()->id;
        $user_code = UserRegistrationCode::where('user_id', $user_id)->firstOrFail();

        return $user_code;
    }

    /**
     * Count the referrals that have logged in with the given referral code.
     */
    public static function retrieveCodeReferralsCount(UserRegistrationCode $code): int
    {
        $uses = UserRegistrationCodeUse::where('code_id', $code->id)->get();

        return count($uses);
    }

    /**
     * Returns the total number of registration codes that there are in the database.
     * This number is also the total number of publicists in the platform.
     */
    public static function countPublicists(): int
    {
        return UserRegistrationCode::count();
    }

    /**
     * Returns the total number of referrals that have been done in the platform.
     */
    public static function countTotalReferredUsers(): int
    {
        return UserRegistrationCodeUse::count();
    }

    /**
     * Retrieves the count of transactions that the users that have been referred by the
     * passed referral code.
     *
     * user_exchange_transactions.status value meanings:
     *  0 Open
     *  1 Completed
     *  2 Rejected
     *  3 Failed
     *  4 In process
     *  5 Refund
     */
    public static function retrieveReferralsTransactionCount(UserRegistrationCode $code): int
    {
        $referred_user_ids = UserRegistrationCodeUse
            ::where('code_id', $code->id)
            ->pluck('user_id');

        return UserExchangeTransactions
            ::whereIn('user_id', explode(',', $referred_user_ids))
            ->where('status', '!=', 0)
            ->where('status', '!=', 4)
            ->count();
    }

    /**
     * Retrieve the UserExchangeTransactions instances associated with the given user_id.
     */
    public static function retrieveUserTransactions($user_id)
    {
        return UserExchangeTransactions
            ::where('user_id', $user_id)
            ->where('status', '!=', 0)
            ->where('status', '!=', 4);
    }


    /**
     * Retrieve the UserWalletsTransactions instances associated with the given user_id.
     *
     * @param $user_id
     *
     * @return
     */
    public static function retrieveUserWalletsTransactions($user_id)
    {
        return UserWalletsTransactions
            ::where('user_id', $user_id)
            ->where(['status' => 1])
            ->where(['exchange_related' => 0])
            ->where(['type' => 1]);
    }

    /**
     * Retrieves the transactions that have been made by the referrals of the given referral code.
     *
     * @param UserRegistrationCode $code
     *
     * @return
     */
    public static function retrieveReferralsTransactions(UserRegistrationCode $code)
    {
        $referred_user_ids = UserRegistrationCodeUse
            ::where('code_id', $code->id)
            ->pluck('user_id');

        return UserExchangeTransactions
            ::whereIn('user_id', explode(',', $referred_user_ids))
            ->where('payment_way', '!=', 'userWallet')
            ->where('status', '!=', 0)
            ->where('status', '!=', 4)
            ->with('walletTransaction');
    }

    /**
     * Retrieves the wallets transactions that have been made by the referrals of the given referral code.
     *
     * @param UserRegistrationCode $code
     *
     * @return
     */
    public static function retrieveReferralsWalletsTransactions(UserRegistrationCode $code)
    {
        $referred_user_ids = UserRegistrationCodeUse
            ::where('code_id', $code->id)
            ->pluck('user_id');

        return UserWalletsTransactions
            ::whereIn('user_id', explode(',', $referred_user_ids))
            ->where(['status' => 1])
            ->where(['exchange_related' => 0])
            ->where(['type' => 1]);
    }

    /**
     * Augments referral data with additional values intended for the views.
     *
     * @param                           $uses
     * @param UserRegistrationCode|null $code
     *
     * @return array
     */
    public static function augmentReferralsData($uses, UserRegistrationCode $code = null): array
    {
        $referrals = [];
//        $totalDebt       = 0;
//        $lastPaymentDate = PromotorPayment::where(['code_id' => $code->id])//Only operations after the last payment date for calculate Debt.
//        ->orderBy('id', 'desc')
//            ->first();
//        $lastPaymentDate = $lastPaymentDate ? $lastPaymentDate->payment_date : null;

        $referred_user_ids  = $uses->pluck('user_id');
        $all_referred_users = User
            ::whereIn('id', $referred_user_ids)
            ->with([
                'exchangeTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where('payment_way', '!=', 'userWallet');

                    $query->with('walletTransaction');
                }
            ])
            ->with([
                'walletsTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where(['exchange_related' => 0])
                        ->where(['type' => 1]);
                }
            ])
            ->with('codeRegistration')
            ->get();

//        dd($all_referred_users);

        foreach ($all_referred_users as $referredUser) {
//            $referredUser = User
//                ::where(['id' => $use->user->id])
//                ->with([
//                    'exchangeTransactions' => static function ($query) {
//                        $query->where(['status' => 1])
//                            ->with('walletTransaction');

//            $query->with('walletTransaction');
//                    }
//                ])
//                ->with([
//                    'walletsTransactions' => static function ($query) {
//                        $query->where(['status' => 1])
//                            ->where(['exchange_related' => 0])
//                            ->where(['type' => 1]);
//                    }
//                ])
//                ->with('codeRegistration')
//                ->first();

            $transaction_count = $referredUser->exchangeTransactions->count() +
                $referredUser->walletsTransactions->count();

            // All the earnings from this user.
            $fiat_percentage = $code->getUserEarnings($referredUser);
//            $totalDebt       += $code->getUserEarnings($referredUser, true, $lastPaymentDate);
            $referrals[] = [
//                'data'              => $use,
'user'              => $referredUser,
'transaction_count' => $transaction_count,
'fiat_percentage'   => $fiat_percentage,
'truncated_email'   => truncate_text($referredUser->email)
            ];
        }

//        $referralsData['totalDebt'] = $totalDebt;
        $referralsData['referrals'] = $referrals;

        return $referralsData;
    }

    public static function retrieveReferralsCodeUses(UserRegistrationCode $code)
    {
        return UserRegistrationCodeUse::where('code_id', $code->id);
    }

    /**
     * Augments transaction data with additional values intended for the views.
     *
     * Notes as per development discussion (2019-09-20, Ignacio & José):
     * - Refund must be blue in the UI
     * - Failed and Rejected must be red in the UI
     * - Open and In Process should *not* appear on the list.
     *
     * @param                           $transactions
     * @param UserRegistrationCode|null $code
     *
     * @return array
     */
    public static function augmentTransactionsData($transactions, UserRegistrationCode $code = null): array
    {
        $data = [];

        foreach ($transactions as $transaction) {
            $user = User::where('id', $transaction->user_id)->first();

            if ($transaction->approved_at !== null) {
                $approved = false;
            } else {
                $approved = true;
            }

            if ($transaction->rejected_at !== null) {
                $rejected = false;
            } else {
                $rejected = true;
            }

            if ($transaction->status === 5) {
                $badge      = 'badge-info';
                $badge_text = 'Refund';
                $strike     = '';
            } elseif ($transaction->status === 2) {
                $badge      = 'badge-danger';
                $badge_text = 'Rechazada';
                $strike     = 'text-strike text-danger';
            } elseif ($transaction->status === 3) {
                $badge      = 'badge-danger';
                $badge_text = 'Fallida';
                $strike     = 'text-strike text-danger';
            } else {
                $badge      = 'badge-success';
                $badge_text = 'Completada';
                $strike     = '';
            }

            $commissionTotal = $transaction->walletTransaction ?
                ($code->profit_percent / 100) * $transaction->walletTransaction->amount :
                ($code->profit_percent / 100) * $transaction->amount;

            $data[] = [
                'user'                  => $user,
                'truncated_email'       => truncate_text($user->email),
                'approved'              => $approved,
                'rejected'              => $rejected,
                'currency'              => $transaction->sender_fiat,
                'transaction'           => $transaction,
                'commission_total'      => $commissionTotal,
                'commission_percentage' => $code->profit_percent . '%',
                'badge'                 => $badge,
                'badge_text'            => $badge_text,
                'strike'                => $strike
            ];
        }

        return $data;
    }

    public static function retrieveAccumulatedReferralsPayment()
    {

    }

    public static function retrieveTotalReferralsPayment()
    {

    }

    /**
     * @param LengthAwarePaginator $codes
     *
     * @return array
     */
    public static function augmentAdminPublicistData(LengthAwarePaginator $codes): array
    {
        $data = [];

        foreach ($codes as $code) {
            //Fin Debt by code.
            $referred_user_ids = UserRegistrationCodeUse
                ::where('code_id', $code->id)
                ->pluck('user_id');
            //Only operations after the last payment date.
            $lastPaymentDate  = PromotorPayment::where(['code_id' => $code->id])
                ->where('payment_total', '>', 0)
                ->orderBy('id', 'desc')
                ->first();
            $lastPaymentDate  = $lastPaymentDate ? $lastPaymentDate->payment_date : null;
            $codeDisabledDate = $code->disabled_at;
            $totalCodeDebt    = 0;

            User::whereIn('id', $referred_user_ids)
                ->with([
                    'exchangeTransactions' => static function ($query) use (
                        $lastPaymentDate,
                        $codeDisabledDate
                    ) {
                        $query->where(['status' => 1])
                            ->where('payment_way', '!=', 'userWallet');

                        //If there's a date from the last payment
                        if ($lastPaymentDate) {
                            $query->where('approved_at', '>', $lastPaymentDate);
                        }

                        if ($codeDisabledDate) {
                            $query->where('approved_at', '<', $codeDisabledDate);
                        }

                        $query->with('walletTransaction');
                    }
                ])
                ->with([
                    'walletsTransactions' => static function ($query) use (
                        $lastPaymentDate,
                        $codeDisabledDate
                    ) {
                        $query->where(['status' => 1])
                            ->where(['exchange_related' => 0])
                            ->where(['type' => 1]);

                        //If there's a date from the last payment
                        if ($lastPaymentDate) {
                            $query->where('approved_at', '>', $lastPaymentDate);
                        }

                        if ($codeDisabledDate) {
                            $query->where('approved_at', '<', $codeDisabledDate);
                        }
                    }
                ])
                ->with([
                    'codeRegistration' => static function ($query) use ($codeDisabledDate) {
//                        $query->where('is_payed', '!=', 1);

                        if ($codeDisabledDate) {
                            $query->where('created_at', '<', $codeDisabledDate);
                        }
                    }
                ])->chunk(5000, static function ($referred_users) use ($code, &$totalCodeDebt, $lastPaymentDate) {
                    foreach ($referred_users as $referred_user) {
                        /** @var UserRegistrationCode $code */
                        $totalCodeDebt += $code->getUserEarnings($referred_user, true, $lastPaymentDate);
                    }
                });

            $data[] = [
                'user'            => $code->user,
                'code'            => $code,
                'referrals_count' => self::retrieveCodeReferralsCount($code),
                'debt'            => $totalCodeDebt,
                'debt_currency'   => 'USD'
            ];
        }

        return $data;
    }

    //Ignacio Salcedo

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveNewCode(): \Illuminate\Http\RedirectResponse
    {
        $inputs  = request()->all();
        $newCode = new UserRegistrationCode();

        $user = User::where(['email' => $inputs['user_email']])
            ->select('id')
            ->first();

        $newCode->code           = $inputs['promo_code'];
        $newCode->user_id        = $user->id;
        $newCode->profit_percent = (float)$inputs['profit_percent'];
        $newCode->referral_bonus = (float)$inputs['referral_bonus'];
        $newCode->save();

        return Redirect::to('/admin/publicists')->with('success', 'Nuevo código creado con éxito.');
    }

    /**
     * @param int $codeID
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCode(int $codeID): \Illuminate\Http\RedirectResponse
    {
        $inputs          = request()->all();
        $codeModel       = UserRegistrationCode::find($codeID);
        $codeModel->code = $inputs['promo_code'];
        $codeModel->save();

        return Redirect::to('/admin/user/' . $codeModel->user_id . '/' . $codeModel->id)
            ->with('success', 'El código se ha actualizado con éxito.');
    }

    /**
     * @param int $codeID
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disableCode(int $codeID): \Illuminate\Http\RedirectResponse
    {
        $inputs = request()->all();

        if ($inputs['disable-select-code'] === '1') {
            $codeModel              = UserRegistrationCode::find($codeID);
            $codeModel->is_disabled = 1;
            $codeModel->disabled_at = Carbon::now()->format('Y-m-d H:i:s');
            $codeModel->save();

            return Redirect::to('/admin/user/' . $codeModel->user_id . '/' . $codeModel->id)
                ->with('success', 'El código se ha deshabilitado.');
        }

        return back();
    }

    /**
     * @param array $publicists
     *
     * @return float
     */
    private static function getTotalCurrentDebt(array $publicists): float
    {
        $totalDebt = 0;
        foreach ($publicists as $publicist) {
            $totalDebt += $publicist['debt'];
        }

        return $totalDebt;
    }

    /**
     * Process publicist payment
     *
     * @param int $user_id
     * @param int $code_id
     *
     * @return mixed
     */
    public function showAdminUserPaymentPay(int $user_id, int $code_id)
    {
        $code              = UserRegistrationCode::where('id', $code_id)->firstOrFail();
        $referred_user_ids = UserRegistrationCodeUse::where('code_id', $code->id)
            ->pluck('user_id');
        $currentDebt       = 0;
        $lastPaymentDate   = PromotorPayment::where(['code_id' => $code->id])
            ->where('payment_total', '>', 0)//Only operations after the last payment date for calculate Debt.
            ->orderBy('id', 'desc')
            ->first();
        $lastPaymentDate   = $lastPaymentDate ? $lastPaymentDate->payment_date : null;
        $codeDisabledDate  = $code->disabled_at;

        User::whereIn('id', $referred_user_ids)
            ->with([
                'exchangeTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where('payment_way', '!=', 'userWallet');

                    $query->with('walletTransaction');
                }
            ])
            ->with([
                'walletsTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where(['exchange_related' => 0])
                        ->where(['type' => 1]);
                }
            ])
            ->with([
                'codeRegistration' => static function ($query) use ($codeDisabledDate) {
//                    $query->where('is_payed', '!=', 1);

                    if ($codeDisabledDate) {
                        $query->where('created_at', '<', $codeDisabledDate);
                    }
                }
            ])->chunk(5000, static function ($referred_users) use ($code, &$currentDebt, $lastPaymentDate) {
                foreach ($referred_users as $referred_user) {
                    /** @var UserRegistrationCode $code */
                    $currentDebt += $code->getUserEarnings($referred_user, true, $lastPaymentDate, true);
                }
            });

        //From here can't be reversed. The payment will be full processed.
//        foreach ($referred_users as $referred_user) {
//            //Verify and mark referral bonus
//            /** @var UserRegistrationCode $code */
//            $currentDebt += $code->getUserEarnings($referred_user, true, $lastPaymentDate, true);
//        }

        if ($currentDebt > 0) {
            //1st Recharge Wallet
            //Banker
            $banker   = new Banker();
            $walletID = $banker->createWallet('USD', $user_id);
            //Payment
            $publicistPayment                        = new PromotorPayment();
            $publicistPayment->paid_by               = Auth::user()->id;
            $publicistPayment->payment_total         = $currentDebt;
            $publicistPayment->currency              = 'USD';
            $publicistPayment->payment_date          = Carbon::now()->format('Y-m-d H:i:s');
            $publicistPayment->code_id               = $code->id;
            $publicistPayment->wallet_transaction_id = $banker->rechargeWalletCredits(
                $walletID,
                $currentDebt,
                'USD',
                'SpecialPayment',
                $user_id
            );
            $publicistPayment->save();
        }

        return Redirect::to('/admin/user-payments/' . $user_id . '/' . $code_id)->with(
            'success',
            'El pago se ha registrado con éxito'
        );
    }


    /**
     * Shows the individual user view of the publicist.
     *
     * @param int $user_id
     *
     * @return
     */
    public function adminShowPublicistsUser(int $user_id)
    {
        /** @var UserRegistrationCode $code */
        $user = User::where('id', $user_id)
            ->with('codeRegistration')
            ->with([
                'exchangeTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->with('walletTransaction');
                }
            ])
            ->with([
                'walletsTransactions' => static function ($query) {
                    $query->where(['status' => 1])
                        ->where(['exchange_related' => 0])
                        ->where(['type' => 1]);
                }
            ])
            ->firstOrFail();

        $code                       = UserRegistrationCode::find($user->codeRegistration->code_id);
        $profile                    = UserPersonProfile::where('user_id', $user->id)->firstOrFail();
        $transactions               = self::retrieveUserTransactions($user->id)->orderBy('id', 'DESC')->paginate(8);
        $allTransactions            = self::retrieveUserTransactions($user->id)->orderBy('id', 'DESC')->get();
        $walletsTransactions        = self::retrieveUserWalletsTransactions($user->id)->orderBy(
            'id',
            'DESC'
        )->paginate(8);
        $allWalletsTransactions     = self::retrieveUserWalletsTransactions($user->id)->orderBy(
            'id',
            'DESC'
        )->get();
        $data                       = [];
        $walletsData                = [];
        $total_earnings             = $code->getUserEarnings($user);
        $total_processed_money      = 0;
        $concreted_transactions     = 0;
        $non_concreted_transactions = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->approved_at !== null) {
                $approved = false;
            } else {
                $approved = true;
            }

            if ($transaction->rejected_at !== null) {
                $rejected = false;
            } else {
                $rejected = true;
            }

            if ($transaction->status === 5) {
                $badge      = 'badge-info';
                $badge_text = 'Refund';
                $strike     = '';
            } elseif ($transaction->status === 2) {
                $badge      = 'badge-danger';
                $badge_text = 'Rechazada';
                $strike     = 'text-strike text-danger';
            } elseif ($transaction->status === 3) {
                $badge      = 'badge-danger';
                $badge_text = 'Fallida';
                $strike     = 'text-strike text-danger';
            } else {
                $badge      = 'badge-success';
                $badge_text = 'Completada';
                $strike     = '';
            }

            $commissionTotal = $transaction->walletTransaction ?
                ($code->profit_percent / 100) * $transaction->walletTransaction->amount :
                ($code->profit_percent / 100) * $transaction->amount;

            $data[] = [
                'approved'              => $approved,
                'rejected'              => $rejected,
                'currency'              => $transaction->sender_fiat,
                'transaction'           => $transaction,
                'commission_total'      => $commissionTotal,
                'commission_percentage' => $code->profit_percent . '%',
                'badge'                 => $badge,
                'badge_text'            => $badge_text,
                'strike'                => $strike,
            ];
        }

        foreach ($allTransactions as $transaction) {
            if ($transaction->status === 5) {
                ++$non_concreted_transactions;
            } elseif ($transaction->status === 2) {
                ++$non_concreted_transactions;
            } elseif ($transaction->status === 3) {
                ++$non_concreted_transactions;
            } else {
                ++$concreted_transactions;
                $amountToSum           = $transaction->walletTransaction ?
                    $transaction->walletTransaction->amount : $transaction->amount;
                $total_processed_money += $amountToSum;
            }
        }

        foreach ($walletsTransactions as $transaction) {
            if ($transaction->approved_at !== null) {
                $approved = false;
            } else {
                $approved = true;
            }

            if ($transaction->rejected_at !== null) {
                $rejected = false;
            } else {
                $rejected = true;
            }

            if ($transaction->status === 5) {
                $badge      = 'badge-info';
                $badge_text = 'Refund';
                $strike     = '';
            } elseif ($transaction->status === 2) {
                $badge      = 'badge-danger';
                $badge_text = 'Rechazada';
                $strike     = 'text-strike text-danger';
            } elseif ($transaction->status === 3) {
                $badge      = 'badge-danger';
                $badge_text = 'Fallida';
                $strike     = 'text-strike text-danger';
            } else {
                $badge      = 'badge-success';
                $badge_text = 'Completada';
                $strike     = '';
            }

            $commissionTotal = $transaction->walletTransaction ?
                ($code->profit_percent / 100) * $transaction->walletTransaction->amount :
                ($code->profit_percent / 100) * $transaction->amount;

            $walletsData[] = [
                'approved'              => $approved,
                'rejected'              => $rejected,
                'currency'              => $transaction->sender_fiat,
                'transaction'           => $transaction,
                'commission_total'      => $commissionTotal,
                'commission_percentage' => $code->profit_percent . '%',
                'badge'                 => $badge,
                'badge_text'            => $badge_text,
                'strike'                => $strike,
            ];
        }

        foreach ($allWalletsTransactions as $transaction) {
            if ($transaction->status === 5) {
                ++$non_concreted_transactions;
            } elseif ($transaction->status === 2) {
                ++$non_concreted_transactions;
            } elseif ($transaction->status === 3) {
                ++$non_concreted_transactions;
            } else {
                ++$concreted_transactions;
                $amountToSum           = $transaction->walletTransaction ?
                    $transaction->walletTransaction->amount : $transaction->amount;
                $total_processed_money += $amountToSum;
            }
        }

        return \View::make('admin.publicist-referral')->with([
            'user'                           => $user,
            'profile'                        => $profile,
            'data'                           => $data,
            'paginator'                      => $transactions,
            'wallets_data'                   => $walletsData,
            'wallets_paginator'              => $walletsTransactions,
            'total_processed_money'          => $total_processed_money,
            'total_processed_money_currency' => 'USD',
            'total_earnings'                 => $total_earnings,
            'total_earnings_currency'        => 'USD',
            'total_earnings_percentage'      => $code->profit_percent . '%',
            'concreted_transactions'         => $concreted_transactions,
            'non_concreted_transactions'     => $non_concreted_transactions
        ]);
    }
}
