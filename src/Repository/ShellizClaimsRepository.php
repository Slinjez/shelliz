<?php

namespace App\Repository;

use App\Entity\ShellizClaims;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizClaims|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizClaims|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizClaims[]    findAll()
 * @method ShellizClaims[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizClaimsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizClaims::class);
    }

    // /**
    //  * @return ShellizClaims[] Returns an array of ShellizClaims objects
    //  */
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
    public function findOneBySomeField($value): ?ShellizClaims
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
