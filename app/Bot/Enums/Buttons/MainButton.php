<?php

namespace App\Bot\Enums\Buttons;

enum MainButton: string
{
    case WALLET = 'wallet';
    case MARKET = 'market';
    case REFERRALS = 'referrals';
    case SETTINGS = 'settings';
}
