<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.11.18
 * Time: 10:52
 */

namespace App\ShortUrl;


use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use App\ShortUrl\Exception\ShortUrlIsNotValidException;
use App\ShortUrl\Exception\ShortUrlNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShortUrlUpdater
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;}

    public function create($url, $token)
    {
        $shortUrl = new ShortUrl();
        $shortUrl->setUrl($url);
        $shortUrl->setUrlId($token);

        $errors = $this->validator->validate($shortUrl);

        if(count($errors) > 0) {
            throw new ShortUrlIsNotValidException($errors);
        }

        $this->em->persist($shortUrl);
        $this->em->flush();

        return true;
    }

    public function update($id, $url)
    {
        $shortUrl = $this->em->getRepository(ShortUrl::class)->find($id);

        if(!$shortUrl) {
            throw new ShortUrlNotFoundException();
        }

        $shortUrl->setUrl($url);

        $errors = $this->validator->validate($shortUrl);

        if(count($errors) > 0) {
            throw new ShortUrlIsNotValidException($errors);
        }

        $this->em->flush();

        return true;
    }

    public function delete($id)
    {
        $shortUrl = $this->em->getRepository(ShortUrl::class)->find($id);

        if(!$shortUrl) {
            throw new ShortUrlNotFoundException();
        }

        $this->em->remove($shortUrl);
        $this->em->flush();

        return true;
    }
}