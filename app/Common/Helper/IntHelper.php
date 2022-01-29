<?php

namespace App\Common\Helper;

class IntHelper
{
    public static function parse(string $value): int
    {
        return (int) preg_replace('/\D/', '', $value);
    }
}
