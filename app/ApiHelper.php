<?php

namespace App;

use App\Http\Controllers\TransactionController;
use App\Mail\TransactionsErrorMessage;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\MultipartStream;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;

//date_default_timezone_set('Etc/GMT+5');

class ApiHelper
{
    public static $masterStartDate = '2019-03-01T05:00:00+00:00';

    /**
     * @param                   $credential
     * @param                   $endpoint
     * @param null|array|string $params
     * @param array             $downloadFile
     * @param string            $method
     *
     * @param null              $raw
     *
     * @return bool|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getLocalBtc(
        $credential,
        $endpoint,
        $params = null,
        $downloadFile = [],
        $method = 'GET',
        $raw = null
    ) {
        $client = new Client();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential);

        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);
        $filePath        = null;
        $paramsToMessage = '';
        $requestOptions  = [
            //'debug'   => true,
            'headers' => []
        ];

        if ($params !== null) {
            if (!isset($params['document'])) {
                if (isset($params['order_by'])) {
                    $paramsToMessage = http_build_query($params);
                } else {
                    $requestOptions['form_params'] = $params;
                    $paramsToMessage               = http_build_query($params);
                }
            } else {
                $file_name = strtolower(str_replace(' ', '', $params['document']->getClientOriginalName()));
                $file_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $file_name);

                $multipart = [
                    [
                        'name'     => 'document',
                        'contents' => fopen($params['document'], 'r'),
                        'filename' => $file_name
                    ],
                    [
                        'name'     => 'msg',
                        'contents' => $params['msg']
                    ]
                ];

                $params['document']->move(base_path() . '/public/img/contact-imgs/' . '/', $file_name);

                $stream                                    = new MultipartStream($multipart, 'uploadkryptos');
                $requestOptions['headers']['Content-Type'] = 'multipart/form-data; boundary=uploadkryptos';
                $requestOptions['body']                    = $stream;

                $paramsToMessage = $stream->getContents();
            }
        }

        //creating signature
        $message   = $nonce . $key . $endpoint . $paramsToMessage;
        $signature = mb_strtoupper(
            hash_hmac('sha256', $message, $hmac_secret)
        );

        $requestOptions['headers']['Apiauth-Key']       = $key;
        $requestOptions['headers']['Apiauth-Nonce']     = $nonce;
        $requestOptions['headers']['Apiauth-Signature'] = $signature;

        if (!empty($downloadFile)) {
            $file                             = public_path() . '/img/from-lcb-files/' . $downloadFile['fileName'];
            $requestOptions['sink']           = $file;
            $requestOptions['decode_content'] = $downloadFile['fileType'];
        }

        try {
//            if (isset($params['order_by'])) {
            $res = $client->request(
                $method,
                'https://localbitcoins.com' . $endpoint . '?' . $paramsToMessage,
                $requestOptions
            );
//            } else {
//                $res = $client->request($method, 'https://localbitcoins.com' . $endpoint, $requestOptions);
//            }
        } catch (ClientException $e) {
            return [
                'status' => 'error',
                'info' => $e->getResponse()->getBody()->getContents()
            ];
        }

        if ($raw !== null) {
            return json_decode($res->getBody(), true);
        }

        return json_decode($res->getBody(), true)['data'];
    }


    /**
     * Return complete list of operations in an ordered array.
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public static function getCompleteList(): array
    {
        return self::recordAll();
//        $canceledTransactions1 = self::getLocalBtc(1, '/api/dashboard/canceled/');
//        $canceledTransactions2 = self::getLocalBtc(2, '/api/dashboard/canceled/');
//
//        self::recordCanceled($canceledTransactions1, $canceledTransactions2);
//
//        $walletData1           = self::getLocalBtc(1, '/api/wallet/');
//        $walletData2           = self::getLocalBtc(2, '/api/wallet/');
//        $releasedTransactions1 = self::getLocalBtc(1, '/api/dashboard/released/');
//        $releasedTransactions2 = self::getLocalBtc(2, '/api/dashboard/released/');
//
//        $walletData1           = self::setAccountName(1, $walletData1, 'wallet');
//        $walletData2           = self::setAccountName(2, $walletData2, 'wallet');
//        $releasedTransactions1 = self::setAccountName(1, $releasedTransactions1, 'released');
//        $releasedTransactions2 = self::setAccountName(2, $releasedTransactions2, 'released');
//        $walletData['received_transactions_30d'] = array_merge(
//            $walletData1['received_transactions_30d'],
//            $walletData2['received_transactions_30d']
//        );
//        $walletData['sent_transactions_30d']     = array_merge(
//            $walletData1['sent_transactions_30d'],
//            $walletData2['sent_transactions_30d']
//        );
//        $releasedTransactions                    = array_merge(
//            $releasedTransactions1['contact_list'],
//            $releasedTransactions2['contact_list']
//        );
//
//        usort($walletData1['received_transactions_30d'], array(self::class, 'sortWalletArray'));
//        usort($walletData1['sent_transactions_30d'], array(self::class, 'sortWalletArray'));
//        usort($walletData2['received_transactions_30d'], array(self::class, 'sortWalletArray'));
//        usort($walletData2['sent_transactions_30d'], array(self::class, 'sortWalletArray'));
//        usort($releasedTransactions1['contact_list'], array(self::class, 'sortTransactionsArray'));
//        usort($releasedTransactions2['contact_list'], array(self::class, 'sortTransactionsArray'));
//        var_dump([
//            $walletData['received_transactions_30d'],
//            $walletData['sent_transactions_30d']
//        ]);
//
//        var_dump($walletData);
//        var_dump($releasedTransactions['contact_list']);
//        die;
//
//        $relatedTransactionsList1 = self::getRelatedWalletTransactions(
//            $releasedTransactions1['contact_list'],
//            $walletData1
//        );
//
//        $relatedTransactionsList2 = self::getRelatedWalletTransactions(
//            $releasedTransactions2['contact_list'],
//            $walletData2
//        );
//        dd([
//            self::organizeStatistics($relatedTransactionsList1[2]),
//            self::organizeStatistics($relatedTransactionsList2[2])
//        ]);
//        die;
//        [
//            $walletData['received_transactions_30d'],
//            $walletData['sent_transactions_30d'],
//            $relations
//        ] = $relatedTransactionsList;
//
//        $walletData1['received_transactions_30d'] = $relatedTransactionsList1[0];
//        $walletData1['sent_transactions_30d']     = $relatedTransactionsList1[1];
//        $relations1                               = $relatedTransactionsList1[2];
//        $cleanedWalletData1                       = self::clearNeutralOutgoing(
//            $walletData1['received_transactions_30d'],
//            $walletData1['sent_transactions_30d']
//        );
//        $walletOperations1                        = self::walletOnlyFromDate($cleanedWalletData1);
//
//        $walletData2['received_transactions_30d'] = $relatedTransactionsList2[0];
//        $walletData2['sent_transactions_30d']     = $relatedTransactionsList2[1];
//        $relations2                               = $relatedTransactionsList2[2];
//        $cleanedWalletData2                       = self::clearNeutralOutgoing(
//            $walletData2['received_transactions_30d'],
//            $walletData2['sent_transactions_30d']
//        );
//        $walletOperations2                        = self::walletOnlyFromDate($cleanedWalletData2);
//
//        /**
//         * Test private send format
//         */
//        $regExCoinB       = '/:.+:/';
//        $strFormatExample = ':coinbank,3500,gdf12018,cristinadlr,Gabriel: _Cualquier Cosa_';
//        preg_match($regExCoinB, $strFormatExample, $matchesCbData);
//        $transactionInfoCb = $matchesCbData[0] ?? null;
//        if ($transactionInfoCb) {
//            $transactionInfoCb = \str_replace(':', '', $transactionInfoCb);
//            $transactionInfoCb = \explode(',', $transactionInfoCb);
//            //Información del movimiento interno.
//        }
//
//        var_dump($walletOperations);
//        var_dump($relations);
//        $completeList                               = [];
//        $completeList['cristinadlr']['outgoingBTC'] = array_merge(
//            $relations1['outgoingBTC'],
//            $walletOperations1['outgoingBTC']
//        );
//        $completeList['cristinadlr']['incomingBTC'] = array_merge(
//            $relations1['incomingBTC'],
//            $walletOperations1['incomingBTC']
//        );
//        $completeList['gdf12018']['outgoingBTC']    = array_merge(
//            $relations2['outgoingBTC'],
//            $walletOperations2['outgoingBTC']
//        );
//        $completeList['gdf12018']['incomingBTC']    = array_merge(
//            $relations2['incomingBTC'],
//            $walletOperations2['incomingBTC']
//        );
//
//        usort($completeList['cristinadlr']['outgoingBTC'], array(self::class, 'sortCompleteList'));
//        usort($completeList['cristinadlr']['incomingBTC'], array(self::class, 'sortCompleteList'));
//        usort($completeList['gdf12018']['outgoingBTC'], array(self::class, 'sortCompleteList'));
//        usort($completeList['gdf12018']['incomingBTC'], array(self::class, 'sortCompleteList'));
//
//        return $completeList;
    }

    /**
     * Analysis about LocalBTC facts.
     *
     * @param $outgoings
     *
     * @return array
     */
    public static function organizeStatistics($outgoings): array
    {
        $sameAmount      = [];
        $sameAmountVes   = [];
        $differentAmount = [];

        foreach ($outgoings['outgoingBTC'] as $key => $outgoing) {
            if ((string)$outgoing['contactInfo']['amount_btc'] === (string)$outgoing['walletTransaction']['amount']) {
                $sameAmount[] = $outgoing;
            } else {
                $differentAmount[] = $outgoing;
            }
        }

        foreach ($sameAmount as $key => $operation) {
            if ($operation['contactInfo']['currency'] === 'VES') {
                unset($sameAmount[$key]);
                $sameAmountVes[] = $operation;
            }
        }

        return [
            'totalOperations'         => $outgoings['outgoingBTC'],
            'sameWalletAnnounce'      => $sameAmount,
            'differentWalletAnnounce' => $differentAmount,
            'sameWalletAnnounceVES'   => $sameAmountVes,
        ];
    }

    /**
     * @param array $incomingBTC Array of wallet movements 'received_transactions_30d'
     * @param array $outgoingBTC Array of wallet movements 'sent_transactions_30d'
     *
     * @return array
     * @throws \Exception
     */
    public static function clearNeutralOutgoing(array $incomingBTC, array $outgoingBTC): array
    {
        foreach ($outgoingBTC as $key => $outTransaction) {
            if ($outTransaction['tx_type'] === 2) {
                unset($outgoingBTC[$key]);
            }
        }

        foreach ($outgoingBTC as $key => $outTransaction) {
            $regEx = '/#(\d+)/';
            preg_match_all($regEx, $outTransaction['description'], $matches, PREG_SET_ORDER);
            $contactID = $matches[0][1] ?? null;
            $partner   = false;

            foreach ($incomingBTC as $innerKey => $inTransaction) {
                if (str_contains($inTransaction['description'], $contactID)) {
                    $partner = true;
//                    if ($incomingBTC[$innerKey]['amount'] === '0.54992532') {
//                        var_dump($incomingBTC[$innerKey]);
//                        var_dump($outgoingBTC[$key]);
//                        die;
//                    }
                    $anchorClosedTransaction   = ClosedContactCache::where(
                        [
                            'contact_id' => $contactID
                        ]
                    )->first();
                    $anchorCanceledTransaction = canceledContactCache::where(
                        [
                            'contact_id' => $contactID
                        ]
                    )->first();

                    if ($anchorClosedTransaction && $anchorCanceledTransaction === null) {
                        //echo 'Salió y Entró, Está cerrado y no cancelado';
                        $canceledDb               = new canceledContactCache;
                        $canceledDb->account_name = $anchorClosedTransaction->account_name;
                        $canceledDb->json_data    = $anchorClosedTransaction->json_data;
                        $canceledDb->contact_id   = $anchorClosedTransaction->contact_id;
                        $canceledDb->save();
//                        var_dump($anchorClosedTransaction);
//                        die;
                    }

                    unset($incomingBTC[$innerKey], $outgoingBTC[$key]);
                }
            }

            if ($partner === false && $contactID) {
                $outTransaction['hold'] = true;
                $outgoingBTC[$key]      = $outTransaction;
            }
        }

        foreach ($outgoingBTC as $key => $outTransaction) {
            if ($outTransaction['tx_type'] === 1) {
                foreach ($outgoingBTC as $innerKey => $feeTransaction) {
                    if ($feeTransaction['created_at'] === $outTransaction['created_at']) {
                        $outTransaction['fee']  = $feeTransaction['amount'];
                        $outTransaction['type'] = 'noTrade';
                        unset($outgoingBTC[$innerKey]);
                        $outgoingBTC[$key] = $outTransaction;
                    }
                }
            }
        }

//        foreach ($incomingBTC as $key => $inTransaction) {
//            var_dump(self::validateAddress($inTransaction['description']));
//            if (strlen($inTransaction['description']) === 34) {
//              var_dump($inTransaction['description']);
//                var_dump(self::validateAddress($inTransaction['description']));
//            }
//        }

        return [
            'received_transactions_30d' => $incomingBTC,
            'sent_transactions_30d'     => $outgoingBTC
        ];
    }

    /**
     * Return many things:
     * [0] return Wallet 'received_transactions_30d'
     * [1] return Wallet 'sent_transactions_30d'
     * [2] return Related Wallet - Transactions array
     *
     * @param array $releasedTransactions
     * @param array $walletTransactions
     *
     * @return array
     */
    public static function getRelatedWalletTransactions(array $releasedTransactions, array $walletTransactions): array
    {
        $relations = [
            'outgoingBTC' => [],
            'incomingBTC' => [],
        ];
        $startDate = strtotime(self::$masterStartDate);

        foreach ($releasedTransactions as $contact) {
            $contactReleasedDate = strtotime($contact['data']['released_at']);

            if ($contactReleasedDate >= $startDate) {
                foreach ($walletTransactions['received_transactions_30d'] as $key => $walletTransaction) {
                    $regEx = '/#(\d+)/';
                    preg_match($regEx, $walletTransaction['description'], $matches);
                    $contactID = $matches[1] ?? null;

                    if ($contactID && $contact['data']['contact_id'] === (int)$contactID) {
                        $relation                      = [];
                        $relation['account_name']      = $contact['account_name'];
                        $relation['contactInfo']       = $contact['data'];
                        $relation['walletTransaction'] = $walletTransaction;
                        $relations['incomingBTC'][]    = $relation;
                        unset($walletTransactions['received_transactions_30d'][$key]);
                    }
                }

                foreach ($walletTransactions['sent_transactions_30d'] as $key => $walletTransaction) {
                    $regEx = '/#(\d+)/';
                    preg_match($regEx, $walletTransaction['description'], $matches);
                    $contactID = $matches[1] ?? null;

                    if ($contactID && $contact['data']['contact_id'] === (int)$contactID) {
                        $relation                      = [];
                        $relation['account_name']      = $contact['account_name'];
                        $relation['contactInfo']       = $contact['data'];
                        $relation['walletTransaction'] = $walletTransaction;
                        $relations['outgoingBTC'][]    = $relation;
                        unset($walletTransactions['sent_transactions_30d'][$key]);
                    }
                }
            }
        }

        return [
            $walletTransactions['received_transactions_30d'],
            $walletTransactions['sent_transactions_30d'],
            $relations
        ];
    }

    /**
     * @param array $cleanedWalletData
     *
     * @return array
     * @throws \Exception
     */
    public static function walletOnlyFromDate(array $cleanedWalletData): array
    {
        $startDate        = strtotime(self::$masterStartDate); //TODO to EST
        $walletOperations = [
            'outgoingBTC' => [],
            'incomingBTC' => []
        ];

        foreach ($cleanedWalletData['received_transactions_30d'] as $walletTransaction) {
            $walletTransactionDate = strtotime($walletTransaction['created_at']);
            if ($walletTransactionDate > $startDate) {
                $walletOperations['incomingBTC'][] = [
                    'account_name'      => $walletTransaction['account_name'],
                    'contactInfo'       => null,
                    'walletTransaction' => $walletTransaction
                ];
            }
        }

        foreach ($cleanedWalletData['sent_transactions_30d'] as $walletTransaction) {
            $walletTransactionDate = strtotime($walletTransaction['created_at']);
            if ($walletTransactionDate > $startDate) {
                $walletOperations['outgoingBTC'][] = [
                    'account_name'      => $walletTransaction['account_name'],
                    'contactInfo'       => null,
                    'walletTransaction' => $walletTransaction
                ];
            }
        }

        return $walletOperations;
    }

    /**
     * @param $a
     * @param $b
     *
     * @return false|int
     */
    public static function sortCompleteList($a, $b)
    {
        return strtotime($a['walletTransaction']['created_at']) - strtotime($b['walletTransaction']['created_at']);
    }

    /**
     * @param $a
     * @param $b
     *
     * @return false|int
     */
    public static function sortWalletArray($a, $b)
    {
        return strtotime($a['created_at']) - strtotime($b['created_at']);
    }

    /**
     * @param $a
     * @param $b
     *
     * @return false|int
     */
    public static function sortTransactionsArray($a, $b)
    {
        return strtotime($a['data']['released_at']) - strtotime($b['data']['released_at']);
    }

    /**
     * @param int    $int
     * @param array  $arrayData
     *
     * @param string $type
     *
     * @return array
     */
    public static function setAccountName(int $int, array $arrayData, string $type): array
    {
        $accountName = 'gdf12018';

        if ($int === 1) {
            $accountName = 'cristinadlr';
        }

        if ($type === 'wallet') {
            foreach ($arrayData['received_transactions_30d'] as $key => $walletTransaction) {
                $walletTransaction['account_name']            = $accountName;
                $arrayData['received_transactions_30d'][$key] = $walletTransaction;
            }
            foreach ($arrayData['sent_transactions_30d'] as $key => $walletTransaction) {
                $walletTransaction['account_name']        = $accountName;
                $arrayData['sent_transactions_30d'][$key] = $walletTransaction;
            }
        }

        if ($type === 'released') {
            foreach ($arrayData['contact_list'] as $key => $transaction) {
                $transaction['account_name']     = $accountName;
                $arrayData['contact_list'][$key] = $transaction;
            }
        }

        return $arrayData;
    }

    /**
     * @param $address
     *
     * @return bool
     * @throws \Exception
     */
    private static function validateAddress($address): bool
    {
        $decoded = self::decodeBase58($address);
        if ($decoded) {
            $d1 = hash('sha256', substr($decoded, 0, 21), true);
            $d2 = hash('sha256', $d1, true);

            if (substr_compare($decoded, $d2, 21, 4)) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param $input
     *
     * @return string|null
     * @throws \Exception
     */
    private static function decodeBase58($input): ?string
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

        $out = array_fill(0, 25, 0);
        for ($i = 0; $i < strlen($input); $i++) {
            if (($p = strpos($alphabet, $input[$i])) === false) {
                return null;
            }
            $c = $p;
            for ($j = 25; $j--;) {
                $c       += (int)(58 * $out[$j]);
                $out[$j] = (int)($c % 256);
                $c       /= 256;
                $c       = (int)$c;
            }
            if ($c != 0) {
                return null;
            }
        }

        $result = '';
        foreach ($out as $val) {
            $result .= chr($val);
        }

        return $result;
    }

    private static function recordCanceled(
        array $canceledTransactions1,
        array $canceledTransactions2,
        $total = null
    ): bool {
        foreach ($canceledTransactions1['contact_list'] as $item) {
            $canceledDb               = new canceledContactCache;
            $canceledDb->account_name = 'cristinadlr';
            $canceledDb->json_data    = $item['data'];
            $canceledDb->contact_id   = $item['data']['contact_id'];
            try {
                $canceledDb->save();
            } catch (QueryException $e) {
                continue;
            }
        }

        foreach ($canceledTransactions2['contact_list'] as $item) {
            $canceledDb               = new canceledContactCache;
            $canceledDb->account_name = 'gdf12018';
            $canceledDb->json_data    = $item['data'];
            $canceledDb->contact_id   = $item['data']['contact_id'];
            try {
                $canceledDb->save();
            } catch (QueryException $e) {
                continue;
            }
        }

        return true;
    }

    /**
     * @param $arr
     * @param $accountInt
     */
    private static function recursiveRecord($arr, $accountInt)
    {
        foreach ($arr as $contact) {
            $contactToDb       = ClosedContactCache::where([
                    'contact_id' => $contact['data']['contact_id']
                ])->first() ?? new ClosedContactCache;
            $anchorDateEST     = new Carbon($contact['data']['closed_at']);
            $anchorDateEST     = $anchorDateEST
                ->setTimezone('EST')
                ->format('Y-m-d H:i:s');
            $localFormatedDate = new Carbon($contact['data']['closed_at']);
            $localFormatedDate = $localFormatedDate
                ->format('Y-m-d H:i:s');

            if ($contact['data']['released_at'] !== null) {
                $contactToDb = ReleasedContactCache::where([
                        'contact_id' => $contact['data']['contact_id']
                    ])->first() ?? new ReleasedContactCache();
            }

            if ($contact['data']['canceled_at'] !== null) {
                $contactToDb = canceledContactCache::where([
                        'contact_id' => $contact['data']['contact_id']
                    ])->first() ?? new canceledContactCache;
            }

            if ($contact['data']['is_selling'] === true &&
                $contact['data']['released_at'] === null &&
                $contact['data']['canceled_at'] === null) {
                $contactToDb = canceledContactCache::where([
                        'contact_id' => $contact['data']['contact_id']
                    ])->first() ?? new canceledContactCache;
            }

            if ($contact['data']['is_buying'] === true &&
                $contact['data']['released_at'] === null &&
                $contact['data']['canceled_at'] === null) {
                $contactToDb = canceledContactCache::where([
                        'contact_id' => $contact['data']['contact_id']
                    ])->first() ?? new canceledContactCache;
            }

            if ($contact['data']['is_selling'] === true) {
                $contactToDb->is_selling = true;
                $contactToDb->is_buying  = false;
            }

            if ($contact['data']['is_buying'] === true) {
                $contactToDb->is_selling = false;
                $contactToDb->is_buying  = true;
            }

            if ($accountInt === 1) {
                $contactToDb->account_name = 'cristinadlr';
            }
            if ($accountInt === 2) {
                $contactToDb->account_name = 'gdf12018';
            }
            if ($accountInt === 3) {
                $contactToDb->account_name = 'AKB01';
            }

            $contactToDb->json_data            = $contact['data'];
            $contactToDb->contact_id           = $contact['data']['contact_id'];
            $contactToDb->anchor_date_localbtc = $localFormatedDate;
            $contactToDb->anchor_date_est      = $anchorDateEST;

            if (!isset($contactToDb->status) || $contactToDb->status === 0) {
                $contactToDb->status = 0;
            }

            if ($contact['data']['released_at'] !== null) {
                $releasedDateEST                = new Carbon($contact['data']['released_at']);
                $contactToDb->released_est      = $releasedDateEST
                    ->setTimezone('EST')
                    ->format('Y-m-d H:i:s');
                $localReleasedDate              = new Carbon($contact['data']['released_at']);
                $contactToDb->released_localbtc = $localReleasedDate
                    ->format('Y-m-d H:i:s');
                $fundedDateEST                  = new Carbon($contact['data']['funded_at']);
                $contactToDb->funded_est        = $fundedDateEST
                    ->setTimezone('EST')
                    ->format('Y-m-d H:i:s');
                $localFundedDate                = new Carbon($contact['data']['funded_at']);
                $contactToDb->funded_localbtc   = $localFundedDate
                    ->format('Y-m-d H:i:s');
            }

//            try {
            $contactToDb->save();
//            } catch (QueryException $e) {
//                continue;
//            }

            //Fix released without funded date
            $releasedFundedNull = ReleasedContactCache::where(['funded_est' => null])->get();
            foreach ($releasedFundedNull as $releasedContact) {
                $fundedDateEST                    = new Carbon($releasedContact['json_data']['funded_at']);
                $releasedContact->funded_est      = $fundedDateEST
                    ->setTimezone('EST')
                    ->format('Y-m-d H:i:s');
                $localFundedDate                  = new Carbon($releasedContact['json_data']['funded_at']);
                $releasedContact->funded_localbtc = $localFundedDate
                    ->format('Y-m-d H:i:s');

                $releasedContact->save();
            }
        }
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function recordAll()
    {
        $genesis = false;

        $closedTransactions1 = self::getLocalBtc(
            1,
            '/api/dashboard/closed/',
            null,
            [],
            'GET',
            true
        );

        self::recursiveRecord($closedTransactions1['data']['contact_list'], 1);

        if ($genesis === true) {
            while (isset($closedTransactions1['pagination']['next'])) {
                $parts  = parse_url($closedTransactions1['pagination']['next']);
                $params = $parts['query'];

                parse_str($params, $params);

                $genesisTimestamp = new Carbon('2019-03-01 00:00:00+00:00');
                $genesisTimestamp = $genesisTimestamp->timestamp;
                $nextTimestamp    = new Carbon($params['start_at']);
                $nextTimestamp    = $nextTimestamp->timestamp;

                if ($nextTimestamp < $genesisTimestamp) {
                    break;
                }

                $closedTransactions1 = self::getLocalBtc(
                    1,
                    '/api/dashboard/closed/',
                    $params,
                    [],
                    'GET',
                    true
                );

                self::recursiveRecord($closedTransactions1['data']['contact_list'], 1);
            }
        }

        //Account 2
        $closedTransactions2 = self::getLocalBtc(
            2,
            '/api/dashboard/closed/',
            null,
            [],
            'GET',
            true
        );

        self::recursiveRecord($closedTransactions2['data']['contact_list'], 2);

        if ($genesis === true) {
            while (isset($closedTransactions2['pagination']['next'])) {
                $parts  = parse_url($closedTransactions2['pagination']['next']);
                $params = $parts['query'];

                parse_str($params, $params);

                $genesisTimestamp = new Carbon('2019-03-01 00:00:00+00:00');
                $genesisTimestamp = $genesisTimestamp->timestamp;
                $nextTimestamp    = new Carbon($params['start_at']);
                $nextTimestamp    = $nextTimestamp->timestamp;

                if ($nextTimestamp < $genesisTimestamp) {
                    break;
                }

                $closedTransactions2 = self::getLocalBtc(
                    2,
                    '/api/dashboard/closed/',
                    $params,
                    [],
                    'GET',
                    true
                );

                self::recursiveRecord($closedTransactions2['data']['contact_list'], 2);
            }
        }

        //Account 3
        $closedTransactions3 = self::getLocalBtc(
            3,
            '/api/dashboard/closed/',
            null,
            [],
            'GET',
            true
        );

//        $beforeCounter = 0;
        //From 01-may Just this one
        $closedTransactions3ToClean = $closedTransactions3['data']['contact_list'];
        foreach ($closedTransactions3ToClean as $key => $transaction) {
            if (strtotime($transaction['data']['closed_at']) < 1559347200) {
                unset($closedTransactions3ToClean[$key]);
            }
        }

        self::recursiveRecord($closedTransactions3ToClean, 3);

//        while ($beforeCounter < 7 && isset($closedTransactions3['pagination']['next'])) {
//            $parts  = parse_url($closedTransactions3['pagination']['next']);
//            $params = $parts['query'];
//
//            parse_str($params, $params);
//
//            $closedTransactions3 = self::getLocalBtc(
//                3,
//                '/api/dashboard/closed/',
//                $params,
//                [],
//                'GET',
//                true
//            );
//
//            self::recursiveRecord($closedTransactions3['data']['contact_list'], 3);
//            $beforeCounter++;
//        }

        /**
         * Wallets
         */
        //Account 1
        $walletData1 = self::getLocalBtc(1, '/api/wallet/');
        /**
         * Lovely control - Love <3
         *
         * $control        = [];
         * $percentPre     = 0;
         * $percentNext    = 0;
         * $totalExternals = 0;
         * $i              = 0;
         *
         * foreach ($walletData2['sent_transactions_30d'] as $key => $sendT) {
         * if ($sendT['tx_type'] === 1 && $sendT['txid'] !== null) {
         * $totalExternals++;
         * $previous = $walletData2['sent_transactions_30d'][$key - 1] ?? null;
         * $next     = $walletData2['sent_transactions_30d'][$key + 1] ?? null;
         *
         * if ($previous && $previous['description'] !== 'fee') {
         * $previous = null;
         * } else {
         * $percentPre++;
         * $control[$i][] = $previous;
         * }
         *
         * $control[$i][] = $sendT;
         *
         * if ($next['description'] !== 'fee') {
         * $next = null;
         * } else {
         * $percentNext++;
         * $control[$i][] = $next;
         * }
         *
         * if ($previous && $next) {
         * $interval             = [];
         * $interval['previous'] = abs(strtotime($sendT['created_at']) - strtotime($previous['created_at']));
         * $interval['next']     = abs(strtotime($sendT['created_at']) - strtotime($next['created_at']));
         * asort($interval);
         * //reset($interval);
         * $closest            = key($interval);
         * $control[$i]['fee'] = ${$closest};
         * }
         *
         * if($previous && !$next) {
         * $control[$i]['fee'] = $previous;
         * }
         *
         * if(!$previous && $next) {
         * $control[$i]['fee'] = $next;
         * }
         *
         * $i++;
         * }
         * }
         *
         * dd($totalExternals, $percentPre, $percentNext, $control, $walletData2['sent_transactions_30d']);
         * die;
         **/

        foreach ($walletData1['received_transactions_30d'] as $sendT) {
            $fingerprint           = hash('md4', $sendT['amount'] . $sendT['created_at']);
            $walletTransactionToDb = WalletTransactionsReceivedCache::where([
                    'fingerprint' => $fingerprint
                ])->first() ?? new WalletTransactionsReceivedCache;

            $anchorDateEST     = new Carbon($sendT['created_at']);
            $anchorDateEST     = $anchorDateEST
                ->setTimezone('EST')
                ->format('Y-m-d H:i:s');
            $localFormatedDate = new Carbon($sendT['created_at']);
            $localFormatedDate = $localFormatedDate
                ->format('Y-m-d H:i:s');

            $regEx = '/#(\d+)/';
            preg_match($regEx, $sendT['description'], $matches);
            $contactID = $matches[1] ?? null;

            $walletTransactionToDb->fingerprint  = $fingerprint;
            $walletTransactionToDb->contact_id   = $contactID;
            $walletTransactionToDb->txid         = $sendT['txid'];
            $walletTransactionToDb->account_name = 'cristinadlr';
            $walletTransactionToDb->json_data    = $sendT;

            if (!isset($walletTransactionToDb->status) || $walletTransactionToDb->status === 0) {
                $walletTransactionToDb->status = 0;
            }

            $walletTransactionToDb->anchor_date_localbtc = $localFormatedDate;
            $walletTransactionToDb->anchor_date_est      = $anchorDateEST;
            $walletTransactionToDb->save();
        }

        foreach ($walletData1['sent_transactions_30d'] as $key => $sendT) {
            $fingerprint           = hash('md4', $sendT['amount'] . $sendT['created_at']);
            $walletTransactionToDb = WalletTransactionsSentCache::where([
                    'fingerprint' => $fingerprint
                ])->first() ?? new WalletTransactionsSentCache;

            $anchorDateEST     = new Carbon($sendT['created_at']);
            $anchorDateEST     = $anchorDateEST
                ->setTimezone('EST')
                ->format('Y-m-d H:i:s');
            $localFormatedDate = new Carbon($sendT['created_at']);
            $localFormatedDate = $localFormatedDate
                ->format('Y-m-d H:i:s');

            $regEx = '/#(\d+)/';
            preg_match($regEx, $sendT['description'], $matches);
            $contactID = $matches[1] ?? null;

            if ($sendT['tx_type'] === 1 && $sendT['txid'] !== null) {
                $previous = $walletData1['sent_transactions_30d'][$key - 1] ?? null;
                $next     = $walletData1['sent_transactions_30d'][$key + 1] ?? null;

                if ($previous && $previous['description'] !== 'fee') {
                    $previous = null;
                }

                if ($next['description'] !== 'fee') {
                    $next = null;
                }

                if ($previous && $next) {
                    $interval             = [];
                    $interval['previous'] = abs(strtotime($sendT['created_at']) - strtotime($previous['created_at']));
                    $interval['next']     = abs(strtotime($sendT['created_at']) - strtotime($next['created_at']));
                    asort($interval);
                    $closest                    = key($interval);
                    $walletTransactionToDb->fee = ${$closest}['amount'];
                }

                if ($previous && !$next) {
                    $walletTransactionToDb->fee = $previous['amount'];
                }

                if (!$previous && $next) {
                    $walletTransactionToDb->fee = $next['amount'];
                }
            }

            $walletTransactionToDb->fingerprint  = $fingerprint;
            $walletTransactionToDb->contact_id   = $contactID;
            $walletTransactionToDb->txid         = $sendT['txid'];
            $walletTransactionToDb->account_name = 'cristinadlr';
            $walletTransactionToDb->json_data    = $sendT;

            if (!isset($walletTransactionToDb->status) || $walletTransactionToDb->status === 0) {
                $walletTransactionToDb->status = 0;
            }

            $walletTransactionToDb->anchor_date_localbtc = $localFormatedDate;
            $walletTransactionToDb->anchor_date_est      = $anchorDateEST;
            $walletTransactionToDb->save();
        }

        //Account 2

        $walletData2 = self::getLocalBtc(2, '/api/wallet/');

        foreach ($walletData2['received_transactions_30d'] as $sendT) {
            $fingerprint           = hash('md4', $sendT['amount'] . $sendT['created_at']);
            $walletTransactionToDb = WalletTransactionsReceivedCache::where([
                    'fingerprint' => $fingerprint
                ])->first() ?? new WalletTransactionsReceivedCache;

            $anchorDateEST     = new Carbon($sendT['created_at']);
            $anchorDateEST     = $anchorDateEST
                ->setTimezone('EST')
                ->format('Y-m-d H:i:s');
            $localFormatedDate = new Carbon($sendT['created_at']);
            $localFormatedDate = $localFormatedDate
                ->format('Y-m-d H:i:s');

            $regEx = '/#(\d+)/';
            preg_match($regEx, $sendT['description'], $matches);
            $contactID = $matches[1] ?? null;

            $walletTransactionToDb->fingerprint  = $fingerprint;
            $walletTransactionToDb->contact_id   = $contactID;
            $walletTransactionToDb->txid         = $sendT['txid'];
            $walletTransactionToDb->account_name = 'gdf12018';
            $walletTransactionToDb->json_data    = $sendT;

            if (!isset($walletTransactionToDb->status) || $walletTransactionToDb->status === 0) {
                $walletTransactionToDb->status = 0;
            }

            $walletTransactionToDb->anchor_date_localbtc = $localFormatedDate;
            $walletTransactionToDb->anchor_date_est      = $anchorDateEST;
            $walletTransactionToDb->save();
        }

        foreach ($walletData2['sent_transactions_30d'] as $key => $sendT) {
            $fingerprint           = hash('md4', $sendT['amount'] . $sendT['created_at']);
            $walletTransactionToDb = WalletTransactionsSentCache::where([
                    'fingerprint' => $fingerprint
                ])->first() ?? new WalletTransactionsSentCache;

            $anchorDateEST     = new Carbon($sendT['created_at']);
            $anchorDateEST     = $anchorDateEST
                ->setTimezone('EST')
                ->format('Y-m-d H:i:s');
            $localFormatedDate = new Carbon($sendT['created_at']);
            $localFormatedDate = $localFormatedDate
                ->format('Y-m-d H:i:s');

            $regEx = '/#(\d+)/';
            preg_match($regEx, $sendT['description'], $matches);
            $contactID = $matches[1] ?? null;

            if ($sendT['tx_type'] === 1 && $sendT['txid'] !== null) {
                $previous = $walletData2['sent_transactions_30d'][$key - 1] ?? null;
                $next     = $walletData2['sent_transactions_30d'][$key + 1] ?? null;

                if ($previous && $previous['description'] !== 'fee') {
                    $previous = null;
                }

                if ($next['description'] !== 'fee') {
                    $next = null;
                }

                if ($previous && $next) {
                    $interval             = [];
                    $interval['previous'] = abs(strtotime($sendT['created_at']) - strtotime($previous['created_at']));
                    $interval['next']     = abs(strtotime($sendT['created_at']) - strtotime($next['created_at']));
                    asort($interval);
                    $closest                    = key($interval);
                    $walletTransactionToDb->fee = ${$closest}['amount'];
                }

                if ($previous && !$next) {
                    $walletTransactionToDb->fee = $previous['amount'];
                }

                if (!$previous && $next) {
                    $walletTransactionToDb->fee = $next['amount'];
                }
            }

            $walletTransactionToDb->fingerprint  = $fingerprint;
            $walletTransactionToDb->contact_id   = $contactID;
            $walletTransactionToDb->txid         = $sendT['txid'];
            $walletTransactionToDb->account_name = 'gdf12018';
            $walletTransactionToDb->json_data    = $sendT;

            if (!isset($walletTransactionToDb->status) || $walletTransactionToDb->status === 0) {
                $walletTransactionToDb->status = 0;
            }

            $walletTransactionToDb->anchor_date_localbtc = $localFormatedDate;
            $walletTransactionToDb->anchor_date_est      = $anchorDateEST;
            $walletTransactionToDb->save();
        }

        //Account 3

        $walletData3 = self::getLocalBtc(3, '/api/wallet/');

        foreach ($walletData3['received_transactions_30d'] as $sendT) {
            if (strtotime($sendT['created_at']) > 1559347200) {
                $fingerprint           = hash('md4', $sendT['amount'] . $sendT['created_at']);
                $walletTransactionToDb = WalletTransactionsReceivedCache::where([
                        'fingerprint' => $fingerprint
                    ])->first() ?? new WalletTransactionsReceivedCache;

                $anchorDateEST     = new Carbon($sendT['created_at']);
                $anchorDateEST     = $anchorDateEST
                    ->setTimezone('EST')
                    ->format('Y-m-d H:i:s');
                $localFormatedDate = new Carbon($sendT['created_at']);
                $localFormatedDate = $localFormatedDate
                    ->format('Y-m-d H:i:s');

                $regEx = '/#(\d+)/';
                preg_match($regEx, $sendT['description'], $matches);
                $contactID = $matches[1] ?? null;

                $walletTransactionToDb->fingerprint  = $fingerprint;
                $walletTransactionToDb->contact_id   = $contactID;
                $walletTransactionToDb->txid         = $sendT['txid'];
                $walletTransactionToDb->account_name = 'AKB01';
                $walletTransactionToDb->json_data    = $sendT;

                if (!isset($walletTransactionToDb->status) || $walletTransactionToDb->status === 0) {
                    $walletTransactionToDb->status = 0;
                }

                $walletTransactionToDb->anchor_date_localbtc = $localFormatedDate;
                $walletTransactionToDb->anchor_date_est      = $anchorDateEST;
                $walletTransactionToDb->save();
            }
        }

        foreach ($walletData3['sent_transactions_30d'] as $key => $sendT) {
            if (strtotime($sendT['created_at']) > 1559347200) {
                $fingerprint           = hash('md4', $sendT['amount'] . $sendT['created_at']);
                $walletTransactionToDb = WalletTransactionsSentCache::where([
                        'fingerprint' => $fingerprint
                    ])->first() ?? new WalletTransactionsSentCache;

                $anchorDateEST     = new Carbon($sendT['created_at']);
                $anchorDateEST     = $anchorDateEST
                    ->setTimezone('EST')
                    ->format('Y-m-d H:i:s');
                $localFormatedDate = new Carbon($sendT['created_at']);
                $localFormatedDate = $localFormatedDate
                    ->format('Y-m-d H:i:s');

                $regEx = '/#(\d+)/';
                preg_match($regEx, $sendT['description'], $matches);
                $contactID = $matches[1] ?? null;

                if ($sendT['tx_type'] === 1 && $sendT['txid'] !== null) {
                    $previous = $walletData3['sent_transactions_30d'][$key - 1] ?? null;
                    $next     = $walletData3['sent_transactions_30d'][$key + 1] ?? null;

                    if ($previous && $previous['description'] !== 'fee') {
                        $previous = null;
                    }

                    if ($next['description'] !== 'fee') {
                        $next = null;
                    }

                    if ($previous && $next) {
                        $interval             = [];
                        $interval['previous'] = abs(strtotime($sendT['created_at']) - strtotime($previous['created_at']));
                        $interval['next']     = abs(strtotime($sendT['created_at']) - strtotime($next['created_at']));
                        asort($interval);
                        $closest                    = key($interval);
                        $walletTransactionToDb->fee = ${$closest}['amount'];
                    }

                    if ($previous && !$next) {
                        $walletTransactionToDb->fee = $previous['amount'];
                    }

                    if (!$previous && $next) {
                        $walletTransactionToDb->fee = $next['amount'];
                    }
                }

                $walletTransactionToDb->fingerprint  = $fingerprint;
                $walletTransactionToDb->contact_id   = $contactID;
                $walletTransactionToDb->txid         = $sendT['txid'];
                $walletTransactionToDb->account_name = 'AKB01';
                $walletTransactionToDb->json_data    = $sendT;

                if (!isset($walletTransactionToDb->status) || $walletTransactionToDb->status === 0) {
                    $walletTransactionToDb->status = 0;
                }

                $walletTransactionToDb->anchor_date_localbtc = $localFormatedDate;
                $walletTransactionToDb->anchor_date_est      = $anchorDateEST;
                $walletTransactionToDb->save();
            }
        }

        self::recordIncomings();
        self::checkHolds();

        return self::recordOutgoingBtcCache();
    }

    /**
     * Record incoming BTC transactions from released contacts
     * @return bool
     */
    public static function recordIncomings(): bool
    {
        /**
         * First scenario
         * Recording Incoming from Released Contacts
         */
        $releasedPurchasesList = ReleasedContactCache::where(
            [
                'status'    => 0,
                'is_buying' => true
            ]
        )
            ->orderBy('anchor_date_localbtc', 'asc')
            ->get();

        $releasedPurchasesList = $releasedPurchasesList->groupBy('account_name');

        foreach ($releasedPurchasesList as $accountName => $purchases) {
            $lastIncomingBtcTransaction = IncomingBtc::where(
                [
                    'account_name' => $accountName,
                    'wallet_only'  => false
                ]
            )
                ->orderBy('localbtc_released_date', 'desc')
                ->first();
            $lastIncomingBtcDate        = strtotime('2019-03-01T05:00:00+00:00');

            if ($lastIncomingBtcTransaction !== null) {
                $lastIncomingBtcDate = strtotime($lastIncomingBtcTransaction->localbtc_released_date);
            }

            foreach ($purchases as $purchase) {
                if ($lastIncomingBtcDate < strtotime($purchase['released_localbtc'])) {
                    $transactionReleasedDate = new Carbon($purchase['released_localbtc']);
                    $localbtcReleasedDate    = $transactionReleasedDate->format('Y-m-d H:i:s');
                    $releasedDate            = $transactionReleasedDate
                        ->setTimezone('EST')
                        ->format('Y-m-d H:i:s');
                    $msg                     = 'Contact ID: ' . $purchase['contact_id'] . '. LocalBitcoins Operation.';

                    $amountBTC = $purchase['json_data']['amount_btc'];

                    if ($purchase['json_data']['advertisement']['advertiser']['username'] === $accountName) {
                        $amountBTC = $purchase['json_data']['amount_btc'] - $purchase['json_data']['fee_btc'];
                    }

                    $newTransaction = [
                        'bank_name'              => '',
                        'transaction_id'         => $purchase['contact_id'],
                        'amount'                 => $purchase['json_data']['amount'],
                        'amount_btc'             => $amountBTC,
                        'msg'                    => $msg,
                        'currency'               => $purchase['json_data']['currency'],
                        'json_data'              => $purchase['json_data'],
                        'released_date'          => $releasedDate,
                        'localbtc_released_date' => $localbtcReleasedDate,
                        'type'                   => 'Outgoing',
                        'account_name'           => $accountName
                    ];

                    $newTransaction['usd_price'] = null;
                    /*if ($purchase['json_data']['currency'] === 'VES') {
                        $fundedDate                  = new Carbon($purchase['json_data']['funded_at']);
                        $fundedDate                  = $fundedDate->format('Y-m-d H:i:s');
                        $bitstamp                    = BitstampData::where(
                            'created_at',
                            '>=',
                            $fundedDate
                        )->first();
                        $newTransaction['usd_price'] = $bitstamp['price'] * $amountBTC;
                    } else*/
                    if ($purchase['json_data']['currency'] === 'USD' ||
                        $purchase['json_data']['currency'] === 'PAB') {
                        $newTransaction['usd_price'] = $purchase['json_data']['amount'];
                    } else {
                        $newTransaction['error'] = 1;
                    }

                    $newTransaction = Transaction::create($newTransaction);
                    IncomingBtc::create([
                        'transaction_id'         => $newTransaction->id,
                        'amount_btc'             => $amountBTC,
                        'usd_price'              => $newTransaction->usd_price,
                        'released_date'          => $releasedDate,
                        'localbtc_released_date' => $localbtcReleasedDate,
                        'remaining'              => $amountBTC,
                        'account_name'           => $accountName,
                        'hold'                   => 0,
                        'hold_spend'             => null,
                        'hold_by'                => null
                    ]);

                    $purchase->status = 1;
                    $purchase->save();
                }
            }
        }

        /**
         * Second scenario
         * Recording Incoming from Wallet transactions
         */
        $releasedPurchasesList = WalletTransactionsReceivedCache::where(
            [
                'status'     => 0,
                'contact_id' => null,
                'txid'       => null
            ]
        )
            ->orderBy('anchor_date_localbtc', 'asc')
            ->get();

        $releasedPurchasesList = $releasedPurchasesList->groupBy('account_name');

        foreach ($releasedPurchasesList as $accountName => $purchases) {
            $lastIncomingBtcTransaction = IncomingBtc::where(
                [
                    'account_name' => $accountName,
                    'wallet_only'  => true
                ]
            )
                ->orderBy('localbtc_released_date', 'desc')
                ->first();
            $lastIncomingBtcDate        = strtotime('2019-03-01T05:00:00+00:00');

            if ($lastIncomingBtcTransaction !== null) {
                $lastIncomingBtcDate = strtotime($lastIncomingBtcTransaction->localbtc_released_date);
            }


            foreach ($purchases as $purchase) {
                if ($lastIncomingBtcDate < strtotime($purchase['json_data']['created_at'])) {
                    $transactionReleasedDate = new Carbon($purchase['json_data']['created_at']);
                    $localbtcReleasedDate    = $transactionReleasedDate->format('Y-m-d H:i:s');
                    $releasedDate            = $transactionReleasedDate
                        ->setTimezone('EST')
                        ->format('Y-m-d H:i:s');

                    $newTransaction = [
                        'bank_name'              => '',
                        'transaction_id'         => '',
                        'amount'                 => 0,
                        'amount_btc'             => $purchase['json_data']['amount'],
                        'msg'                    => 'Only Wallet Transaction. Needs more info',
                        'currency'               => '',
                        'json_data'              => [],
                        'released_date'          => $releasedDate,
                        'localbtc_released_date' => $localbtcReleasedDate,
                        'type'                   => 'Outgoing',
                        'account_name'           => $accountName,
                        'is_manual'              => 1
                    ];

                    $newTransaction['usd_price'] = null;
                    $newTransaction['error']     = 1;
                    $newTransaction              = Transaction::create($newTransaction);

                    IncomingBtc::create([
                        'transaction_id'         => $newTransaction->id,
                        'amount_btc'             => $purchase['json_data']['amount'],
                        'usd_price'              => $newTransaction->usd_price,
                        'released_date'          => $releasedDate,
                        'localbtc_released_date' => $localbtcReleasedDate,
                        'remaining'              => $purchase['json_data']['amount'],
                        'account_name'           => $accountName,
                        'hold'                   => 0,
                        'hold_spend'             => null,
                        'hold_by'                => null,
                        'wallet_only'            => true
                    ]);
                }

                $purchase->status = 1;
                $purchase->save();
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function checkHolds(): bool
    {
        //Account 1
        $activeContacts = self::getLocalBtc(
            1,
            '/api/dashboard/',
            null,
            [],
            'GET',
            true
        );

        foreach ($activeContacts['data']['contact_list'] as $activeContact) {
            if ($activeContact['data']['is_selling'] === true) {
                //1st Step - Registration in DB
                $activeContactCache                         = new ActiveContactCache();
                $activeContactCache['contact_id']           = $activeContact['data']['contact_id'];
                $activeContactCache['account_name']         = 'cristinadlr';
                $activeContactCache['json_data']            = $activeContact['data'];
                $activeContactCache['status']               = 0;
                $transactionReleasedDate                    = new Carbon($activeContact['data']['funded_at']);
                $localbtcFundedDate                         = $transactionReleasedDate->format('Y-m-d H:i:s');
                $fundedDate                                 = $transactionReleasedDate
                    ->setTimezone('EST')
                    ->format('Y-m-d H:i:s');
                $activeContactCache['anchor_date_localbtc'] = $localbtcFundedDate;
                $activeContactCache['anchor_date_est']      = $fundedDate;

                //Condition for Process or not the fee.
                if ($activeContact['data']['advertisement']['advertiser']['username'] === 'cristinadlr') {
                    $activeContactCache['process_fee'] = true;
                } else {
                    $activeContactCache['process_fee'] = false;
                }

                try {
                    $activeContactCache->save();
                } catch (QueryException $e) {
                    continue;
                }

            }
        }

        $activeContacts = ActiveContactCache::orderBy('anchor_date_localbtc', 'asc')
            ->where([
                'account_name' => 'cristinadlr'
            ])
            ->get();

        //2nd Step. Hold or Un-hold incoming.
        foreach ($activeContacts as $activeContactCache) {
            //To un-hold
            $canceledContactAnchor = canceledContactCache::where(['contact_id' => $activeContactCache->contact_id])
                ->first();
            $releasedContactAnchor = ReleasedContactCache::where(['contact_id' => $activeContactCache->contact_id])
                ->first();

            if ($canceledContactAnchor || $releasedContactAnchor) {
                if ($activeContactCache->status === 1) {
                    self::unHoldIncomingBtc($activeContactCache->contact_id);
                }
                $activeContactCache->delete();
            }
        }

        //Account 2
        $activeContacts = self::getLocalBtc(
            2,
            '/api/dashboard/',
            null,
            [],
            'GET',
            true
        );

        foreach ($activeContacts['data']['contact_list'] as $activeContact) {
            if ($activeContact['data']['is_selling'] === true) {
                //1st Step - Registration in DB
                $activeContactCache                         = new ActiveContactCache();
                $activeContactCache['contact_id']           = $activeContact['data']['contact_id'];
                $activeContactCache['account_name']         = 'gdf12018';
                $activeContactCache['json_data']            = $activeContact['data'];
                $activeContactCache['status']               = 0;
                $transactionReleasedDate                    = new Carbon($activeContact['data']['funded_at']);
                $localbtcFundedDate                         = $transactionReleasedDate->format('Y-m-d H:i:s');
                $fundedDate                                 = $transactionReleasedDate
                    ->setTimezone('EST')
                    ->format('Y-m-d H:i:s');
                $activeContactCache['anchor_date_localbtc'] = $localbtcFundedDate;
                $activeContactCache['anchor_date_est']      = $fundedDate;

                //Condition for Process or not the fee.
                if ($activeContact['data']['advertisement']['advertiser']['username'] === 'gdf12018') {
                    $activeContactCache['process_fee'] = true;
                } else {
                    $activeContactCache['process_fee'] = false;
                }

                try {
                    $activeContactCache->save();
                } catch (QueryException $e) {
                    continue;
                }

            }
        }

        $activeContacts = ActiveContactCache::orderBy('anchor_date_localbtc', 'asc')
            ->where([
                'account_name' => 'gdf12018'
            ])
            ->get();

        //2nd Step. Hold or Un-hold incoming.
        foreach ($activeContacts as $activeContactCache) {
            //To un-hold
            $canceledContactAnchor = canceledContactCache::where(['contact_id' => $activeContactCache->contact_id])
                ->first();
            $releasedContactAnchor = ReleasedContactCache::where(['contact_id' => $activeContactCache->contact_id])
                ->first();

            if ($canceledContactAnchor || $releasedContactAnchor) {
                if ($activeContactCache->status === 1) {
                    self::unHoldIncomingBtc($activeContactCache->contact_id);
                }
                $activeContactCache->delete();
            }
        }

        //Account 3
        $activeContacts = self::getLocalBtc(
            3,
            '/api/dashboard/',
            null,
            [],
            'GET',
            true
        );

        foreach ($activeContacts['data']['contact_list'] as $activeContact) {
            if ($activeContact['data']['is_selling'] === true) {
                //1st Step - Registration in DB
                $activeContactCache                         = new ActiveContactCache();
                $activeContactCache['contact_id']           = $activeContact['data']['contact_id'];
                $activeContactCache['account_name']         = 'AKB01';
                $activeContactCache['json_data']            = $activeContact['data'];
                $activeContactCache['status']               = 0;
                $transactionReleasedDate                    = new Carbon($activeContact['data']['funded_at']);
                $localbtcFundedDate                         = $transactionReleasedDate->format('Y-m-d H:i:s');
                $fundedDate                                 = $transactionReleasedDate
                    ->setTimezone('EST')
                    ->format('Y-m-d H:i:s');
                $activeContactCache['anchor_date_localbtc'] = $localbtcFundedDate;
                $activeContactCache['anchor_date_est']      = $fundedDate;

                //Condition for Process or not the fee.
                if ($activeContact['data']['advertisement']['advertiser']['username'] === 'AKB01') {
                    $activeContactCache['process_fee'] = true;
                } else {
                    $activeContactCache['process_fee'] = false;
                }

                try {
                    $activeContactCache->save();
                } catch (QueryException $e) {
                    continue;
                }

            }
        }

        $activeContacts = ActiveContactCache::orderBy('anchor_date_localbtc', 'asc')
            ->where([
                'account_name' => 'AKB01'
            ])
            ->get();

        //2nd Step. Hold or Un-hold incoming.
        foreach ($activeContacts as $activeContactCache) {
            //To un-hold
            $canceledContactAnchor = canceledContactCache::where(['contact_id' => $activeContactCache->contact_id])
                ->first();
            $releasedContactAnchor = ReleasedContactCache::where(['contact_id' => $activeContactCache->contact_id])
                ->first();

            if ($canceledContactAnchor || $releasedContactAnchor) {
                if ($activeContactCache->status === 1) {
                    self::unHoldIncomingBtc($activeContactCache->contact_id);
                }
                $activeContactCache->delete();
            }
        }

        return true;
    }

    /**
     * Remove hold from Incoming BTC.
     *
     * @param int $contactID
     *
     * @return bool
     */
    private static function unHoldIncomingBtc(int $contactID): bool
    {
        $incomingsToReserve = IncomingBtc::whereRaw("hold_spend like '%" . $contactID . "%'")
            ->get();

        foreach ($incomingsToReserve as $incomingBTC) {
            $holdBy    = $incomingBTC['hold_by'];
            $holdSpend = $incomingBTC['hold_spend'];

            unset(
                $holdBy[array_search($contactID, $holdBy)],
                $holdSpend[$contactID]
            );

            $holdBy                    = empty($holdBy) ? null : $holdBy;
            $holdSpend                 = empty($holdSpend) ? null : $holdSpend;
            $incomingBTC['remaining']  += $incomingBTC['hold_spend'][$contactID];
            $incomingBTC['was_used']   = 0;
            $incomingBTC['hold']       = empty($holdBy) ? 0 : 1;
            $incomingBTC['hold_spend'] = $holdSpend;
            $incomingBTC['hold_by']    = $holdBy;
            $incomingBTC->save();
        }

        return true;
    }

    /**
     * @param $accountName
     * @param $activeContact
     *
     * @return bool|null
     */
    public static function holdingIncomingsBtc($accountName, $activeContact): ?bool
    {
        $btcAmount = $activeContact['json_data']['amount_btc'];

        if ($activeContact['process_fee'] === 1) {
            $btcAmount += $activeContact['json_data']['fee_btc'];
        }

        $contactID    = $activeContact['contact_id'];
        $btcPurchases = IncomingBtc::where(
            [
                'was_used'     => 0,
                'account_name' => $accountName
            ]
        )
            ->where('remaining', '>', 0)
            ->orderBy('localbtc_released_date', 'ASC')
            ->get();

        /**
         * Se está recorriendo entre todas tus compras de BTC
         *
         * Nos traemos de la DB las prociones no usadas
         */
        $purchase = $btcPurchases[0];
        //Si a esta compra le queda más de 0
        //$btcAmount = BTC totales (liberados + fee)
        if ((string)$purchase->remaining >= (string)$btcAmount) {
            //$saleRate               = Precio BTC de venta
            //$purchaseRate           = Rate de la compra
            //$sale['amount_btc']     = Porción de BTC liberado
            //$sale['fee_btc']        = Local Fee

            $purchase->hold        = 1;
            $holdSpend             = $purchase->hold_spend;
            $holdSpend[$contactID] = $btcAmount;
            $purchase->hold_spend  = $holdSpend;
            $holdBy                = $purchase->hold_by;
            $holdBy[]              = $contactID;
            $purchase->hold_by     = $holdBy;

            $purchase->remaining -= $btcAmount;
            $purchase->save();

            $activeContact->status = 1;
            unset($activeContact['localbtc_released_date']);
            $activeContact->save();

            return true;
        }

        //Cuando la porcion no puede pagar la venta total

        /**
         * $sale es una array que lleva los BTC liberados y el Fee
         * $purchase: Rate de compra, Remaining
         */

        self::dynamicHoldingIncomingsBtc($btcAmount, $purchase, 0, $btcPurchases, $contactID);
        $activeContact->status = 1;
        unset($activeContact['localbtc_released_date']);
        $activeContact->save();

        return true;
    }

    /**
     * @param $btcAmount
     * @param $purchase
     * @param $key
     * @param $btcPurchases
     *
     * @param $holderID
     *
     * @param $contactID
     *
     * @return bool|null
     */
    private static function dynamicHoldingIncomingsBtc(
        $btcAmount,
        $purchase,
        $key,
        $btcPurchases,
        $contactID
    ): ?bool {
        $nLoan = $btcAmount - $purchase->remaining;
        $nLoan = round($nLoan, 8);
        unset($btcPurchases[$key]);

        $purchase->hold        = 1;
        $holdSpend             = $purchase->hold_spend;
        $holdSpend[$contactID] = $purchase->remaining;
        $purchase->hold_spend  = $holdSpend;
        $holdBy                = $purchase->hold_by;
        $holdBy[]              = $contactID;
        $purchase->hold_by     = $holdBy;

        $purchase->remaining = 0;
        $purchase->save();

        foreach ($btcPurchases as $nPurchase) {
            if ((string)$nPurchase->remaining >= (string)$nLoan) {
                $nPurchase->hold       = 1;
                $holdSpend             = $nPurchase->hold_spend;
                $holdSpend[$contactID] = $nLoan;
                $nPurchase->hold_spend = $holdSpend;
                $holdBy                = $nPurchase->hold_by;
                $holdBy[]              = $contactID;
                $nPurchase->hold_by    = $holdBy;

                $nPurchase->remaining -= $nLoan;
                $nPurchase->save();

                return true;
            }

            if ($nPurchase->remaining > 0) {
                $nLoan -= $nPurchase->remaining;

                $nPurchase->hold       = 1;
                $holdSpend             = $nPurchase->hold_spend;
                $holdSpend[$contactID] = $nPurchase->remaining;
                $nPurchase->hold_spend = $holdSpend;
                $holdBy                = $nPurchase->hold_by;
                $holdBy[]              = $contactID;
                $nPurchase->hold_by    = $holdBy;

                $nPurchase->remaining = 0;
                $nPurchase->save();
            }
        }

        return true;
    }

    /**
     * Calculate fragments for a Sale.
     *
     * @param $btcPurchases
     * @param $btcAmount
     * @param $account
     *
     * @return array|bool
     */
    public static function calculateSellingBtc(
        $btcPurchases,
        $btcAmount
    ) {
        $nLoan          = $btcAmount;
        $nLoan          = round($nLoan, 8);
        $fragmentsToUSe = [];

        foreach ($btcPurchases as $nPurchase) {
            if ((string)$nPurchase->remaining >= (string)$nLoan) {

                $fragmentsToUSe[] = $nPurchase;

                return $fragmentsToUSe;
            }

            if ($nPurchase->remaining > 0) {
                $nLoan -= $nPurchase->remaining;

                $fragmentsToUSe[] = $nPurchase;
            }
        }

        return true;
    }

    /**
     * Record outgoing BTC and calculate profit
     */
    public static function recordOutgoingBtcCache()
    {
        $walletOnlyOutgoings = WalletTransactionsSentCache::orderBy('anchor_date_localbtc', 'asc')
            ->whereRaw('status = 0 and ((contact_id is null and txid is null and json_data like \'%tx_type": 3%\'
             and json_data not like \'%fee%\')  or (contact_id is null and txid is not null and fee is not null))')
            ->get();

        $walletOnlyOutgoings = $walletOnlyOutgoings->groupBy('account_name');

        $releasedSalesList = ReleasedContactCache::where(
            [
                'status'     => 0,
                'is_selling' => true
            ]
        )
            ->orderBy('anchor_date_localbtc', 'asc')
            ->get();

        $releasedSalesList = $releasedSalesList->groupBy('account_name');
        $completeList      = ['cristinadlr' => [], 'gdf12018' => [], 'AKB01' => []];
        foreach ($releasedSalesList as $accountName => $outgoing) {
            //$completeList[$accountName] = [];
            foreach ($outgoing as $outgoingBtcTransaction) {
                $completeList[$accountName][] = $outgoingBtcTransaction;
            }
        }

        foreach ($walletOnlyOutgoings as $accountName => $outgoing) {
//            if (!isset($completeList[$accountName])) {
//                $completeList[$accountName] = [];
//            }
            foreach ($outgoing as $outgoingBtcTransaction) {
                $completeList[$accountName][] = $outgoingBtcTransaction;
            }
        }

        foreach ($completeList as $accountName => $outgoing) {
            $lastOutgoingBtcTransaction = OutgoingBtcCache::where('account_name', $accountName)
                ->orderBy('localbtc_released_date', 'desc')
                ->first();

            $lastOutgoingBtcDate = strtotime('2019-03-01T05:00:00+00:00');

            if ($lastOutgoingBtcTransaction !== null) {
                $lastOutgoingBtcDate = strtotime($lastOutgoingBtcTransaction->localbtc_released_date);
            }

            //localbtc_released_date now use funded date from API data

            foreach ($outgoing as $outgoingBtcTransaction) {
                $className = get_class($outgoingBtcTransaction);

                if ($className === 'App\ReleasedContactCache') {

                    $walletTransaction = WalletTransactionsSentCache::where(['contact_id' => $outgoingBtcTransaction['contact_id']])
                        ->first();

                    OutgoingBtcCache::create([
                        'contact_id'             => $outgoingBtcTransaction['contact_id'],
                        'account_name'           => $accountName,
                        'contactInfo'            => $outgoingBtcTransaction['json_data'],
                        'walletTransaction'      => $walletTransaction['json_data'],
                        'localbtc_released_date' => $outgoingBtcTransaction['funded_localbtc'],
                        'md5'                    => '',
                        'status'                 => false
                    ]);

                    $outgoingBtcTransaction->status = 1;
                    $outgoingBtcTransaction->save();
                }

                if ($className === 'App\WalletTransactionsSentCache' &&
                    $lastOutgoingBtcDate < strtotime($outgoingBtcTransaction['anchor_date_localbtc'])) {
                    $walletTransaction                 = $outgoingBtcTransaction['json_data'];
                    $walletTransaction['fee']          = $outgoingBtcTransaction['fee'] ?? 0;
                    $walletTransaction['type']         = 'noTrade';
                    $walletTransaction['account_name'] = $accountName;

                    OutgoingBtcCache::create([
                        'contact_id'             => $outgoingBtcTransaction['contact_id'],
                        'account_name'           => $accountName,
                        'contactInfo'            => null,
                        'walletTransaction'      => $walletTransaction,
                        'localbtc_released_date' => $outgoingBtcTransaction['anchor_date_localbtc'],
                        'md5'                    => '',
                        'status'                 => false
                    ]);

                    $outgoingBtcTransaction->status = 1;
                    $outgoingBtcTransaction->save();
                }
            }
        }

        return $completeList;
    }

    public static function getLocalBtcV2($credential, $endpoint, $params = '')
    {
        $client = new Client();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential);

        //creating nonce
        $mt    = explode(' ', microtime());
        $nonce = $mt[1] . substr($mt[0], 2, 6);

        //creating signature
        $message       = $nonce . $key . $endpoint . $params;
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        try {
            $res = $client->request('GET', 'https://localbitcoins.com' . $endpoint, [
                'headers' => [
                    'Apiauth-Key'       => $key,
                    'Apiauth-Nonce'     => $nonce,
                    'Apiauth-Signature' => $signature
                ]
            ]);
        } catch (RequestException $e) {
            return $e->getMessage();
        }

        return json_decode($res->getBody(), true)['data'];
    }

    public static function postLocalBtc($credential, $endpoint, $params = [])
    {
        $client = new Client();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential);

        //creating nonce
        $mt    = microtime(true);
        $mt    = str_replace('.', '', $mt);
        $nonce = $mt;

        //creating signature
        $message       = $nonce . $key . $endpoint . http_build_query($params);
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        try {
            $res = $client->request('POST', 'https://localbitcoins.com' . $endpoint, [
                'headers'     => [
                    'Apiauth-Key'       => $key,
                    'Apiauth-Nonce'     => $nonce,
                    'Apiauth-Signature' => $signature
                ],
                'form_params' => $params
            ]);
        } catch (RequestException $e) {
            return $e->getMessage();
        }

        return json_decode($res->getBody(), true)['data'];
    }

    public $countriesEN = [
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island & Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic Of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre And Miquelon',
        'VC' => 'Saint Vincent And Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis And Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    ];

    public $countriesES = [
        'AF' => 'Afganistán',
        'AX' => 'Islas Aland',
        'AL' => 'Albania',
        'DZ' => 'Argelia',
        'AS' => 'Samoa Americana',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguila',
        'AQ' => 'Antártida',
        'AG' => 'Antigua y Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaiyán',
        'BS' => 'Bahamas',
        'BH' => 'Bahrein',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belice',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bután',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia y Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Isla Bouvet',
        'BR' => 'Brasil',
        'IO' => 'Territorio Británico del Océano Índico',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Camboya',
        'CM' => 'Camerún',
        'CA' => 'Canadá',
        'CV' => 'Cabo Verde',
        'KY' => 'Islas Caimán',
        'CF' => 'República Centroafricana',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Isla de Navidad',
        'CC' => 'Islas Cocos (Keeling)',
        'CO' => 'Colombia',
        'KM' => 'Comoras',
        'CG' => 'Congo',
        'CD' => 'Congo, República Democrática',
        'CK' => 'Islas Cook',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D \' Ivoire ',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'República Checa',
        'DK' => 'Dinamarca',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'República Dominicana',
        'EC' => 'Ecuador',
        'EG' => 'Egipto',
        'SV' => 'El Salvador',
        'GQ' => 'Guinea Ecuatorial',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Etiopía',
        'FK' => 'Islas Malvinas',
        'FO' => 'Islas Feroe',
        'FJ' => 'Fiji',
        'FI' => 'Finlandia',
        'FR' => 'Francia',
        'GF' => 'Guayana Francesa',
        'PF' => 'Polinesia francesa',
        'TF' => 'Territorios Franceses del Sur',
        'GA' => 'Gabón',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Alemania',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Grecia',
        'GL' => 'Groenlandia',
        'GD' => 'Grenada',
        'GP' => 'Guadalupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haití',
        'HM' => 'Islas Heard y Mcdonald',
        'VA' => 'Santa Sede (Estado de la Ciudad del Vaticano)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungría',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Irán, República Islámica de',
        'IQ' => 'Iraq',
        'IE' => 'Irlanda',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japón',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenia',
        'KI' => 'Kiribati',
        'KR' => 'Corea',
        'KW' => 'Kuwait',
        'KG' => 'Kirguistán',
        'LA' => 'República Democrática Popular Lao',
        'LV' => 'Letonia',
        'LB' => 'Líbano',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Jamahiriya Árabe Libia',
        'LI' => 'Liechtenstein',
        'LT' => 'Lituania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Islas Marshall',
        'MQ' => 'Martinica',
        'MR' => 'Mauritania',
        'MU' => 'Mauricio',
        'YT' => 'Mayotte',
        'MX' => 'México',
        'FM' => 'Micronesia, Estados federados de',
        'MD' => 'Moldova',
        'MC' => 'Mónaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Marruecos',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Países Bajos',
        'AN' => 'Antillas Holandesas',
        'NC' => 'Nueva Caledonia',
        'NZ' => 'Nueva Zelanda',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Isla Norfolk',
        'MP' => 'Islas Marianas del Norte',
        'NO' => 'Noruega',
        'OM' => 'Omán',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Territorio Palestino Ocupado',
        'PA' => 'Panamá',
        'PG' => 'Papua Nueva Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Perú',
        'PH' => 'Filipinas',
        'PN' => 'Pitcairn',
        'PL' => 'Polonia',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Rumania',
        'RU' => 'Federación Rusa',
        'RW' => 'Rwanda',
        'BL' => 'San Bartolomé',
        'SH' => 'Santa Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Santa Lucía',
        'MF' => 'San Martín',
        'PM' => 'San Pedro y Miquelón',
        'VC' => 'San Vicente y Granadinas',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Santo Tomé y Príncipe',
        'SA' => 'Arabia Saudita',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leona',
        'SG' => 'Singapur',
        'SK' => 'Eslovaquia',
        'SI' => 'Eslovenia',
        'SB' => 'Islas Salomón',
        'SO' => 'Somalia',
        'ZA' => 'Sudáfrica',
        'GS' => 'Islas Georgia del Sur y Sandwich',
        'ES' => 'España',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudán',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Suecia',
        'CH' => 'Suiza',
        'SY' => 'República Árabe Siria',
        'TW' => 'Taiwan',
        'TJ' => 'Tayikistán',
        'TZ' => 'Tanzania',
        'TH' => 'Tailandia',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'A'  => 'Tonga',
        'TT' => 'Trinidad y Tobago',
        'TN' => 'Túnez',
        'TR' => 'Turquía',
        'TM' => 'Turkmenistán',
        'TC' => 'Islas Turcas y Caicos',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ucrania',
        'AE' => 'Emiratos Árabes Unidos',
        'GB' => 'Reino Unido',
        'US' => 'United States',
        'UM' => 'Islas periféricas de los Estados Unidos',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Islas Vírgenes Británicas',
        'VI' => 'Islas Vírgenes, EE.UU.',
        'WF' => 'Wallis y Futuna',
        'EH' => 'Sahara Occidental',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    ];

    /**
     * @param $iso
     *
     * @return array
     */
    public function getCountry($iso): array
    {
        return [
            $this->countriesEN[$iso] ?? null,
            $this->countriesES[$iso] ?? null
        ];
    }
}
