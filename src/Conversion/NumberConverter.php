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
    const BINARY = '01';
    const HEX = '0123456789ABCDEF';
    const OCTAL = '012345678';
    const TOKEN = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function encode($number, $system)
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

    public function decode($value, $system)
    {
        $base = strlen($system);
        $systemArr = array_flip(str_split($system));
        $sum = 0;

        for($i=0; $i < strlen($value); $i++) {

            $sum += $systemArr[$value[$i]] * pow($base, strlen($value)-$i-1);
        }

        return $sum;
    }
}