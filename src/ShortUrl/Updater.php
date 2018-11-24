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

    public function __construct(EntityManagerInterface $em, Validator $validator, RandomNumber $randomNumber)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->randomNumber = $randomNumber;
    }

    public function create(?User $user, string $url)
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

    public function update(ShortUrl $shortUrl, string $url)
    {
        $shortUrl->setUrl($url);

        $this->validator->validate($shortUrl);

        $this->em->flush();

        return true;
    }

    public function delete(ShortUrl $shortUrl)
    {
        $this->em->remove($shortUrl);
        $this->em->flush();

        return true;
    }
}