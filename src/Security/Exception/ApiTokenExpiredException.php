<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 17.11.18
 * Time: 14:57
 */

namespace App\Security\Exception;


use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ApiTokenExpiredException extends AuthenticationException
{
    public function getMessageKey(): string
    {
        return 'Your token has expired.';
    }

}