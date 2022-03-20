<?php

namespace App\Bot\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use GuzzleHttp\Client;

class CurrencyConversation extends Conversation
{
    public const PERCENT = 0.01;

    public const BASE_COUNT = 100;

    private Client $client;

    public function __construct(private float $num = 1)
    {
        $this->client = new Client();
    }

    public function run()
    {
        $bankCurrency = $this->bankCurrency();
        $cryptoAmount = $this->cryptoAmount();

        $amount = $this->num;
        $amount *= round((self::BASE_COUNT * $bankCurrency) / ($cryptoAmount - ($cryptoAmount * self::PERCENT)), 2);

        $this->say(
            "{$this->num} TON - {$amount} UAH" . PHP_EOL . PHP_EOL
            . "Курс pumb: $bankCurrency UAH" . PHP_EOL
            . 'Курс neocrypto: ' . round(self::BASE_COUNT / $cryptoAmount, 3) . ' USD'
        );
    }

    private function bankCurrency(): float
    {
        return 29.9;
    }

    private function cryptoAmount(): float
    {
        $response = $this->client->post('https://api.neocrypto.net/api/purchase/request/', [
            'json' => [
                'fiat_amount'     => self::BASE_COUNT,
                'fiat_currency'   => 'USD',
                'crypto_currency' => 'TON',
            ],
        ]);
        $result = json_decode($response->getBody(), true);

        return $result['crypto_amount'];
    }
}
