<?php

namespace App\Repository;

use App\Entity\ShellizTicketTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizTicketTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizTicketTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizTicketTypes[]    findAll()
 * @method ShellizTicketTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizTicketTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizTicketTypes::class);
    }

    // /**
    //  * @return ShellizTicketTypes[] Returns an array of ShellizTicketTypes objects
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
    public function findOneBySomeField($value): ?ShellizTicketTypes
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
