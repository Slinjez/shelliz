<?php

namespace App\Repository;

use App\Entity\ShellizRenewalFrequency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizRenewalFrequency|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizRenewalFrequency|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizRenewalFrequency[]    findAll()
 * @method ShellizRenewalFrequency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizRenewalFrequencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizRenewalFrequency::class);
    }

    // /**
    //  * @return ShellizRenewalFrequency[] Returns an array of ShellizRenewalFrequency objects
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
    public function findOneBySomeField($value): ?ShellizRenewalFrequency
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
