<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

Use App\UserCompanyProfile;
use App\UserExchangeTransactions;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'verification_token', 'is_verified', 'currency', 'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role', 'role_id');
    }

    /**
     * @return HasOne
     */
    public function personProfile(): HasOne
    {
        return $this->hasOne(UserPersonProfile::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function personProfileObject()
    {
        $personProfile   = UserPersonProfile::where(['user_id' => $this->id])->first();
        $profileComplete = 1;
//        foreach ($personProfile->getAttributes() as $attribute) {
//            if ($attribute === null) {
//                $profileComplete = 0;
//                break;
//            }
//        }
        $personProfile->profileCompletition = $profileComplete;

        return $personProfile;
    }

    public function userProfile()
    {
        $prof = UserCompanyProfile::where('user_id', $this->id)->first();

        if (is_null($prof)) {
            return 'Personal';
        }

        return 'Company';
    }

    public function bonusCoupon()
    {
        return $this->hasOne('App\BonusCoupon', 'merchant_id');
    }

    public function assignedExchanges()
    {
        return $this->hasMany('App\UserExchangeTransactions', 'trader_id');
    }

    public function assignedWalletsTransactions()
    {
        return $this->hasMany('App\UserWalletsTransactions', 'trader_id');
    }

    /**
     * @return HasMany
     */
    public function assignedExchangesChats(): HasMany
    {
        return $this->hasMany(UserExchangeTransactions::class, 'attended_by');
    }

    //Wallets

    /**
     * @return HasMany
     */
    public function assignedWalletsChats(): HasMany
    {
        return $this->hasMany(UserWalletsTransactions::class, 'attended_by');
    }

    public function getRating()
    {
        $userTransactions = UserExchangeTransactions::where(['user_id' => $this->id])
            ->where('status', '!=', 0)
            ->where('status', '!=', 4)
            ->select(['status', 'id'])
            ->get();
        $totalPercent     = count($userTransactions);
        $failed           = 0;
        $success          = 0;

//        if ($totalPercent > 4) {
        foreach ($userTransactions as $transaction) {
            if ($transaction->status === 2 || $transaction->status === 3 || $transaction->status === 5) {
                ++$failed;
            } elseif ($transaction->status === 1) {
                ++$success;
            }
        }

        $this['score'] = [
            'totalOperations' => $totalPercent,
            'successPercent'  => $totalPercent > 0 ? round((100 * $success) / $totalPercent, 2) : 0,
            'failurePercent'  => $totalPercent > 0 ? round((100 * $failed) / $totalPercent, 2) : 0,
        ];
//        } else {
//            $this['score'] = null;
//        }

        return $this;
    }

    /**
     * @return HasOne
     */
    public function codeRegistration(): HasOne
    {
        return $this->hasOne(UserRegistrationCodeUse::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function exchangeTransactions(): HasMany
    {
        return $this->hasMany(UserExchangeTransactions::class, 'user_id');
    }

    public function currencyWallet(): HasMany
    {
        return $this->hasMany(CurrencyWallet::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function walletsTransactions(): HasMany
    {
        return $this->hasMany(UserWalletsTransactions::class, 'user_id');
    }


    /**
     * Return true if user have some code
     *
     * @return bool
     */
    public function isPublicist(): bool
    {
        return UserRegistrationCode::where(['user_id' => $this->id])->first() ? true : false;
    }
}
