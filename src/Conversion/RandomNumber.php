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

    public function __construct($maxRange, $minRange = 0)
    {

        $this->maxRange = $maxRange;
        $this->minRange = $minRange;
    }

    public function setMinRange($minRange)
    {
        $this->minRange = $minRange;

        return $this;
    }

    public function setMaxRange($maxRange)
    {
        $this->maxRange = $maxRange;

        return $this;
    }

    public function getMinRange()
    {
        return $this->minRange;
    }

    public function getMaxRange()
    {
        return $this->maxRange;
    }

    public function generate()
    {
        return mt_rand($this->minRange, $this->maxRange);
    }

}