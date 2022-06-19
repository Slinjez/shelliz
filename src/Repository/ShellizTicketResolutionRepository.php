<?php

namespace App\Repository;

use App\Entity\ShellizTicketResolution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizTicketResolution|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizTicketResolution|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizTicketResolution[]    findAll()
 * @method ShellizTicketResolution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizTicketResolutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizTicketResolution::class);
    }

    // /**
    //  * @return ShellizTicketResolution[] Returns an array of ShellizTicketResolution objects
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
    public function findOneBySomeField($value): ?ShellizTicketResolution
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
