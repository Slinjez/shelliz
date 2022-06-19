<?php

namespace App\Repository;

use App\Entity\ShellizTransactionHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizTransactionHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizTransactionHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizTransactionHistory[]    findAll()
 * @method ShellizTransactionHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class liShelzShellizTransactionHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizTransactionHistory::class);
    }

    // /**
    //  * @return ShellizTransactionHistory[] Returns an array of ShellizTransactionHistory objects
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
    public function findOneBySomeField($value): ?ShellizTransactionHistory
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
