<?php

namespace App\Repository;

use App\Entity\ShellizPolicies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShellizPolicies|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShellizPolicies|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShellizPolicies[]    findAll()
 * @method ShellizPolicies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShellizPoliciesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShellizPolicies::class);
    }

    // /**
    //  * @return ShellizPolicies[] Returns an array of ShellizPolicies objects
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
    public function findOneBySomeField($value): ?ShellizPolicies
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    
    public function get_policy_uptake_stat($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $start_date = $conn->quote($data['start_date']);
        $end_date = $conn->quote($data['end_date']);

        $query = ("SELECT COUNT(shelliz_products.product_name) AS policy_count, shelliz_products.product_name, CONCAT( DATE_FORMAT(shelliz_policies.policy_start_date, '%b'), '-', YEAR(shelliz_policies.policy_start_date))as era

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on shelliz_products.record_id=shelliz_policies.product_id 
        
        WHERE (`shelliz_policies`.`policy_start_date`) BETWEEN $start_date AND $end_date
        
        GROUP BY shelliz_products.product_name,shelliz_policies.policy_start_date

        ORDER BY shelliz_policies.policy_start_date ASC ;");
        // echo $query;
        // exit;
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    
    public function get_policy_uptake_stat_moth_fortmat($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $start_date = $conn->quote($data['start_date']);
        $end_date = $conn->quote($data['end_date']);

        $query = ("SELECT COUNT(shelliz_products.product_name) AS policy_count, shelliz_products.product_name, CONCAT( DATE_FORMAT(shelliz_policies.policy_start_date, '%b'), '-', YEAR(shelliz_policies.policy_start_date))as era,CONCAT( DATE_FORMAT(shelliz_policies.policy_start_date, '%b'), '-', YEAR(shelliz_policies.policy_start_date))as era

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on shelliz_products.record_id=shelliz_policies.product_id 
        
        WHERE (`shelliz_policies`.`policy_start_date`) BETWEEN $start_date AND $end_date
        
        GROUP BY shelliz_products.product_name,shelliz_policies.policy_start_date

        ORDER BY shelliz_policies.policy_start_date ASC ;");
        // echo $query;
        // exit;
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_policy_uptake_stat_product_list($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $start_date = $conn->quote($data['start_date']);
        $end_date = $conn->quote($data['end_date']);

        $query = ("       
        
        SELECT DISTINCT(shelliz_products.product_name) FROM `shelliz_policies` JOIN shelliz_products on shelliz_products.record_id=shelliz_policies.product_id WHERE (`shelliz_policies`.`policy_start_date`) BETWEEN $start_date AND $end_date GROUP BY shelliz_products.product_name ORDER BY shelliz_policies.policy_start_date ASC;
        ");
        
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_policy_uptake_stat_sav($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $the_month = $conn->quote($data['the_month']);
        $the_year = $conn->quote($data['the_year']);
        $product_name = $conn->quote($data['product_name']);

        $query = ("SELECT COUNT(shelliz_products.product_name) AS policy_count, shelliz_products.product_name, CONCAT( DATE_FORMAT(shelliz_policies.policy_start_date, '%b'), '-', YEAR(shelliz_policies.policy_start_date))as era

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on shelliz_products.record_id=shelliz_policies.product_id 
        
        WHERE  MONTH(`shelliz_policies`.`policy_start_date`) = $the_month AND YEAR(`shelliz_policies`.`policy_start_date`) = $the_year  AND (`shelliz_products`.`product_name`) = $product_name 
        
        GROUP BY shelliz_products.product_name,shelliz_policies.policy_start_date

        ORDER BY shelliz_policies.policy_start_date ASC ;");
        // echo $query;
        // exit;
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_product_claims_stat_product_list($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $start_date = $conn->quote($data['start_date']);
        $end_date = $conn->quote($data['end_date']);

        $query = ("  
        
        SELECT DISTINCT(shelliz_products.product_name) 

        FROM `shelliz_claims` 

        JOIN shelliz_policies ON shelliz_claims.policy_id=shelliz_claims.policy_id
        JOIN shelliz_products ON shelliz_policies.product_id=shelliz_products.record_id

        WHERE (`shelliz_claims`.`on_date`) BETWEEN  $start_date AND $end_date

        GROUP BY shelliz_products.product_name 
        ORDER BY shelliz_claims.on_date ASC;
        
        ");
        
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    
    public function get_prod_claim_stat_sav($data)
    {
        //var_dump($data);
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $the_month = $conn->quote($data['the_month']);
        $the_year = $conn->quote($data['the_year']);
        $product_name = $conn->quote($data['product_name']);

        $query = ("SELECT COUNT(shelliz_products.product_name) AS policy_count, shelliz_products.product_name, CONCAT( DATE_FORMAT(shelliz_policies.policy_start_date, '%b'), '-', YEAR(shelliz_policies.policy_start_date))as era

        FROM `shelliz_claims` 

        LEFT JOIN shelliz_policies ON shelliz_claims.policy_id  = shelliz_policies.record_id
        JOIN shelliz_products ON shelliz_policies.product_id=shelliz_products.record_id 

        WHERE MONTH(`shelliz_claims`.`on_date`) = $the_month AND YEAR(`shelliz_claims`.`on_date`) = $the_year AND (`shelliz_products`.`product_name`) = $product_name 

        
        GROUP BY shelliz_products.product_name,shelliz_policies.policy_start_date

        ORDER BY shelliz_policies.policy_start_date ASC ;");
        // echo $query;
        // exit;
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
}
