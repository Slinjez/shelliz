<?php

namespace App\Repository;

use App\Entity\ShellizTickets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizTickets|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizTickets|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizTickets[]    findAll()
 * @method ShellizTickets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizTicketsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizTickets::class);
    }

    // /**
    //  * @return ShellizTickets[] Returns an array of ShellizTickets objects
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
    public function findOneBySomeField($value): ?ShellizTickets
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
