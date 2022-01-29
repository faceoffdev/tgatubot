<?php

namespace App\Bot\Controllers;

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
        $botman = app('botman');

        $botman->listen();
    }

    public function startConversation(BotMan $bot, ?int $referralId = null): void
    {
        (new RegistrationAction())->execute(new ChatTelegramUser($bot->getUser()), $referralId);

        $bot->startConversation(new MainConversation());
    }
}
