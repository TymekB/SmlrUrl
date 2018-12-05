<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 24.11.18
 * Time: 11:11
 */

namespace App\ApiToken;


class Generator
{
    /**
     * @var int
     */
    private $length;

    /**
     * Generator constructor.
     * @param int $length
     */
    public function __construct(int $length = 10)
    {
        $this->length = $length;
    }
    /**
     * @param int $length
     * @return Generator
     */
    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generate(): string
    {
        return bin2hex(random_bytes($this->length));
    }

}