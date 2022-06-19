<?php

namespace App\Repository;

use App\Entity\ShellizPolicyStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizPolicyStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizPolicyStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizPolicyStatus[]    findAll()
 * @method ShellizPolicyStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizPolicyStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizPolicyStatus::class);
    }

    // /**
    //  * @return ShellizPolicyStatus[] Returns an array of ShellizPolicyStatus objects
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
    public function findOneBySomeField($value): ?ShellizPolicyStatus
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
