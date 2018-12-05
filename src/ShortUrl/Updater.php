<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.11.18
 * Time: 10:52
 */

namespace App\ShortUrl;


use App\Conversion\RandomNumber;
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
     * @param User|null $user
     * @param string $url
     * @return ShortUrl
     * @throws Exception\ShortUrlIsNotValidException
     */
    public function create(?User $user, string $url): ShortUrl
    {
        $shortUrl = new ShortUrl();
        $shortUrl->setUrl($url);
        $shortUrl->setToken($this->randomNumber->generate());
        $shortUrl->setUser($user);

        $this->validator->validate($shortUrl);

        $this->em->persist($shortUrl);
        $this->em->flush();

        return $shortUrl;
    }

    /**
     * @param ShortUrl $shortUrl
     * @param string $url
     * @return bool
     * @throws Exception\ShortUrlIsNotValidException
     */
    public function update(ShortUrl $shortUrl, string $url): bool
    {
        $shortUrl->setUrl($url);

        $this->validator->validate($shortUrl);

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