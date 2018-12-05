<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 15.11.18
 * Time: 16:42
 */

namespace App\Conversion;


class RandomNumber
{
    private $maxRange;
    /**
     * @var null
     */
    private $minRange;

    /**
     * RandomNumber constructor.
     * @param $maxRange
     * @param int $minRange
     */
    public function __construct($maxRange, $minRange = 0)
    {

        $this->maxRange = $maxRange;
        $this->minRange = $minRange;
    }

    /**
     * @param $minRange
     * @return $this
     */
    public function setMinRange($minRange): self
    {
        $this->minRange = $minRange;

        return $this;
    }

    /**
     * @param $maxRange
     * @return $this
     */
    public function setMaxRange($maxRange): self
    {
        $this->maxRange = $maxRange;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinRange(): int
    {
        return $this->minRange;
    }

    /**
     * @return int
     */
    public function getMaxRange(): int
    {
        return $this->maxRange;
    }

    /**
     * @return int
     */
    public function generate(): int
    {
        return mt_rand($this->minRange, $this->maxRange);
    }

}