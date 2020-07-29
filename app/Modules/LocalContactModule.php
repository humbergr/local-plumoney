<?php


namespace App\Modules;

use App\LocalContact;
use App\ApiHelper;
use Auth;

class LocalContactModule
{
    /**
     * @param string $credential
     * @param array $request_params
     * @param float $amount
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createLocalContact(int $credential, array $request_params, float $amount): array
    {
        $local_contact = ApiHelper::getLocalBtc(
            $credential,
            '/api/contact_create/'.$request_params['ad_id'].'/',
            [
                'amount' => $amount,
            ],
            null,
            'POST'
        );

        if (isset($local_contact['status']) && $local_contact['status'] === 'error') return $local_contact;

        $contact_info = ApiHelper::getLocalBtc(
            $credential,
            '/api/contact_info/'.$local_contact['contact_id'].'/',
            null,
            null,
            'GET'
        );

        $our_contact = LocalContact::create([
            'ad_id'            => $contact_info['advertisement']['id'],
            'trader_id'        => Auth::user()->id,
            'local_contact_id' => $local_contact['contact_id'],
            'fiat_amount'      => $amount,
            'amount_btc'       => $contact_info['amount_btc'],
            'rate'             => $contact_info['amount'] / $contact_info['amount_btc'],
            'currency'         => $contact_info['currency'],
            'ad_username'      => $contact_info['advertisement']['advertiser']['name'],
            'trade_type'       => $contact_info['advertisement']['trade_type'],
        ]);

        return ['status' => 'success', 'info' => $our_contact];
    }
}
