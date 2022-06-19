<?php

namespace App\Repository;

use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Clients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clients[]    findAll()
 * @method Clients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clients::class);
    }

    // /**
    //  * @return Clients[] Returns an array of Clients objects
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
    public function findOneBySomeField($value): ?Clients
    {
    return $this->createQueryBuilder('c')
    ->andWhere('c.exampleField = :val')
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
        // var_dump($conn);die;

        $email = $conn->quote($email);
        $query = ('SELECT * FROM `clients` WHERE LOWER(`email_address`)=LOWER(' . $email . ');');

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
            if ($results[0]['role'] == 2) {
                $retVal = array(
                    'status' => 'fail',
                    'msg' => 'Your profile is still under review.',
                );
                return $retVal;
            }
            if ($results[0]['password'] == $password) {

                if ($results[0]['profile_picture'] == '' || $results[0]['profile_picture'] == '#') {
                    $profpic = 'assets/images/users/16.jpg';
                } else {
                    $profpic = $results[0]['profile_picture'];
                }
                $retVal = array(
                    'status' => 'ok',
                    'retval' => array(
                        'uuid' => $results[0]['record_id'],
                        'username' => $results[0]['user_name'],
                        'email' => $results[0]['email_address'],
                        'role' => $results[0]['role'],
                        'profpic' => $profpic,
                    ),
                );
                return $retVal;
            } else {
                $retVal = array(
                    'status' => 'fail',
                    'msg' => 'invalid credentials',
                );
                return $retVal;
            }
        }
    }

    public function confirm_not_already_registered($email)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $email = $conn->quote($email);

        $query = ('SELECT * FROM `clients` WHERE LOWER(`email_address`) = LOWER(' . $email . ');');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function add_login_info($user)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_name = $conn->quote($user['user_name']);
        $user_email = $conn->quote($user['user_email']);
        $password = $conn->quote($user['password']);
        $mobile_number = $conn->quote($user['mobile_number']);
        $OTP = $conn->quote($user['OTP']);

        $query = ('INSERT INTO `clients`( `user_name`, `email_address`, `phone`, `password`, `is_active`, `temp_otp`) VALUES (' . $user['user_acc'] . ',' . $user_name . ',' . $user_work_mail . ',' . $user_alt_mail . ',' . $password . ',' . $user_department . ',0,' . $OTP . ');');

        $sth = $conn->prepare($query);
        $sth->execute();
        $rowsAffected = $conn->lastInsertId();

        return $rowsAffected;
    }

    public function verify_registration_reset($work_email)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $work_email = $conn->quote($work_email);
        $query = ('SELECT * FROM `clients` WHERE LOWER(`email_address`) = LOWER(' . $work_email . ');');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function verify_registration_reset_by_id($record_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $query = ('SELECT * FROM `clients` WHERE `record_id` = ' . $record_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function update_user_password($save_data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $work_email = $conn->quote($save_data['work_email']);
        $ps1 = $conn->quote($save_data['ps1']);
        $OTP = $conn->quote($save_data['OTP']);

        $query = ('UPDATE `clients` SET `password`=' . $ps1 . ', `is_active`=0,`temp_otp`=' . $OTP . ',`otp_time`=NOW() WHERE `email_address`=' . $work_email . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function update_user_password_authenticated($save_data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $work_email = $conn->quote($save_data['work_email']);
        $ps1 = $conn->quote($save_data['ps1']);
        $OTP = $conn->quote($save_data['OTP']);

        $query = ('UPDATE `clients` SET `password`=' . $ps1 . ', `is_active`=1,`temp_otp`=' . $OTP . ',`otp_time`=NOW() WHERE `email_address`=' . $work_email . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function update_user_profile_picture($file_data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $record_id = $conn->quote($file_data['record_id']);
        $the_file_path = $conn->quote($file_data['file_path']);

        $query = ('UPDATE `clients` SET `profile_picture`=' . $the_file_path . ' WHERE `record_id`=' . $record_id . '');

        $sth = $conn->prepare($query);
        $sth->execute();
        $rowsAffected = $conn->lastInsertId();

        return $rowsAffected;
    }

    public function get_acc_otp($work_email)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $work_email = $conn->quote($work_email);

        $query = ('SELECT `temp_otp`, `otp_time` FROM `clients` WHERE LOWER(`email_address`) = LOWER(' . $work_email . ');');
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function activate_acc_otp($work_email)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $work_email = $conn->quote($work_email);

        $query = ('UPDATE `clients` SET `is_active`=1  WHERE LOWER(`email_address`)=LOWER(' . $work_email . ');');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function get_client_profile($user_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT *
        FROM `clients`
        WHERE clients.record_id=' . $user_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_client_profile_extra($user_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `client_profile_data`.`national_id`,`client_profile_data`.`pin`,`client_profile_data`.`address`,`client_profile_data`.`city`,`client_profile_data`.`postal_code`,`client_profile_data`.`country`,`countries`.`name` AS country_name,`client_profile_data`.`website`,`client_profile_data`.`facebook`,`client_profile_data`.`twitter`,`client_profile_data`.`instagram` FROM `client_profile_data`
        JOIN countries ON `client_profile_data`.`country`=`countries`.id
        WHERE client_profile_data.client_id =' . $user_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_client_bank_details($user_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `record_id`, `bank_name`, `bank_branch`, `account_number`, `status`
        FROM `client_bank_details`
        WHERE client_bank_details.client_id =' . $user_id . ' ;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_country_list()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT *
        FROM `countries`;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function update_client_profile($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $client_full_name = $conn->quote($user_details['client_full_name']);
        $client_phone = $conn->quote($user_details['client_phone']);
        $address = $conn->quote($user_details['address']);
        $id_number = $conn->quote($user_details['id_number']);
        $pin = $conn->quote($user_details['pin']);
        $city = $conn->quote($user_details['city']);
        $postal_code = $conn->quote($user_details['postal_code']);
        $country = $conn->quote($user_details['country']);

        $query = ('UPDATE `clients` SET `user_name`=' . $client_full_name . ',`phone`=' . $client_phone . ',`modified_date`=NOW() WHERE `record_id`=' . $user_id . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function update_client_extra_profile($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $client_full_name = $conn->quote($user_details['client_full_name']);
        $client_phone = $conn->quote($user_details['client_phone']);
        $address = $conn->quote($user_details['address']);
        $id_number = $conn->quote($user_details['id_number']);
        $pin = $conn->quote($user_details['pin']);
        $city = $conn->quote($user_details['city']);
        $postal_code = $conn->quote($user_details['postal_code']);
        $country = $conn->quote($user_details['country']);

        $query = ('REPLACE INTO `client_profile_data` (`client_id`,`national_id`,`pin`,`address`,`city`,`postal_code`,`country`) VALUES(' . $user_id . ', ' . $id_number . ', ' . $pin . ',' . $address . ',' . $city . ',' . $postal_code . ',' . $country . ');');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function save_bank_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $bank_name = $conn->quote($user_details['bank_name']);
        $bank_branch = $conn->quote($user_details['bank_branch']);
        $account_number = $conn->quote($user_details['account_number']);

        $query = ('INSERT INTO `client_bank_details`(`client_id`, `bank_name`, `bank_branch`, `account_number`, `status`) VALUES (' . $user_id . ',' . $bank_name . ',' . $bank_branch . ',' . $account_number . ',1);');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function check_bank_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $bank_name = $conn->quote($user_details['bank_name']);
        $bank_branch = $conn->quote($user_details['bank_branch']);
        $account_number = $conn->quote($user_details['account_number']);

        $query = ('SELECT * FROM `client_bank_details` WHERE `client_id`=' . $user_id . ' AND LOWER(`bank_name`)=LOWER(' . $bank_name . ') AND  LOWER(`account_number`)=LOWER(' . $account_number . ');');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function update_bank_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $bank_name = $conn->quote($user_details['bank_name']);
        $bank_branch = $conn->quote($user_details['bank_branch']);
        $account_number = $conn->quote($user_details['account_number']);

        $query = ('UPDATE `client_bank_details` SET `bank_name`=' . $bank_name . ',`bank_branch`=' . $bank_branch . ',`account_number`=' . $account_number . ' WHERE `client_id`=' . $user_id . ' and LOWER(`bank_name`)=LOWER(' . $bank_name . ') AND LOWER(`account_number`)=LOWER(' . $account_number . ');');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function get_bank_details_by_id($attr_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $attr_id = $conn->quote($attr_id);

        $query = ('SELECT `record_id`, `bank_name`, `bank_branch`, `account_number`, `status`
        FROM `client_bank_details`
        WHERE client_bank_details.record_id =' . $attr_id . ' ;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function update_bank_details_by_id($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $bank_id_up = $user_details['bank_id_up'];
        $user_id = ($user_details['userid']);
        $bank_name = $conn->quote($user_details['bank_name']);
        $bank_branch = $conn->quote($user_details['bank_branch']);
        $account_number = $conn->quote($user_details['account_number']);

        $query = ('UPDATE `client_bank_details` SET `bank_name`=' . $bank_name . ',`bank_branch`=' . $bank_branch . ',`account_number`=' . $account_number . ' WHERE `record_id`=' . $bank_id_up . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function update_bank_status($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $query = ('UPDATE `client_bank_details` SET `status`=' . $data['click_act'] . ' WHERE `record_id`=' . $data['record_id'] . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function update_client_status($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $query = ('UPDATE `clients` SET `is_active`=' . $data['click_act'] . ' WHERE `record_id`=' . $data['record_id'] . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function get_all_clients()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT * FROM `clients`;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_client_profile_data($attr_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);
        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT * FROM `client_profile_data` WHERE client_id=' . $attr_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_all_products()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_products`.`record_id`,`shelliz_products`.`product_name`,`shelliz_products`.`status`,`shelliz_products`.created_on,`shelliz_product_types`.`product_type_name`
        FROM `shelliz_products`
        JOIN `shelliz_product_types` ON `shelliz_products`.`shelliz_product_type`=`shelliz_product_types`.`record_id`;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function update_product_status($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $query = ('UPDATE `shelliz_products` SET `status`=' . $data['click_act'] . ' WHERE `record_id`=' . $data['record_id'] . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function get_policy_type_list()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT * FROM `shelliz_product_types` WHERE status=1;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_ticket_type_list()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT * FROM `shelliz_ticket_types` WHERE `status`=1 ORDER BY ticket_type_description DESC;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function check_product_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $product_name = $conn->quote($user_details['product_name']);
        $product_type = ($user_details['product_type']);

        $query = ('SELECT * FROM `shelliz_products` WHERE LOWER(`product_name`)=LOWER(' . $product_name . ') AND  `shelliz_product_type`=' . $product_type . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function save_product_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $product_name = $conn->quote($user_details['product_name']);
        $product_type = $conn->quote($user_details['product_type']);
        $description = $conn->quote($user_details['description']);

        $query = ('INSERT INTO `shelliz_products`(`product_name`, `shelliz_product_type`, `description`) VALUES (' . $product_name . ',' . $product_type . ',' . $description . ');');

        $sth = $conn->prepare($query);
        $sth->execute();

        $rowsAffected = $conn->lastInsertId();

        return $rowsAffected;
    }

    public function update_product_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $product_name = $conn->quote($user_details['product_name']);
        $product_type = $conn->quote($user_details['product_type']);
        $description = $conn->quote($user_details['description']);

        $query = ('UPDATE `shelliz_products` SET `product_name`=' . $product_name . ',`shelliz_product_type`=' . $product_type . ',`description`=' . $description . ' WHERE
         LOWER(`product_name`)=LOWER(' . $product_name . ') AND `shelliz_product_type`=' . $product_type . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function update_product_details_byid($product_id, $user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $product_name = $conn->quote($user_details['product_name']);
        $product_type = $conn->quote($user_details['product_type']);
        $description = $conn->quote($user_details['description']);

        $query = ('UPDATE `shelliz_products` SET `product_name`=' . $product_name . ',`shelliz_product_type`=' . $product_type . ',`description`=' . $description . ' WHERE
        `record_id`=' . $product_id . ' ;');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function update_product_icon($file_data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $record_id = $conn->quote($file_data['record_id']);
        $the_file_path = $conn->quote($file_data['file_path']);

        $query = ('UPDATE `shelliz_products` SET `icon_path`=' . $the_file_path . ' WHERE `record_id`=' . $record_id . '');

        $sth = $conn->prepare($query);
        $sth->execute();
        $rowsAffected = $conn->lastInsertId();

        return $rowsAffected;
    }

    public function get_product_details_by_id($attr_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_products`.`record_id`,`shelliz_products`.`product_name`,`shelliz_products`.`description`,`shelliz_products`.`icon_path`,`shelliz_products`.`status`,`shelliz_products`.created_on,`shelliz_product_types`.`product_type_name`
        FROM `shelliz_products`
        JOIN `shelliz_product_types` ON `shelliz_products`.`shelliz_product_type`=`shelliz_product_types`.`record_id`
        WHERE shelliz_products.record_id =' . $attr_id . ' ;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_product_list()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT * FROM `shelliz_products` WHERE `status`=1;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function check_cb_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $call_back_product_id = $conn->quote($user_details['call_back_product_id']);
        $call_back_date = $conn->quote($user_details['call_back_date']);

        $query = ('SELECT * FROM `shelliz_callback_requests` WHERE `client_id`=' . $user_id . ' AND `product_id`=' . $call_back_product_id . '');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function save_CB_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $call_back_product_id = $conn->quote($user_details['call_back_product_id']);
        $call_back_date = $conn->quote($user_details['call_back_date']);

        $query = ('INSERT INTO `shelliz_callback_requests`(`client_id`, `product_id`, `call_back_time`) VALUES (' . $user_id . ',' . $call_back_product_id . ',' . $call_back_date . ');');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function save_ticket_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $ticket_type = $conn->quote($user_details['ticket_type']);
        $ticket_subject = $conn->quote($user_details['ticket_subject']);
        $message = $conn->quote($user_details['message']);

        $query = ('INSERT INTO `shelliz_tickets`(`client_id`, `ticket_type`, `ticket_subject`, `message`) VALUES ('.$user_id.','.$ticket_type.','.$ticket_subject.','.$message.')');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function update_CB_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $user_id = ($user_details['userid']);
        $call_back_product_id = $conn->quote($user_details['call_back_product_id']);
        $call_back_date = $conn->quote($user_details['call_back_date']);

        $query = ('UPDATE `shelliz_callback_requests` SET `call_back_time`=' . $call_back_date . ' WHERE `client_id`=' . $user_id . ' and `product_id`=' . $call_back_product_id . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function get_all_client_call_back()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_callback_requests`.`client_id`,`shelliz_callback_requests`.`record_id`,`shelliz_callback_requests`.`product_id`,`shelliz_callback_requests`.`call_back_time`,shelliz_callback_requests.`status`, clients.user_name, shelliz_products.product_name

        FROM `shelliz_callback_requests`

        JOIN clients on clients.record_id = `shelliz_callback_requests`.`client_id`
        JOIN shelliz_products on shelliz_callback_requests.product_id= shelliz_products.record_id');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_all_client_call_back_by_id($user_id_c)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_callback_requests`.`client_id`,`shelliz_callback_requests`.`record_id`,`shelliz_callback_requests`.`product_id`,`shelliz_callback_requests`.`call_back_time`,shelliz_callback_requests.`status`, clients.user_name, shelliz_products.product_name

        FROM `shelliz_callback_requests`

        JOIN clients on clients.record_id = `shelliz_callback_requests`.`client_id`
        JOIN shelliz_products on shelliz_callback_requests.product_id= shelliz_products.record_id

        WHERE `shelliz_callback_requests`.`client_id`=' . $user_id_c . '
        ');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_my_tickets($userid)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $userid = $conn->quote($userid);

        $query = ('SELECT clients.user_name,`shelliz_tickets`.`record_id`,shelliz_ticket_types.ticket_type_description,`shelliz_tickets`.`client_id`,`shelliz_tickets`.`ticket_type`,`shelliz_tickets`.`ticket_subject`,`shelliz_tickets`.`message`,`shelliz_tickets`.`stage`,`shelliz_tickets`.`on_date` 

        FROM `shelliz_tickets` 
        
        JOIN clients on `shelliz_tickets`.`client_id` = clients.record_id
        JOIN shelliz_ticket_types ON `shelliz_tickets`.`ticket_type` = shelliz_ticket_types.record_id
        
        WHERE `shelliz_tickets`.`client_id`=' . $userid . ' 
        
        ORDER BY shelliz_tickets.record_id DESC;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    
    public function get_all_tickets()
    {
        $conn = $this->getEntityManager()
            ->getConnection();


        $query = ('SELECT clients.user_name,`shelliz_tickets`.`record_id`,shelliz_ticket_types.ticket_type_description,`shelliz_tickets`.`client_id`,`shelliz_tickets`.`ticket_type`,`shelliz_tickets`.`ticket_subject`,`shelliz_tickets`.`message`,`shelliz_tickets`.`stage`,`shelliz_tickets`.`on_date` 

        FROM `shelliz_tickets` 
        
        JOIN clients on `shelliz_tickets`.`client_id` = clients.record_id
        JOIN shelliz_ticket_types ON `shelliz_tickets`.`ticket_type` = shelliz_ticket_types.record_id
        
        ORDER BY shelliz_tickets.record_id DESC;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_all_tickets_by_Client($user_id_c)
    {
        $conn = $this->getEntityManager()
            ->getConnection();


        $query = ('SELECT clients.user_name,`shelliz_tickets`.`record_id`,shelliz_ticket_types.ticket_type_description,`shelliz_tickets`.`client_id`,`shelliz_tickets`.`ticket_type`,`shelliz_tickets`.`ticket_subject`,`shelliz_tickets`.`message`,`shelliz_tickets`.`stage`,`shelliz_tickets`.`on_date` 

        FROM `shelliz_tickets` 
        
        JOIN clients on `shelliz_tickets`.`client_id` = clients.record_id
        JOIN shelliz_ticket_types ON `shelliz_tickets`.`ticket_type` = shelliz_ticket_types.record_id
        
        WHERE `shelliz_tickets`.`client_id`=' . $user_id_c . ' 

        ORDER BY shelliz_tickets.record_id DESC;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_lead_client_profile($user_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `client_id` FROM `shelliz_callback_requests` WHERE `record_id`=' . $user_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_lead_product_id($record_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `product_id` FROM `shelliz_callback_requests` WHERE `record_id`='.$record_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_lead_product_details($record_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `shelliz_products`.`record_id`,`shelliz_products`.`product_name`,`shelliz_products`.`shelliz_product_type`,`shelliz_products`.`description`,`shelliz_products`.`status`,`shelliz_products`.`icon_path`,shelliz_product_types.product_type_name 

        FROM `shelliz_products` 
                
        JOIN shelliz_product_types ON shelliz_product_types.record_id=shelliz_products.shelliz_product_type
                
        WHERE `shelliz_products`.`record_id`='.$record_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }    

    public function get_policy_frequency_list()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `record_id`,`frequency_name` FROM `shelliz_renewal_frequency` WHERE status=1;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_lead_details($transaction_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT * FROM `shelliz_callback_requests` WHERE `record_id`='.$transaction_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function check_ext_policy($transaction_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT * FROM `shelliz_policies` WHERE `parent_lead`='.$transaction_id['transaction_id'].';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function create_policy($prod_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);
        $client_id = ($prod_details['client_id']);
        $product_id = ($prod_details['product_id']);
        $transaction_id = ($prod_details['transaction_id']);
        $description = $conn->quote($prod_details['description']);
        $cov_start_date = $conn->quote($prod_details['cov_start_date']);
        $cov_end_date = $conn->quote($prod_details['cov_end_date']);
        $renew_frequency=$prod_details['renew_frequency'];

        $query = ('INSERT INTO `shelliz_policies`(`client_id`, `product_id`, `policy_start_date`, `policy_end_date`, `renewal_frequency`, `extra_info`,`parent_lead`) VALUES ('.$client_id.','.$product_id.','.$cov_start_date.','.$cov_end_date.','.$renew_frequency.','.$description.','.$transaction_id.');');
        // echo $query;
        // exit;
        $sth = $conn->prepare($query);
        $sth->execute();
        $rowsAffected = $conn->lastInsertId();

        return $rowsAffected;
    }

    public function save_policy_beneficiary($prod_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);
        $policy_id = ($prod_details['policy_id']);
        $beneficiary_name = $conn->quote($prod_details['beneficiary_name']);
        $relationship = $conn->quote($prod_details['relationship']);

        $query = ('INSERT INTO `shelliz_beneficiaries`(`policy_id`, `beneficiary_name`, `beneficiary_relationship`) VALUES ('.$policy_id.','.$beneficiary_name.','.$relationship.');');
        // echo $query;
        // exit;
        $sth = $conn->prepare($query);
        $sth->execute();
        $rowsAffected = $conn->lastInsertId();

        return $rowsAffected;
    }

    public function convert_lead($transaction_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);
        $transaction_id = ($transaction_id);

        $query = ('UPDATE `shelliz_callback_requests` SET `status`=2 WHERE `record_id`='.$transaction_id.';');
        // echo $query;
        // exit;
        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function get_all_policies()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_policies`.`record_id`,`shelliz_policies`.`client_id`,`shelliz_policies`.`product_id`,`shelliz_policies`.`policy_start_date`,`shelliz_policies`.`policy_end_date`,`shelliz_policies`.`renewal_frequency`,`shelliz_policies`.`extra_info`,`shelliz_policies`.`parent_lead`,`shelliz_policies`.`book_date`,clients.user_name,shelliz_products.product_name,shelliz_renewal_frequency.frequency_name

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on `shelliz_policies`.`product_id`=shelliz_products.record_id
        JOIN clients ON shelliz_policies.client_id=clients.record_id
        JOIN shelliz_renewal_frequency on shelliz_policies.renewal_frequency=shelliz_renewal_frequency.record_id;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
        
    public function get_policy_client_profile($user_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `client_id` FROM `shelliz_policies` WHERE `record_id`=' . $user_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    
    public function get_policy_by_id($record_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_policies`.`record_id`,`shelliz_policies`.`client_id`,`shelliz_policies`.`product_id`,`shelliz_policies`.`policy_start_date`,`shelliz_policies`.`policy_end_date`,`shelliz_policies`.`renewal_frequency`,`shelliz_policies`.`extra_info`,`shelliz_policies`.`parent_lead`,`shelliz_policies`.`book_date`,clients.user_name,shelliz_products.product_name,shelliz_renewal_frequency.frequency_name

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on `shelliz_policies`.`product_id`=shelliz_products.record_id
        JOIN clients ON shelliz_policies.client_id=clients.record_id
        JOIN shelliz_renewal_frequency on shelliz_policies.renewal_frequency=shelliz_renewal_frequency.record_id WHERE shelliz_policies.record_id='.$record_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_policy_beneficiary($record_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT * FROM `shelliz_beneficiaries` WHERE `policy_id`='.$record_id.';');
        // echo($query);
        // exit;
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_policy_by_client_id($record_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_policies`.`record_id`,`shelliz_policies`.`client_id`,`shelliz_policies`.`product_id`,`shelliz_policies`.`policy_start_date`,`shelliz_policies`.`policy_end_date`,`shelliz_policies`.`renewal_frequency`,`shelliz_policies`.`extra_info`,`shelliz_policies`.`parent_lead`,`shelliz_policies`.`book_date`,clients.user_name,shelliz_products.product_name,shelliz_renewal_frequency.frequency_name

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on `shelliz_policies`.`product_id`=shelliz_products.record_id
        JOIN clients ON shelliz_policies.client_id=clients.record_id
        JOIN shelliz_renewal_frequency on shelliz_policies.renewal_frequency=shelliz_renewal_frequency.record_id WHERE shelliz_policies.client_id='.$record_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_client_count()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT COUNT(*) AS client_count FROM `clients`;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_policy_count()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT COUNT(*) AS policy_count FROM `shelliz_policies`;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_ticket_count()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT COUNT(*) AS ticket_count FROM `shelliz_tickets`;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
        
    public function get_client_policy_client_profile($userid)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `client_id` FROM `shelliz_policies` WHERE `client_id`=' . $userid . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_client_policy_by_id($userid,$record_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_policies`.`record_id`,`shelliz_policies`.`client_id`,`shelliz_policies`.`product_id`,`shelliz_policies`.`policy_start_date`,`shelliz_policies`.`policy_end_date`,`shelliz_policies`.`renewal_frequency`,`shelliz_policies`.`extra_info`,`shelliz_policies`.`parent_lead`,`shelliz_policies`.`book_date`,clients.user_name,shelliz_products.product_name,shelliz_renewal_frequency.frequency_name

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on `shelliz_policies`.`product_id`=shelliz_products.record_id
        JOIN clients ON shelliz_policies.client_id=clients.record_id
        JOIN shelliz_renewal_frequency on shelliz_policies.renewal_frequency=shelliz_renewal_frequency.record_id WHERE shelliz_policies.record_id='.$record_id.' AND shelliz_policies.client_id='.$userid.' ;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_client_policy_by_client_id($record_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_policies`.`record_id`,`shelliz_policies`.`client_id`,`shelliz_policies`.`product_id`,`shelliz_policies`.`policy_start_date`,`shelliz_policies`.`policy_end_date`,`shelliz_policies`.`renewal_frequency`,`shelliz_policies`.`extra_info`,`shelliz_policies`.`parent_lead`,`shelliz_policies`.`book_date`,clients.user_name,shelliz_products.product_name,shelliz_renewal_frequency.frequency_name

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on `shelliz_policies`.`product_id`=shelliz_products.record_id
        JOIN clients ON shelliz_policies.client_id=clients.record_id
        JOIN shelliz_renewal_frequency on shelliz_policies.renewal_frequency=shelliz_renewal_frequency.record_id WHERE shelliz_policies.client_id='.$record_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_all_client_policies_ovr_1($userid)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_policies`.`record_id`,`shelliz_policies`.`client_id`,`shelliz_policies`.`product_id`,`shelliz_policies`.`policy_start_date`,`shelliz_policies`.`policy_end_date`,`shelliz_policies`.`renewal_frequency`,`shelliz_policies`.`extra_info`,`shelliz_policies`.`parent_lead`,`shelliz_policies`.`book_date`,clients.user_name,shelliz_products.product_name,shelliz_renewal_frequency.frequency_name

        FROM `shelliz_policies` 
        
        JOIN shelliz_products on `shelliz_policies`.`product_id`=shelliz_products.record_id
        JOIN clients ON shelliz_policies.client_id=clients.record_id
        JOIN shelliz_renewal_frequency on shelliz_policies.renewal_frequency=shelliz_renewal_frequency.record_id WHERE shelliz_policies.client_id='.$userid.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_client_ticket_count($client_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT COUNT(*) AS ticket_count FROM `shelliz_tickets` WHERE client_id='.$client_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function get_client_beneficiary_count($client_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT COUNT(*) AS beneficiary_count FROM `shelliz_beneficiaries` 
        JOIN shelliz_policies on shelliz_beneficiaries.policy_id=shelliz_policies.record_id
        WHERE shelliz_policies.client_id='.$client_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
  
    public function get_client_policy_count($client_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT COUNT(*) AS policy_count FROM `shelliz_policies` WHERE `client_id`='.$client_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }    

    public function check_claim_details($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $policy_id = ($user_details['policy_id']);
        
        $query = ('SELECT * FROM `shelliz_claims` WHERE `policy_id`=' . $policy_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function save_claim($user_details)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $policy_id = ($user_details['policy_id']);
        $incident_date = $conn->quote($user_details['incident_date']);
        $description = $conn->quote($user_details['description']);
        

        $query = ('INSERT INTO `shelliz_claims`(`policy_id`, `on_date`, `description`, `claim_status`) VALUES ('.$policy_id.','.$incident_date.','.$description.',1);');

        $sth = $conn->prepare($query);
        $sth->execute();

        $rowsAffected = $conn->lastInsertId();

        return $rowsAffected;
    }
   
    public function save_claim_images($file_data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $record_id = $conn->quote($file_data['record_id']);
        $the_file_path = $conn->quote($file_data['file_path']);

        $query = ('INSERT INTO `shelliz_claim_images`(`claim_id`, `image_path`) VALUES ('.$record_id.','.$the_file_path.')');

        $sth = $conn->prepare($query);
        $sth->execute();
        $rowsAffected = $conn->lastInsertId();

        return $rowsAffected;
    }

    public function get_client_claim_data($client_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_claims`.`record_id`,`shelliz_claims`.`policy_id`,shelliz_products.product_name,`shelliz_claims`.`on_date`,`shelliz_claims`.`description`,`shelliz_claims`.`claim_status`,shelliz_claim_status.status_description 

        FROM `shelliz_claims` 
        
        JOIN shelliz_policies ON shelliz_claims.policy_id=shelliz_policies.record_id
        JOIN shelliz_products ON shelliz_policies.product_id=shelliz_products.record_id
		JOIN shelliz_claim_status on shelliz_claims.claim_status=shelliz_claim_status.record_id

        WHERE shelliz_policies.client_id='.$client_id.';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_admin_claim_data()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        //$attr_id = $conn->quote($attr_id);

        $query = ('SELECT `shelliz_claims`.`record_id`,clients.record_id AS client_id,clients.user_name,`shelliz_claims`.`policy_id`,shelliz_products.product_name,`shelliz_claims`.`on_date`,`shelliz_claims`.`description`,`shelliz_claims`.`claim_status`,shelliz_claim_status.status_description 

        FROM `shelliz_claims` 
        
        JOIN shelliz_policies ON shelliz_claims.policy_id=shelliz_policies.record_id
        JOIN shelliz_products ON shelliz_policies.product_id=shelliz_products.record_id
		JOIN shelliz_claim_status on shelliz_claims.claim_status=shelliz_claim_status.record_id
        JOIN clients on shelliz_policies.client_id=clients.record_id

        ORDER BY shelliz_claims.record_id DESC;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
            
    public function get_policy_claim_client_profile($user_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT shelliz_policies.client_id
        FROM `shelliz_claims`
        JOIN shelliz_policies ON shelliz_claims.policy_id=shelliz_policies.record_id
        WHERE shelliz_claims.`record_id`=' . $user_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
        
    public function get_policy_claim_policy_profile($user_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `policy_id` FROM `shelliz_claims` WHERE `record_id`=' . $user_id . ';');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
    
    public function get_client_claim_details($user_id)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        //$email = $conn->quote($email);

        $query = ('SELECT `shelliz_claims`.`record_id`,clients.record_id AS client_id,clients.user_name,`shelliz_claims`.`policy_id`,shelliz_products.product_name,`shelliz_claims`.`on_date`,`shelliz_claims`.`description`,`shelliz_claims`.`claim_status`,shelliz_claim_status.status_description 

        FROM `shelliz_claims` 
        
        JOIN shelliz_policies ON shelliz_claims.policy_id=shelliz_policies.record_id
        JOIN shelliz_products ON shelliz_policies.product_id=shelliz_products.record_id
		JOIN shelliz_claim_status on shelliz_claims.claim_status=shelliz_claim_status.record_id
        JOIN clients on shelliz_policies.client_id=clients.record_id

        WHERE shelliz_claims.record_id=' . $user_id . ' ;');

        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }

    public function update_claim_status($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $query = ('UPDATE `shelliz_claims` SET `claim_status`=' . $data['click_act'] . ' WHERE `record_id`=' . $data['record_id'] . ';');

        $sth = $conn->prepare($query);
        $sth->execute();

        return true;
    }

    public function get_user_stat($data)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $start_date = $conn->quote($data['start_date']);
        $end_date = $conn->quote($data['end_date']);

        $query = ("SELECT COUNT(*) AS stat_count,YEAR(date_of_joining) as year_resp, DATE_FORMAT(date_of_joining, '%b') As month_resp FROM `clients` WHERE (`date_of_joining`) BETWEEN $start_date AND $end_date GROUP BY YEAR(date_of_joining), MONTH(date_of_joining);");
        // echo $query;
        // exit;
        $sth = $conn->prepare($query);
        $result = $sth->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
}
