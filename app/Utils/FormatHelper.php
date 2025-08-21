<?php

namespace App\Utils;

class FormatHelper
{
    public static function currencyFormat(int $value): string
    {
        return number_format($value / 100, 2, ',', '.');
    }

}
