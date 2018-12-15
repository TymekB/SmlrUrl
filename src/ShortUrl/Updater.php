<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.11.18
 * Time: 10:52
 */

namespace App\ShortUrl;


use App\Conversion\RandomNumber;
use App\Dto\ShortUrlDto;
use App\Entity\ShortUrl;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Updater
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var RandomNumber
     */
    private $randomNumber;

    /**
     * Updater constructor.
     * @param EntityManagerInterface $em
     * @param Validator $validator
     * @param RandomNumber $randomNumber
     */
    public function __construct(EntityManagerInterface $em, Validator $validator, RandomNumber $randomNumber)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->randomNumber = $randomNumber;
    }

    /**
     * @param ShortUrlDto $shortUrlDto
     * @return ShortUrl
     * @throws Exception\ShortUrlIsNotValidException
     */
    public function create(ShortUrlDto $shortUrlDto): ShortUrl
    {
        $shortUrlDto->setToken($this->randomNumber->generate());
        $this->validator->validate($shortUrlDto);

        $shortUrl = ShortUrl::createFromDto($shortUrlDto);
        $this->em->persist($shortUrl);
        $this->em->flush();

        return $shortUrl;
    }

    /**
     * @param ShortUrl $shortUrl
     * @param ShortUrlDto $shortUrlDto
     * @return bool
     * @throws Exception\ShortUrlIsNotValidException
     */
    public function update(ShortUrl $shortUrl, ShortUrlDto $shortUrlDto): bool
    {
        $this->validator->validate($shortUrlDto);
        $shortUrl->updateFromDto($shortUrlDto);

        $this->em->persist($shortUrl);
        $this->em->flush();

        return true;
    }

    /**
     * @param ShortUrl $shortUrl
     * @return bool
     */
    public function delete(ShortUrl $shortUrl): bool
    {
        $this->em->remove($shortUrl);
        $this->em->flush();

        return true;
    }
}