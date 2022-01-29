<?php
/*
 * @author Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 02.01.2021
 * Time: 11:22
 */

namespace App\Framework\Http\Middleware;

use function app;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizeBotman
{
    private BotMan $botMan;

    public function __construct()
    {
        $this->botMan = app('botman');
    }

    public function handle(Request $request, Closure $next)
    {
        /** @var IncomingMessage $message */
        [$message] = $this->botMan->getMessages();

        if ($message) {
            Auth::onceUsingId($message->getSender());
        }

        return $next($request);
    }
}
