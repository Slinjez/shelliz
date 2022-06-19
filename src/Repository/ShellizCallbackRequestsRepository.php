<?php

namespace App\Repository;

use App\Entity\ShellizCallbackRequests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizCallbackRequests|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizCallbackRequests|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizCallbackRequests[]    findAll()
 * @method ShellizCallbackRequests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizCallbackRequestsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizCallbackRequests::class);
    }

    // /**
    //  * @return ShellizCallbackRequests[] Returns an array of ShellizCallbackRequests objects
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
    public function findOneBySomeField($value): ?ShellizCallbackRequests
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
