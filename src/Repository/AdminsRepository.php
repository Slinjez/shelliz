<?php

namespace App\Repository;

use App\Entity\Admins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Admins|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admins|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admins[]    findAll()
 * @method Admins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admins::class);
    }

    // /**
    //  * @return Admins[] Returns an array of Admins objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Admins
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function login($email, $password)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $email = $conn->quote($email);
        $query = ('SELECT * FROM `admins` WHERE LOWER(`email`) = LOWER(' . $email . ');');
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        $retVal = array();

        if (empty($results)) {
            $retVal = array(
                'status' => 'fail',
                'msg' => 'Account Not Found',
            );
            return $retVal;
            exit;
        } else {
            $profpic = '';
            if ($results[0]['password'] == $password) {
                if ($results[0]['profpic'] == '') {
                    $profpic = 'assets/images/users/16.jpg';
                } else {
                    $profpic = $results[0]['profpic'];
                }
                $retVal = array(
                    'status' => 'ok',
                    'retval' => array(
                        'uuid' => $results[0]['record_id'],
                        'username' => $results[0]['username'],
                        'email' => $results[0]['email'],
                        'role' => $results[0]['role'],
                        'profpic' => $profpic,
                    ),
                );
                return $retVal;
                exit;
            } else {
                $retVal = array(
                    'status' => 'fail',
                    'msg' => 'invalid credentials',
                );
                return $retVal;
                exit;
            }
        }

    }


}
