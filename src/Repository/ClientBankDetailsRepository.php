<?php

namespace App\Repository;

use App\Entity\ClientBankDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientBankDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientBankDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientBankDetails[]    findAll()
 * @method ClientBankDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientBankDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientBankDetails::class);
    }

    // /**
    //  * @return ClientBankDetails[] Returns an array of ClientBankDetails objects
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
    public function findOneBySomeField($value): ?ClientBankDetails
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
