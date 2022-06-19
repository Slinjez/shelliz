<?php

namespace App\Repository;

use App\Entity\ShellizBeneficiaryRelarionships;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizBeneficiaryRelarionships|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizBeneficiaryRelarionships|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizBeneficiaryRelarionships[]    findAll()
 * @method ShellizBeneficiaryRelarionships[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizBeneficiaryRelarionshipsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizBeneficiaryRelarionships::class);
    }

    // /**
    //  * @return ShellizBeneficiaryRelarionships[] Returns an array of ShellizBeneficiaryRelarionships objects
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
    public function findOneBySomeField($value): ?ShellizBeneficiaryRelarionships
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
