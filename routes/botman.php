<?php

use App\Bot\Controllers\BotManController;
use App\Bot\Conversations\MainConversation;

$botman = resolve('botman');

$botman->hears('/start ([0-9]+)', BotManController::class . '@startConversation');
$botman->hears('/start', BotManController::class . '@startConversation');
$botman->hears('/c  ([0-9]+)', fn ($bot, $num) => $bot->startConversation(new \App\Bot\Conversations\CurrencyConversation($num)));
$botman->hears('/c', fn ($bot,) => $bot->startConversation(new \App\Bot\Conversations\CurrencyConversation()));

$botman->fallback(fn ($bot) => $bot->startConversation(new MainConversation()));
