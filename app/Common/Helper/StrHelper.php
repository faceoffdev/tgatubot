<?php

namespace App\Common\Helper;

class StrHelper
{
    // потому что undefined mb_strimwidth
    public static function strimwidth(string $str, int $start, int $width): string
    {
        if (strlen($str) <= $width) {
            return $str;
        }

        return substr($str, $start, $width) . '...';
    }
}
