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

class TokenDecorator
{
    /**
     * @var NumberConverter
     */
    private $converter;

    /**
     * TokenDecorator constructor.
     * @param NumberConverter $converter
     */
    public function __construct(NumberConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @param ShortUrl $shortUrl
     * @param string $system
     * @return ShortUrl
     */
    public function encodeToken(ShortUrl $shortUrl, $system = NumberConverter::TOKEN): ShortUrl
    {
        $token = $this->converter->encode($shortUrl->getToken(), $system);
        $shortUrl->setToken($token);

        return $shortUrl;
    }

    /**
     * @param Collection $shortUrlCollection
     * @param string $system
     * @return Collection
     */
    public function encodeTokenFromCollection(Collection $shortUrlCollection, $system = NumberConverter::TOKEN): Collection
    {
        foreach($shortUrlCollection as $shortUrl) {
            $token = $this->converter->encode($shortUrl->getToken(), $system);
            $shortUrl->setToken($token);
        }

        return $shortUrlCollection;
    }

}