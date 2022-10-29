<?php

namespace App\Bot\Enums\Buttons;

enum WalletButton: string
{
    case TOP_UP = 'top_up';
    case WITHDRAW = 'withdraw';
    case PAY_URL = 'pay_ref_url';
}
