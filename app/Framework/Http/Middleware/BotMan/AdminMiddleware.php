<?php
/*
 * @author Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 02.01.2021
 * Time: 12:18
 */

namespace App\Framework\Http\Middleware\BotMan;

use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware implements Matching
{
    /**
     * {@inheritDoc}
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched): bool
    {
        return $regexMatched && Auth::user()->is_admin;
    }
}
