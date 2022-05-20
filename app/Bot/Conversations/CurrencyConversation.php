<?php

namespace App\Bot\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CurrencyConversation extends Conversation
{
    public const MIN_FIAT_AMOUNT = 1000;

    public const BOT_PERCENT        = 1;
    public const MIN_PROFIT_PERCENT = 14;

    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function run()
    {
        $tonCurrency  = $this->tonCurrency();
        $cryptoAmount = $this->cryptoAmount();

        $minProfitPercent       = self::MIN_PROFIT_PERCENT;
        $cryptoAmountWithProfit = $tonCurrency + ($tonCurrency * ($minProfitPercent / 100));
        $profit                 = ($cryptoAmountWithProfit - $cryptoAmountWithProfit * (self::BOT_PERCENT / 100)) - $cryptoAmount;

        $this->say(
            "Продажа по $cryptoAmountWithProfit RUB ($minProfitPercent%) принесет $profit RUB за 1 TON" . PHP_EOL . PHP_EOL
            . "Курс на coingecko: *$tonCurrency* RUB" . PHP_EOL
            . "Курс на neocrypto: *$cryptoAmount* RUB" . PHP_EOL . PHP_EOL
            . 'Обновлено: ' . Carbon::now('Europe/Moscow')->format('H:i d.m.Y')
            . ' | [Купить](https://t.me/CryptoBot?start=r-206218-market)',
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

        return round(self::MIN_FIAT_AMOUNT / $result['crypto_amount'], 2);
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
