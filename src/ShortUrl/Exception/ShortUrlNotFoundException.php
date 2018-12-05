<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.11.18
 * Time: 12:53
 */

namespace App\ShortUrl\Exception;


use Throwable;

class ShortUrlNotFoundException extends \Exception
{
    public function __construct(string $message = 'ShortUrl not found', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}