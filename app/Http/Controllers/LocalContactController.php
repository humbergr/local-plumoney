<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Modules\LocalContactModule;
use App\Modules\ExchangeModule;

use App\Credential;

use Pusher\Laravel\Facades\Pusher;


class LocalContactController extends Controller
{
    protected $localContactModule;
    protected $exchangeModule;
    protected $credential;

    public function __construct(
        LocalContactModule $localContactModule,
        ExchangeModule $exchangeModule
    )
    {
        $this->credential         = Credential::where('is_active', 1)->first();
        $this->localContactModule = $localContactModule;
        $this->exchangeModule     = $exchangeModule;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postOpenContact(): array
    {
        $inputs           = request()->all();
        $exchanges_amount = $this->exchangeModule->getExchangeAmount($inputs['params']['exchange_ids']);

        $response         = $this->localContactModule->createLocalContact(
            $this->credential->env_number,
            $inputs['params'],
            $exchanges_amount
        );

        if ($response['status'] === 'success'){
            $this->exchangeModule->editById(
                $inputs['params']['exchange_ids'],
                ['contact_id' => $response['info']->local_contact_id]
            );

            $response['exchanges'] = $this->exchangeModule->getMyOpenExchanges();
        }

        return $response;
    }
}
