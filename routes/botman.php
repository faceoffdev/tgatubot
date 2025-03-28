<?php

use App\Bot\Controllers\BotManController;
use App\Bot\Conversations\MainConversation;

$botman = resolve('botman');

$botman->hears('/c', fn ($bot) => $bot->startConversation(new \App\Bot\Conversations\CurrencyConversation()));

$botman->hears('/start ([0-9]+)', BotManController::class . '@startConversation');
$botman->hears('/start', BotManController::class . '@startConversation');

$botman->fallback(fn ($bot) => $bot->startConversation(new MainConversation()));
