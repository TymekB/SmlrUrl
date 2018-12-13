<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Dto\ApiTokenDto;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 */
class ApiToken
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiration_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    public static function createFromDto(ApiTokenDto $apiTokenDto): self
    {
        $apiToken = new self();

        $apiToken->id = $apiTokenDto->getId();
        $apiToken->token = $apiTokenDto->getToken();
        $apiToken->description= $apiTokenDto->getDescription();
        $apiToken->expiration_date = $apiTokenDto->getExpirationDate();
        $apiToken->active = $apiTokenDto->getActive();
        $apiToken->user = $apiTokenDto->getUser();

        return $apiToken;
    }

    public function __toString()
    {
        return 'test';
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }
}
