<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 13.11.18
 * Time: 22:14
 */

namespace App\Conversion;


class NumberConverter
{
    public const BINARY = '01';
    public const HEX = '0123456789ABCDEF';
    public const OCTAL = '012345678';
    public const TOKEN = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @param int $number
     * @param string $system
     * @return string
     */
    public function encode(int $number, string $system): string
    {
        $base = strlen($system);
        $encodedValue = '';

        do {
            $i = $number % $base;
            $encodedValue = $system[$i].$encodedValue;

            $number = ($number-$i) / $base;

        }while($number > 0);

        return $encodedValue;
    }

    /**
     * @param int $value
     * @param string $system
     * @return int
     */
    public function decode(int $value, string $system): int
    {
        $base = strlen($system);
        $systemArr = array_flip(str_split($system));
        $sum = 0;

        for($i=0, $iMax = strlen($value); $i < $iMax; $i++) {

            $sum += $systemArr[$value[$i]] * ($base ** (strlen($value) - $i - 1));
        }

        return $sum;
    }
}