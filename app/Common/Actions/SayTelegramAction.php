<?php

namespace App\Common\Actions;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\TelegramDriver;

final class SayTelegramAction
{
    public function __construct(private BotMan $bot)
    {
    }

    public function execute(string $text, int $receptionId, $params = []): void
    {
        $receptionIds = [$receptionId, config('botman.telegram.info')];

        $this->bot->say($text, $receptionIds, TelegramDriver::class, $params);
    }
}
