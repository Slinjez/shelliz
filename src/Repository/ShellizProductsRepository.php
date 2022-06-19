<?php

namespace App\Repository;

use App\Entity\ShellizProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizProducts[]    findAll()
 * @method ShellizProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizProducts::class);
    }

    // /**
    //  * @return ShellizProducts[] Returns an array of ShellizProducts objects
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
    public function findOneBySomeField($value): ?ShellizProducts
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
