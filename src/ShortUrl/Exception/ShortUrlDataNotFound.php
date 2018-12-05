<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.11.18
 * Time: 14:27
 */

namespace App\ShortUrl\Exception;


use Throwable;

class ShortUrlDataNotFound extends \Exception
{
    public function __construct(string $message = 'Data not found', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}