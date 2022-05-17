<?php

namespace App\Bot\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CurrencyConversation extends Conversation
{
    public const MIN_FIAT_AMOUNT = 1000;

    private Client $client;

    public function __construct(private float $percent = 15)
    {
        $this->client = new Client();
    }

    public function run()
    {
        $tonCurrency  = $this->tonCurrency();
        $cryptoAmount = $this->cryptoAmount();

        $percent = ($tonCurrency + ($tonCurrency * ($this->percent / 100))) / $cryptoAmount * 100;

        $this->say(
            "Прибыль: $percent%"
            . ' | [Купить](https://t.me/CryptoBot?start=r-206218-market)' . PHP_EOL . PHP_EOL
            . "Курс Toncoin (coingecko): *$tonCurrency* RUB" . PHP_EOL
            . "Курс Toncoin (neocrypto): *$cryptoAmount* RUB" . PHP_EOL . PHP_EOL
            . 'Обновлено: ' . Carbon::now('Europe/Moscow')->format('H:i d.m.Y'),
            ['parse_mode' => 'markdown']
        );
    }

    private function cryptoAmount(): float
    {
        $response = $this->client->post('https://api.neocrypto.net/api/purchase/request/', [
            'json' => [
                'fiat_amount'     => self::MIN_FIAT_AMOUNT,
                'fiat_currency'   => 'RUB',
                'crypto_currency' => 'TON',
            ],
        ]);
        $result = json_decode($response->getBody(), true);

        return round($result['crypto_amount'] / self::MIN_FIAT_AMOUNT, 2);
    }

    private function tonCurrency(): float
    {
        $id       = 'the-open-network';
        $currency = 'rub';

        $response = $this->client->get('https://api.coingecko.com/api/v3/simple/price', [
            'query' => [
                'ids'           => $id,
                'vs_currencies' => $currency,
            ],
        ]);
        $result = json_decode($response->getBody(), true);

        return round($result[$id][$currency], 2);
    }
}
