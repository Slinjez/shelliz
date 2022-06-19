<?php

namespace App\Repository;

use App\Entity\ShellizPolicyBlueprint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizPolicyBlueprint|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizPolicyBlueprint|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizPolicyBlueprint[]    findAll()
 * @method ShellizPolicyBlueprint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizPolicyBlueprintRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizPolicyBlueprint::class);
    }

    // /**
    //  * @return ShellizPolicyBlueprint[] Returns an array of ShellizPolicyBlueprint objects
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
    public function findOneBySomeField($value): ?ShellizPolicyBlueprint
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
