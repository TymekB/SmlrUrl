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

    public function encodeToken(ShortUrl $shortUrl, $system = NumberConverter::TOKEN)
    {
        $token = $this->converter->encode($shortUrl->getToken(), $system);
        $shortUrl->setToken($token);

        return $shortUrl;
    }

    public function encodeTokenFromCollection(Collection $shortUrlCollection, $system = NumberConverter::TOKEN)
    {
        foreach($shortUrlCollection as $shortUrl) {
            $token = $this->converter->encode($shortUrl->getToken(), $system);
            $shortUrl->setToken($token);
        }

        return $shortUrlCollection;
    }

}