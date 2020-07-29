<?php


namespace App\Modules;

use App\UserExchangeTransactions;
use Illuminate\Database\Eloquent\Collection;
use Auth;

class ExchangeModule
{
    /**
     * @param array $ids
     * @return Collection
     */
    public function getById(array $ids): Collection
    {
        return UserExchangeTransactions::find($ids);
    }

    /**
     * @param array $ids
     * @return object
     */
    public function getAmountById(array $ids)
    {
        return UserExchangeTransactions::select('receiver_fiat_amount')->find($ids);
    }

    /**
     * @param array $ids
     * @return float
     */
    public function getExchangeAmount(array $ids): float
    {
        $amounts = $this->getAmountById($ids);
        $total = 0;

        foreach ($amounts as $amount) {
            $total += (float)str_replace(',', '', $amount->receiver_fiat_amount);
        }

        return $total;
    }

    /**
     * @param array $ids
     * @param array $params
     * @return int
     */
    public function editById(array $ids, array $params): int
    {
        return UserExchangeTransactions::whereIn('id', $ids)
            ->update($params);
    }

    /**
     * @return Collection
     */
    public function getMyOpenExchanges(): Collection
    {
        return UserExchangeTransactions::with('destinationAccount')
            ->where([
                'trader_id' => Auth::user()->id,
                'status'    => 0
            ])
            ->whereNull('contact_id')
            ->get();
    }
}
