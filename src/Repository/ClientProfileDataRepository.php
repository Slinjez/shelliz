<?php

namespace App\Repository;

use App\Entity\ClientProfileData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientProfileData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientProfileData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientProfileData[]    findAll()
 * @method ClientProfileData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientProfileDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientProfileData::class);
    }

    // /**
    //  * @return ClientProfileData[] Returns an array of ClientProfileData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClientProfileData
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
