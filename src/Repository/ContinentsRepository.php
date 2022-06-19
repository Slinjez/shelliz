<?php

namespace App\Repository;

use App\Entity\Continents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Continents|null find($id, $lockMode = null, $lockVersion = null)
 * @method Continents|null findOneBy(array $criteria, array $orderBy = null)
 * @method Continents[]    findAll()
 * @method Continents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContinentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Continents::class);
    }

    // /**
    //  * @return Continents[] Returns an array of Continents objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Continents
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
