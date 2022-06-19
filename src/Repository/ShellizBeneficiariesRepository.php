<?php

namespace App\Repository;

use App\Entity\ShellizBeneficiaries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizBeneficiaries|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizBeneficiaries|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizBeneficiaries[]    findAll()
 * @method ShellizBeneficiaries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizBeneficiariesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizBeneficiaries::class);
    }

    // /**
    //  * @return ShellizBeneficiaries[] Returns an array of ShellizBeneficiaries objects
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
    public function findOneBySomeField($value): ?ShellizBeneficiaries
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
