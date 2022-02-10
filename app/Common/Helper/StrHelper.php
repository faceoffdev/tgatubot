<?php

namespace App\Common\Helper;

class StrHelper
{
    // потому что undefined mb_strimwidth
    public static function strimwidth(string $str, int $start, int $width): string
    {
        if ($width >= (mb_strlen($str) + 2)) {
            return $str;
        }

        return mb_substr($str, $start, $width) . '..';
    }
}
