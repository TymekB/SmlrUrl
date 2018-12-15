<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 15.12.18
 * Time: 10:59
 */

namespace App\Dto;


class ShortUrlDto
{
    private $url;
    private $token;
    private $user;

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return ShortUrlDto
     */
    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return ShortUrlDto
     */
    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return ShortUrlDto
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }
}