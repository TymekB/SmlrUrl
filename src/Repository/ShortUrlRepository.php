<?php

namespace App\Repository;

use App\Entity\ShortUrl;
use App\Entity\User;
use App\ShortUrl\Exception\ShortUrlNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShortUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortUrl[]    findAll()
 * @method ShortUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortUrlRepository extends ServiceEntityRepository
{
    /**
     * @param $id
     * @return ShortUrl
     * @throws ShortUrlNotFoundException
     */
    public function getOneById($id): ShortUrl
    {
        $shortUrl = $this->find($id);

        if(!$shortUrl) {
            throw new ShortUrlNotFoundException();
        }

        return $shortUrl;
    }

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShortUrl::class);
    }

//    /**
//     * @return ShortUrl[] Returns an array of ShortUrl objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShortUrl
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
