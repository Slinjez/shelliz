<?php

namespace App\Repository;

use App\Entity\ClientContacts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientContacts|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientContacts|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientContacts[]    findAll()
 * @method ClientContacts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientContactsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientContacts::class);
    }

    // /**
    //  * @return ClientContacts[] Returns an array of ClientContacts objects
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
    public function findOneBySomeField($value): ?ClientContacts
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
