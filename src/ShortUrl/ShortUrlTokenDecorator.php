<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 14.11.18
 * Time: 16:44
 */

namespace App\ShortUrl;


use App\Conversion\NumberConverter;
use App\Entity\ShortUrl;
use Doctrine\Common\Collections\Collection;

class ShortUrlTokenDecorator
{
    /**
     * @var NumberConverter
     */
    private $converter;

    public function __construct(NumberConverter $converter)
    {
        $this->converter = $converter;
    }

    public function encodeToken(ShortUrl $shortUrl)
    {
        $token = $this->converter->encode($shortUrl->getToken(), NumberConverter::TOKEN);
        $shortUrl->setToken($token);
    }

    public function encodeTokenFromCollection(Collection $shortUrlCollection)
    {
        foreach($shortUrlCollection as $shortUrl) {
            $token = $this->converter->encode($shortUrl->getToken(), NumberConverter::TOKEN);
            $shortUrl->setToken($token);
        }
    }

}