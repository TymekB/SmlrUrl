<?php

namespace App\Entity;

use App\Dto\ShortUrlDto;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShortUrlRepository")
 */
class ShortUrl implements \JsonSerializable
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
    private $url;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="shortUrls")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public static function createFromDto(ShortUrlDto $dto): self
    {
        $shortUrl = new self();
        $shortUrl->setUrl($dto->getUrl());
        $shortUrl->setToken($dto->getToken());
        $shortUrl->setUser($dto->getUser());

        return $shortUrl;
    }

    public function updateFromDto(ShortUrlDto $dto): void
    {
        if($dto->getUser()) {
            $this->setUser($dto->getUser());
        }

        if($dto->getToken()) {
            $this->setToken($dto->getToken());
        }

        if($dto->getUrl()) {
            $this->setUrl($dto->getUrl());
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return ['id' => $this->getId(), 'url' => $this->getUrl(), 'token' => $this->getToken()];
    }
}
