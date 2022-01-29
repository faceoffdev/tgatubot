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
        $this->bot->say($text, $receptionId, TelegramDriver::class, $params);
    }
}
