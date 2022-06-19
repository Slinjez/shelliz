<?php

namespace App\Repository;

use App\Entity\ShellizClaimStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizClaimStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizClaimStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizClaimStatus[]    findAll()
 * @method ShellizClaimStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizClaimStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizClaimStatus::class);
    }

    // /**
    //  * @return ShellizClaimStatus[] Returns an array of ShellizClaimStatus objects
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
    public function findOneBySomeField($value): ?ShellizClaimStatus
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
