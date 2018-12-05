<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 02.12.18
 * Time: 20:26
 */

namespace App\ApiToken\Exception;


use Throwable;

class ApiTokenNotFoundException extends \Exception
{
    public function __construct(string $message = 'ApiToken not found', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}