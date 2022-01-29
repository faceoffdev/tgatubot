<?php

namespace App\Bot\Enums\Buttons;

enum QuestionMassButton: string
{
    case ALL             = 'all';
    case MODULE          = 'module';
    case MODULE_NOT_LAB  = 'module_not_lab';
    case MODULE_WITH_LAB = 'module_with_lab';
}
