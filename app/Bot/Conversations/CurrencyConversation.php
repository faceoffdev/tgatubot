<?php

namespace App\Bot\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use GuzzleHttp\Client;

class CurrencyConversation extends Conversation
{
    public const PERCENT       = 0.01;
    public const BANK_CURRENCY = 2958.58;

    public function run()
    {
        $client = new Client();

        $response = $client->post('https://api.neocrypto.net/api/purchase/request/', [
            'json' => [
                'fiat_amount'     => 100,
                'fiat_currency'   => 'USD',
                'crypto_currency' => 'TON',
            ],
        ]);

        $result = json_decode($response->getBody(), true);

        $cryptoAmount = $result['crypto_amount'];
        $amount       = self::BANK_CURRENCY / ($cryptoAmount - ($cryptoAmount * self::PERCENT));

        $this->say("1 TON - {$amount} UAH");
    }
}
