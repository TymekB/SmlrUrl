<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 24.11.18
 * Time: 11:07
 */

namespace App\ApiToken;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Updater
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Generator
     */
    private $generator;
    /**
     * @var string
     */
    private $intervalExpirationDate;

    /**
     * Updater constructor.
     * @param EntityManagerInterface $em
     * @param Generator $generator
     * @param string $intervalExpirationDate
     */
    public function __construct(EntityManagerInterface $em, Generator $generator, string $intervalExpirationDate = 'P3M')
    {
        $this->em = $em;
        $this->generator = $generator;
        $this->intervalExpirationDate = $intervalExpirationDate;
    }

    public function setIntervalExpirationDate($intervalExpirationDate): self
    {
        $this->intervalExpirationDate = $intervalExpirationDate;

        return $this;
    }

    /**
     * @param User $user
     * @param string $description
     * @return bool
     * @throws \Exception
     */
    public function create(User $user, $description = 'default'): bool
    {
        $date = new \DateTime();
        $expirationDate = $date->add(new \DateInterval($this->intervalExpirationDate));

        $apiToken = new ApiToken();
        $apiToken->setUser($user);
        $apiToken->setToken($this->generator->generate());
        $apiToken->setDescription($description);
        $apiToken->setExpirationDate($expirationDate);

        $user->addApiToken($apiToken);

        $this->em->persist($apiToken);
        $this->em->persist($user);
        $this->em->flush();

        return true;
    }

    /**
     * @param ApiToken $apiToken
     * @param string|null $description
     * @return bool
     */
    public function update(ApiToken $apiToken, string $description = null): bool
    {
        if($description) {
            $apiToken->setDescription($description);
        }

        $this->em->persist($apiToken);
        $this->em->flush();

        return true;
    }

    /**
     * @param ApiToken $apiToken
     * @return bool
     */
    public function delete(ApiToken $apiToken): bool
    {
        $this->em->remove($apiToken);
        $this->em->flush();

        return true;
    }

}