<?php

namespace App\Bot\Controllers;

use Illuminate\Support\Facades\Log;
use function app;
use App\Bot\Actions\RegistrationAction;
use App\Bot\Conversations\MainConversation;
use App\Bot\Decorators\ChatTelegramUser;
use BotMan\BotMan\BotMan;
use Illuminate\Routing\Controller as BaseController;

class BotManController extends BaseController
{
    public function handle(): void
    {
        try {
            $botman = app('botman');

            $botman->listen();
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), ['trace' => $e->getTrace()]);
        }
    }

    public function startConversation(BotMan $bot, ?int $referralId = null): void
    {
        (new RegistrationAction())->execute(new ChatTelegramUser($bot->getUser()), $referralId);

        $bot->startConversation(new MainConversation());
    }
}
