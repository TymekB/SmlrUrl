<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 12.11.18
 * Time: 15:18
 */

namespace App\User;

use App\Entity\ApiToken;
use App\Entity\User;
use DateTime;

class ApiTokenGenerator
{
    /**
     * @var int
     */
    private $length;
    /**
     * @var string
     */
    private $intervalExpirationDate;

    public function __construct($length = 10, $intervalExpirationDate = 'P3M')
    {
        $this->length = $length;
        $this->intervalExpirationDate = $intervalExpirationDate;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setIntervalExpirationDate($intervalExpirationDate): self
    {
        $this->intervalExpirationDate = $intervalExpirationDate;

        return $this;
    }

    public function getIntervalExpirationDate(): string
    {
        return $this->getIntervalExpirationDate();
    }

    public function generate(User $user, ApiToken $apiToken, $description = 'default'): bool
    {
        $date = new DateTime();
        $expirationDate = $date->add(new \DateInterval($this->intervalExpirationDate));

        $apiToken->setToken(bin2hex(random_bytes($this->length)));
        $apiToken->setUser($user);
        $apiToken->setDescription($description);
        $apiToken->setExpirationDate($expirationDate);

        $user->addApiToken($apiToken);

        return true;
    }

}