<?php

namespace App\Common\Enums;

enum OrderStatus: string
{
    case WAIT       = 'wait';
    case PROCESSING = 'processing';
    case COMPLETED  = 'completed';
}
