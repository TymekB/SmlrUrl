<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 12.11.18
 * Time: 15:18
 */

namespace App\User;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ApiTokenGenerator
{
    /**
     * @var int
     */
    private $length;

    public function __construct($length = 10)
    {
        $this->length = $length;
    }

    public function setLength(int $length)
    {
        $this->length = $length;

        return $this;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function generate(User $user): bool
    {
        $token = bin2hex(random_bytes($this->length));
        $user->setApiToken($token);

        return true;
    }

}