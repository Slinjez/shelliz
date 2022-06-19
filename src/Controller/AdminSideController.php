<?php

namespace App\Controller;

ini_set("upload_max_filesize", "300M");

use App\Controller\SessionController;
use App\Entity\Admins;
use App\Entity\Clients;
use App\Entity\ShellizPolicies;
use App\Service\OpsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isEmpty;

class AdminSideController extends AbstractController
{
    private $session;
    protected $projectDir;
    public function __construct(SessionInterface $session, KernelInterface $kernel)
    {
        $this->session = $session;
        $this->projectDir = $kernel->getProjectDir();
    }
    /**
     * @Route("/loginAction-adm", name="Shared loginAction-adm")
     */
    public function loginAction_adm()
    {
        $sescontrol = new SessionController;
        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($email == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your email address.";
            return $this->json($respondWith);
            exit;
        } else if ($password == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your password.";
            return $this->json($respondWith);
            exit;
        } else {
            $password = hash('ripemd160', $password);
            $repository = $this->getDoctrine()
                ->getRepository(Admins::class);
            $validity = $repository->login($email, $password);
            $retval = array();
            if ($validity['status'] != 'ok') {
                $retval = array(
                    'status' => 'fail',
                    'messages' => $validity['msg'],
                );
                return $this->json($retval);
            } else {
                if ($validity['retval']['profpic'] == '') {
                    $pic = 'assets/images/users/16.jpg';
                } else {
                    $pic = $validity['retval']['profpic'];
                }
                $userid = $validity['retval']['uuid'];
                $username = $validity['retval']['username'];
                $role = $validity['retval']['role'];
                $profpic = $pic;
                $token = $sescontrol->getJwt($userid, $role);
                $this->session->set('dsladminuname', $username);
                $this->session->set('dsladminuid', $userid);
                $this->session->set('token', $token);
                $path = '';
                if ($role == 1) {
                    $path = '/admin-dash';
                } else if ($role == 2) {
                    $path = '/admin-dash';
                }
                $result = array(
                    'status' => 'ok',
                    'token' => $token,
                    'username' => $username,
                    'profpic' => $profpic,
                    'path' => $path,
                );
            }
            if ($result['status'] != 'ok') {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = $result['msg'];
                return $this->json($respondWith);
            } else {
                $respondWith['status'] = 'ok';
                $respondWith['messages'] = 'Welcome ' . $result["username"];
                $respondWith['vars'] = $result;
                return $this->json($respondWith);
            }
        }
    }
    /**
     * @Route("/admin-fetch-clients", name="admin-fetch-clients")
     */
    public function admin_fetch_clients()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_all_clients();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $user_name = $result['user_name'];
            $email_address = $result['email_address'];
            $phone = $result['phone'];
            $status = $result['is_active'];
            $created_date = $result['date_of_joining'];
            $display_date = date('dS-M Y', strtotime($created_date));
            $id = '-';
            $pin = '-';
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $profile_details = $repository->get_client_profile_data($record_id);
            if (!empty($profile_details)) {
                $id = $profile_details[0]['national_id'];
                $pin = $profile_details[0]['pin'];
            }
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_client_params($record_id, $status);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                $user_name,
                $email_address,
                $phone,
                $id,
                $pin,
                $created_date,
                $unit_ui_display,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/admin-get-profile-details", name="admin-get-profile-details")
     */
    public function admin_get_profile_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid = $token;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_client_profile($userid);
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_extra = $repository->get_client_profile_extra($userid);
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_banking = $repository->get_client_bank_details($userid);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $ops_service_bank_response = $ops_service->get_bank_compiled_params($results_banking);
        unset($results[0]['is_active']);
        unset($results[0]['temp_otp']);
        unset($results[0]['otp_time']);
        unset($results[0]['password']);
        $result_extra_expo = [];
        if (isset($results_extra[0])) {
            $result_extra_expo = $results_extra[0];
        }
        $client_age = floor((time() - strtotime($results[0]['date_of_joining'])) / 31556926);
        $results[0]['client_tenure'] = $client_age;
        $results[0]['results_extra'] = $result_extra_expo;
        $results[0]['results_banking'] = $ops_service_bank_response;
        $results[0]['member_since'] = date('Y-m-d', strtotime($results[0]['date_of_joining']));
        $live_date_diff = $this->getDateDiff($results[0]['date_of_joining'], date("Y-m-d H:i:s"));
        $results[0]['client_tenure_var'] = $live_date_diff;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $results[0];
        return $this->json($respondWith);
    }
    /**
     * @Route("/client-toggle-client-status", name="client-toggle-client-status")
     */
    public function client_toggle_client_status()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $record_id = $_POST['record_id'];
        $click_act = $_POST['click_act'];
        $data_t_save = array(
            'record_id' => $record_id,
            'click_act' => $click_act,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->update_client_status($data_t_save);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Not Updated.";
            return $this->json($respondWith);
        } else {
            $respondWith['status'] = 'ok';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Updated.";
            return $this->json($respondWith);
        }
    }
    /**
     * @Route("/admin-fetch-products", name="admin-fetch-products")
     */
    public function admin_fetch_products()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_all_products();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $product_name = $result['product_name'];
            $product_type_name = $result['product_type_name'];
            $status = $result['status'];
            $created_date = $result['created_on'];
            $display_date = date('dS-M Y', strtotime($created_date));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_product_params($record_id, $status);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                $product_name,
                $product_type_name,
                $created_date,
                $unit_ui_display,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/admin-fetch-callback-requests", name="admin-fetch-callback-requests")
     */
    public function admin_fetch_callback_requests()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_all_client_call_back();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $client_id = $result['client_id'];
            $user_name = $result['user_name'];
            $product_name = $result['product_name'];
            $status = $result['status'];
            $call_back_time = $result['call_back_time'];
            $display_date = date('dS-M Y H:i', strtotime($call_back_time));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_call_back_params($record_id, $status);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                '<a href="/admin-view-client-profile?param=' . $client_id . '"><span class="badge rounded-pill bg-blue mt-2">' . $user_name . '</span></a>',
                $product_name,
                $display_date,
                $unit_ui_display,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/admin-fetch-callback-requests-by-id", name="admin-fetch-callback-requests-by-id")
     */
    public function admin_fetch_callback_requests_by_id()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $user_id_c = $_GET['user-id-c'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_all_client_call_back_by_id($user_id_c);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $client_id = $result['client_id'];
            $user_name = $result['user_name'];
            $product_name = $result['product_name'];
            $status = $result['status'];
            $call_back_time = $result['call_back_time'];
            $display_date = date('dS-M Y H:i', strtotime($call_back_time));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_call_back_params($record_id, $status);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                '<a href="/admin-view-client-profile?param=' . $client_id . '"><span class="badge rounded-pill bg-blue mt-2">' . $user_name . '</span></a>',
                $product_name,
                $display_date,
                $unit_ui_display,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/client-toggle-product-status", name="client-toggle-product-status")
     */
    public function client_toggle_product_status()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $record_id = $_POST['record_id'];
        $click_act = $_POST['click_act'];
        $data_t_save = array(
            'record_id' => $record_id,
            'click_act' => $click_act,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->update_product_status($data_t_save);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Not Updated.";
            return $this->json($respondWith);
        } else {
            $respondWith['status'] = 'ok';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Updated.";
            return $this->json($respondWith);
        }
    }
    /**
     * @Route("/save-product", name="save-product")
     */
    public function save_product()
    {
        /**files */
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $em = $this->getDoctrine()->getManager();
        $storeFolder = '/public/uploads/products/icons/';
        $retrieve_folder = '/uploads/products/icons/';
        $thefilearry = $_FILES;
        $filesystem = new Filesystem();
        $project_directory = $this->projectDir;
        $upl_path = $project_directory . $storeFolder;
        try {
            $resp = $filesystem->mkdir($upl_path, 0777);
        } catch (IOExceptionInterface $exception) {
            $respondWith['status'] = 'fail';
            $respondWith['title'] = "Data Saved";
            $respondWith['messages'] = "An error occurred while creating your directory at " . $exception->getPath();
            $respondWith['data'] = 'error thrown';
        }
        if (isset($_FILES['eventfiles'])) {
            $file_data = $_FILES['eventfiles'];
        }
        $file_path = '';
        /**files */
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "...";
            return $this->json($respondWith);
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $product_name = trim($_POST['product_name']);
        $product_type = trim($_POST['product_type']);
        $description = trim($_POST['description']);
        $update_record_id = trim($_POST['update_record_id']);
        if ($product_name == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your product name.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($product_type == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please select product type.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($description == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter product description.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $prod_details = array(
            'userid' => $userid,
            'product_name' => $product_name,
            'product_type' => $product_type,
            'description' => $description,
        );
        $product_id = 0;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $existing_product = $repository->check_product_details($prod_details);
        if ($update_record_id == 0) {
            if (empty($existing_product)) {
                $repository = $this->getDoctrine()
                    ->getRepository(Clients::class);
                $update_prod_results = $repository->save_product_details($prod_details);
                $product_id = $update_prod_results;
                if ($product_id > 0) {
                    $file_datas = $this->save_local_files($product_id, $storeFolder, $retrieve_folder, $upl_path);
                    if (!empty($file_datas)) {
                        foreach ($file_datas as $file_data) :
                            $file_path = $file_data['file_path'];
                            $repository = $this->getDoctrine()
                                ->getRepository(Clients::class);
                            $repository->update_product_icon($file_data);
                        endforeach;
                    }
                }
                if (!$update_prod_results) {
                    $respondWith['status'] = 'false';
                    $respondWith['title'] = "null";
                    $respondWith['messages'] = "Save failed. Kindly try again later.";
                    $respondWith['data'] = 0;
                    return $this->json($respondWith);
                } else {
                    $respondWith['status'] = 'ok';
                    $respondWith['title'] = "Ok";
                    $respondWith['messages'] = "Saved.";
                    return $this->json($respondWith);
                }
            } else {
                $product_id = $existing_product[0]['record_id'];
                $repository = $this->getDoctrine()
                    ->getRepository(Clients::class);
                $update_prod_results = $repository->update_product_details($prod_details);
                if ($product_id > 0) {
                    $file_datas = $this->save_local_files($product_id, $storeFolder, $retrieve_folder, $upl_path);
                    if (!empty($file_datas)) {
                        foreach ($file_datas as $file_data) :
                            $file_path = $file_data['file_path'];
                            $repository = $this->getDoctrine()
                                ->getRepository(Clients::class);
                            $repository->update_product_icon($file_data);
                        endforeach;
                    }
                }
                if (!$update_prod_results) {
                    $respondWith['status'] = 'false';
                    $respondWith['title'] = "null";
                    $respondWith['messages'] = "Update failed. Kindly try again later.";
                    $respondWith['data'] = 0;
                    return $this->json($respondWith);
                } else {
                    $respondWith['status'] = 'ok';
                    $respondWith['title'] = "Ok";
                    $respondWith['messages'] = "Updated.";
                    return $this->json($respondWith);
                }
            }
        } else {
            $product_id = $update_record_id;
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $update_prod_results = $repository->update_product_details_byid($product_id, $prod_details);
            if ($product_id > 0) {
                $file_datas = $this->save_local_files($product_id, $storeFolder, $retrieve_folder, $upl_path);
                if (!empty($file_datas)) {
                    foreach ($file_datas as $file_data) :
                        $file_path = $file_data['file_path'];
                        $repository = $this->getDoctrine()
                            ->getRepository(Clients::class);
                        $repository->update_product_icon($file_data);
                    endforeach;
                }
            }
            if (!$update_prod_results) {
                $respondWith['status'] = 'false';
                $respondWith['title'] = "null";
                $respondWith['messages'] = "Update failed. Kindly try again later.";
                $respondWith['data'] = 0;
                return $this->json($respondWith);
            } else {
                $respondWith['status'] = 'ok';
                $respondWith['title'] = "Ok";
                $respondWith['messages'] = "Updated.";
                return $this->json($respondWith);
            }
        }
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        return $this->json($respondWith);
    }
    /**
     * @Route("/get-product-details-by-id", name="get-product-details-by-id")
     */
    public function get_product_details_by_id()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $attr_id = $_POST['attr_id'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_products = $repository->get_product_details_by_id($attr_id);
        if (empty($results_products)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $ops_service_prod_response = $ops_service->get_product_compiled_params($results_products);
        $results[0]['results_prod'] = $ops_service_prod_response;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $results[0];
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-fetch-all-tickets", name="admin-fetch-all-tickets")
     */
    public function admin_fetch_all_tickets()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_all_tickets();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $client_id = $result['client_id'];
            $user_name = $result['user_name'];
            $ticket_type_description = $result['ticket_type_description'];
            $ticket_subject = $result['ticket_subject'];
            $message = $result['message'];
            $status = $result['stage'];
            $created_date = $result['on_date'];
            $display_date = date('dS-M Y', strtotime($created_date));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_admin_ticket_params($record_id, $status);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                '<a href="/admin-view-client-profile?param=' . $client_id . '"><span class="badge rounded-pill bg-blue mt-2">' . $user_name . '</span></a>',
                $ticket_subject,
                $ticket_type_description,
                $display_date,
                $unit_ui_display,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/admin-fetch-all-tickets-by-client", name="admin-fetch-all-tickets-by-client")
     */
    public function admin_fetch_all_tickets_by_clients()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $user_id_c = $_GET['user-id-c'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_all_tickets($user_id_c);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $client_id = $result['client_id'];
            $user_name = $result['user_name'];
            $ticket_type_description = $result['ticket_type_description'];
            $ticket_subject = $result['ticket_subject'];
            $message = $result['message'];
            $status = $result['stage'];
            $created_date = $result['on_date'];
            $display_date = date('dS-M Y', strtotime($created_date));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_admin_ticket_params($record_id, $status);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                '<a href="/admin-view-client-profile?param=' . $client_id . '"><span class="badge rounded-pill bg-blue mt-2">' . $user_name . '</span></a>',
                $ticket_subject,
                $ticket_type_description,
                $display_date,
                $unit_ui_display,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/admin-get-lead-profile-details", name="admin-get-lead-profile-details")
     */
    public function admin_get_lead_profile_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid = $token;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $lead_details = $repository->get_lead_client_profile($userid);
        if (empty($lead_details)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $userid = $lead_details[0]['client_id'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_client_profile($userid);
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_extra = $repository->get_client_profile_extra($userid);
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_banking = $repository->get_client_bank_details($userid);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $ops_service_bank_response = $ops_service->get_bank_compiled_params($results_banking);
        unset($results[0]['is_active']);
        unset($results[0]['temp_otp']);
        unset($results[0]['otp_time']);
        unset($results[0]['password']);
        $result_extra_expo = [];
        if (isset($results_extra[0])) {
            $result_extra_expo = $results_extra[0];
        }
        $client_age = floor((time() - strtotime($results[0]['date_of_joining'])) / 31556926);
        $results[0]['client_tenure'] = $client_age;
        $results[0]['results_extra'] = $result_extra_expo;
        $results[0]['results_banking'] = $ops_service_bank_response;
        $results[0]['member_since'] = date('Y-m-d', strtotime($results[0]['date_of_joining']));
        $live_date_diff = $this->getDateDiff($results[0]['date_of_joining'], date("Y-m-d H:i:s"));
        $results[0]['client_tenure_var'] = $live_date_diff;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $results[0];
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-get-lead-product-details", name="admin-get-product-profile-details")
     */
    public function admin_get_product_profile_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid = $token;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $product_lead_details = $repository->get_lead_product_id($userid);
        if (empty($product_lead_details)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $product_details = $repository->get_lead_product_details($product_lead_details[0]['product_id']);
        if (empty($product_details)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $product_details[0];
        return $this->json($respondWith);
    }
    /**
     * @Route("/create-policy", name="create-policy")
     */
    public function create_policy()
    {
        /**files */
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $em = $this->getDoctrine()->getManager();
        $storeFolder = '/public/uploads/products/icons/';
        $retrieve_folder = '/uploads/products/icons/';
        $thefilearry = $_FILES;
        $filesystem = new Filesystem();
        $project_directory = $this->projectDir;
        $upl_path = $project_directory . $storeFolder;
        try {
            $resp = $filesystem->mkdir($upl_path, 0777);
        } catch (IOExceptionInterface $exception) {
            $respondWith['status'] = 'fail';
            $respondWith['title'] = "Data Saved";
            $respondWith['messages'] = "An error occurred while creating your directory at " . $exception->getPath();
            $respondWith['data'] = 'error thrown';
        }
        if (isset($_FILES['eventfiles'])) {
            $file_data = $_FILES['eventfiles'];
        }
        $file_path = '';
        /**files */
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "...";
            return $this->json($respondWith);
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $renew_frequency = trim($_POST['status_select']);
        $description = trim($_POST['description']);
        $transaction_id = trim($_POST['transaction_id']);
        $beneficiaries = $_POST['beneficiary_name'];
        $relationships = $_POST['relationship'];
        $cov_start_date = $_POST['cov_start_date'];
        $cov_end_date = $_POST['cov_end_date'];
        if ($renew_frequency == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please select renewal frequency.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($description == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter description.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($cov_start_date == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Kindly select cover start date.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($cov_end_date == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Kindly select cover end date.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $lead_details = $repository->get_lead_details($transaction_id);
        if (empty($lead_details)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Invalid transaction.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $client_id = $lead_details[0]['client_id'];
        $product_id = $lead_details[0]['product_id'];
        $prod_details = array(
            'client_id' => $client_id,
            'product_id' => $product_id,
            'description' => $description,
            'transaction_id' => $transaction_id,
            'cov_start_date' => $cov_start_date,
            'cov_end_date' => $cov_end_date,
            'renew_frequency' => $renew_frequency,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $existing_policy_id = $repository->check_ext_policy($prod_details);
        $policy_id = 0;
        if (empty($existing_policy_id)) {
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $policy_id = $repository->create_policy($prod_details);
        } else {
            $policy_id = $existing_policy_id[0]['record_id'];
        }
        if ($policy_id < 1) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Policy creation failed.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $ref_index = 0;
        foreach ($beneficiaries as $beneficiary_element) :
            if (trim($beneficiary_element) == '') {
                $respondWith['status'] = 'false';
                $respondWith['title'] = "null";
                $respondWith['messages'] = "Ensure all beneficiary fields are set. Error at name fields ";
                $respondWith['data'] = 0;
                return $this->json($respondWith);
            }
            if (isset($relationships[$ref_index])) {
                if (trim($relationships[$ref_index]) == '') {
                    $respondWith['status'] = 'false';
                    $respondWith['title'] = "null";
                    $respondWith['messages'] = "Ensure all beneficiary fields are set. Error at beneficiary " . $beneficiary_element;
                    $respondWith['data'] = 0;
                    return $this->json($respondWith);
                }
            } else {
                $respondWith['status'] = 'false';
                $respondWith['title'] = "null";
                $respondWith['messages'] = "Ensure all beneficiary fields are set.";
                $respondWith['data'] = 0;
                return $this->json($respondWith);
            }
            $ref_index++;
        endforeach;
        $ref_index = 0;
        foreach ($beneficiaries as $beneficiary_element) :
            if (isset($relationships[$ref_index])) {
                $beneficiary_details = array(
                    'policy_id' => $policy_id,
                    'beneficiary_name' => $beneficiary_element,
                    'relationship' => $relationships[$ref_index],
                );
                $repository = $this->getDoctrine()
                    ->getRepository(Clients::class);
                $repository->save_policy_beneficiary($beneficiary_details);
            }
            $ref_index++;
        endforeach;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $repository->convert_lead($transaction_id);
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Policy Booked.";
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-fetch-policies", name="admin-fetch-policies")
     */
    public function admin_fetch_policies()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_all_policies();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $client_id = $result['client_id'];
            $user_name = $result['user_name'];
            $product_id = $result['product_id'];
            $policy_start_date = $result['policy_start_date'];
            $policy_end_date = $result['policy_end_date'];
            $frequency_name = $result['frequency_name'];
            $book_date = $result['book_date'];
            $product_name = $result['product_name'];
            $policy_start_date = date('dS-M Y', strtotime($policy_start_date));
            $policy_end_date = date('dS-M Y', strtotime($policy_end_date));
            $book_date = date('dS-M Y', strtotime($book_date));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_policy_params($record_id, $status = 1);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                '<a class="badge rounded-pill bg-info mt-2"  href="#" >' . $user_name . '</a><br>',
                $product_name,
                $frequency_name,
                $policy_start_date,
                $policy_end_date,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/admin-get-policy-profile-details", name="admin-get-policy-profile-details")
     */
    public function admin_get_policy_profile_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid = $token;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $lead_details = $repository->get_policy_client_profile($userid);
        if (empty($lead_details)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $userid = $lead_details[0]['client_id'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_client_profile($userid);
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_extra = $repository->get_client_profile_extra($userid);
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_banking = $repository->get_client_bank_details($userid);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $ops_service_bank_response = $ops_service->get_bank_compiled_params($results_banking);
        unset($results[0]['is_active']);
        unset($results[0]['temp_otp']);
        unset($results[0]['otp_time']);
        unset($results[0]['password']);
        $result_extra_expo = [];
        if (isset($results_extra[0])) {
            $result_extra_expo = $results_extra[0];
        }
        $client_age = floor((time() - strtotime($results[0]['date_of_joining'])) / 31556926);
        $results[0]['client_tenure'] = $client_age;
        $results[0]['results_extra'] = $result_extra_expo;
        $results[0]['results_banking'] = $ops_service_bank_response;
        $results[0]['member_since'] = date('Y-m-d', strtotime($results[0]['date_of_joining']));
        $live_date_diff = $this->getDateDiff($results[0]['date_of_joining'], date("Y-m-d H:i:s"));
        $results[0]['client_tenure_var'] = $live_date_diff;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $results[0];
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-get-policy-details", name="admin-get-policy-details")
     */
    public function admin_get_policy_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $token;
        $record_id = $_POST['record_id'];
        if (trim($record_id) == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $resultz = $repository->get_policy_by_id($record_id);
        if (empty($resultz)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $result = null;
        foreach ($resultz as $result_extract) {
            $result = $result_extract;
        }
        $record_id = $result['record_id'];
        $client_id = $result['client_id'];
        $user_name = $result['user_name'];
        $product_id = $result['product_id'];
        $policy_start_date = $result['policy_start_date'];
        $policy_end_date = $result['policy_end_date'];
        $frequency_name = $result['frequency_name'];
        $book_date = $result['book_date'];
        $product_name = $result['product_name'];
        $result[0]['policy_start_date'] = date('dS-M Y', strtotime($policy_start_date));
        $result[0]['policy_end_date'] = date('dS-M Y', strtotime($policy_end_date));
        $result[0]['book_date'] = date('dS-M Y', strtotime($book_date));
        $ops_service = new OpsService;
        $dropdown = '';
        $ops_service_response = $ops_service->get_policy_params($record_id, $status = 1);
        $dropdown = $ops_service_response['dropdown'];
        $unit_ui_display = $ops_service_response['unit_ui_display'];
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $result;
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-get-policy-beneficiaries", name="admin-get-policy-beneficiaries")
     */
    public function admin_get_policy_beneficiaries()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $token;
        $record_id = $_POST['record_id'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_policy_beneficiary($record_id);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $results;
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-fetch-client-policies", name="admin-fetch-client-policies")
     */
    public function admin_fetch_client_policies()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $record_id = $_GET['user-id-c'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_policy_by_client_id($record_id);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $client_id = $result['client_id'];
            $user_name = $result['user_name'];
            $product_id = $result['product_id'];
            $policy_start_date = $result['policy_start_date'];
            $policy_end_date = $result['policy_end_date'];
            $frequency_name = $result['frequency_name'];
            $book_date = $result['book_date'];
            $product_name = $result['product_name'];
            $policy_start_date = date('dS-M Y', strtotime($policy_start_date));
            $policy_end_date = date('dS-M Y', strtotime($policy_end_date));
            $book_date = date('dS-M Y', strtotime($book_date));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_policy_params($record_id, $status = 1);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                $product_name,
                $frequency_name,
                $policy_start_date,
                $policy_end_date,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/get-admin-dashboard-details", name="get-admin-dashboard-details")
     */
    public function get_admin_dashboard_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $profile_array = array();
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $client_count = $repository->get_client_count();
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $policy_count = $repository->get_policy_count();
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $ticket_count = $repository->get_ticket_count();
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "Fetched";
        $respondWith['messages'] = "Done.";
        $respondWith['client_count'] = $client_count[0];
        $respondWith['policy_count'] = $policy_count[0];
        $respondWith['ticket_count'] = $ticket_count[0];
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-get-client-claim-list", name="admin-get-client-claim-list")
     */
    public function admin_get_client_claim_list()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_admin_claim_data($userid);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $client_id = $result['client_id'];
            $user_name = $result['user_name'];
            $product_name = $result['product_name'];
            $on_date = $result['on_date'];
            $claim_status = $result['claim_status'];
            $status_description = $result['status_description'];
            $on_date = date('dS-M Y', strtotime($on_date));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_admin_claim_policy_params_ovr($record_id, $claim_status, $status_description);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                '<a href="/admin-view-client-profile?param=' . $client_id . '"><span class="badge rounded-pill bg-blue mt-2">' . $user_name . '</span></a>',
                $product_name,
                $on_date,
                $unit_ui_display,
                $dropdown,
            );
        endforeach;
        return $this->json($returnarray);
    }
    /**
     * @Route("/admin-get-claim-policy-details", name="admin-get-claim-policy-details")
     */
    public function admin_get_claim_policy_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $token;
        $record_id = $_POST['record_id'];
        if (trim($record_id) == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $record_ids = $repository->get_policy_claim_policy_profile($record_id);
        $record_id = $record_ids[0]['policy_id'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $resultz = $repository->get_policy_by_id($record_id);
        if (empty($resultz)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $result = null;
        foreach ($resultz as $result_extract) {
            $result = $result_extract;
        }
        $record_id = $result['record_id'];
        $client_id = $result['client_id'];
        $user_name = $result['user_name'];
        $product_id = $result['product_id'];
        $policy_start_date = $result['policy_start_date'];
        $policy_end_date = $result['policy_end_date'];
        $frequency_name = $result['frequency_name'];
        $book_date = $result['book_date'];
        $product_name = $result['product_name'];
        $result[0]['policy_start_date'] = date('dS-M Y', strtotime($policy_start_date));
        $result[0]['policy_end_date'] = date('dS-M Y', strtotime($policy_end_date));
        $result[0]['book_date'] = date('dS-M Y', strtotime($book_date));
        $ops_service = new OpsService;
        $dropdown = '';
        $ops_service_response = $ops_service->get_policy_params($record_id, $status = 1);
        $dropdown = $ops_service_response['dropdown'];
        $unit_ui_display = $ops_service_response['unit_ui_display'];
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $result;
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-get-policy-claim-profile-details", name="admin-get-policy-claim-profile-details")
     */
    public function admin_get_policy_claim_profile_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid = $token;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $lead_details = $repository->get_policy_client_profile($userid);
        if (empty($lead_details)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $userid = $lead_details[0]['client_id'];
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_client_profile($userid);
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_extra = $repository->get_client_profile_extra($userid);
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results_banking = $repository->get_client_bank_details($userid);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $ops_service_bank_response = $ops_service->get_bank_compiled_params($results_banking);
        unset($results[0]['is_active']);
        unset($results[0]['temp_otp']);
        unset($results[0]['otp_time']);
        unset($results[0]['password']);
        $result_extra_expo = [];
        if (isset($results_extra[0])) {
            $result_extra_expo = $results_extra[0];
        }
        $client_age = floor((time() - strtotime($results[0]['date_of_joining'])) / 31556926);
        $results[0]['client_tenure'] = $client_age;
        $results[0]['results_extra'] = $result_extra_expo;
        $results[0]['results_banking'] = $ops_service_bank_response;
        $results[0]['member_since'] = date('Y-m-d', strtotime($results[0]['date_of_joining']));
        $live_date_diff = $this->getDateDiff($results[0]['date_of_joining'], date("Y-m-d H:i:s"));
        $results[0]['client_tenure_var'] = $live_date_diff;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $results[0];
        return $this->json($respondWith);
    }
    /**
     * @Route("/admin-get-claim-profile-details", name="admin-get-claim-profile-details")
     */
    public function admin_get_claim_profile_details()
    {
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
        }
        $userid = $token;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_client_claim_details($userid);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $i = 1;
        $pendingString = '';
        foreach ($results as $result) :
            $record_id = $result['record_id'];
            $client_id = $result['client_id'];
            $user_name = $result['user_name'];
            $product_name = $result['product_name'];
            $on_date = $result['on_date'];
            $claim_status = $result['claim_status'];
            $status_description = $result['status_description'];
            $on_date = date('dS-M Y', strtotime($on_date));
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_admin_claim_policy_params_ovr_with_toggle($record_id, $claim_status, $status_description);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];
            $returnarray['data'][] = array(
                'client' => '<a href="/admin-view-client-profile?param=' . $client_id . '"><span class="badge rounded-pill bg-blue mt-2">' . $user_name . '</span></a>',
                'product' => $product_name,
                'date' => $on_date,
                'status' => $unit_ui_display,
                'action' => $dropdown,
            );
        endforeach;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $returnarray;
        return $this->json($respondWith);
    }
    /**
     * @Route("/set-claim-status-by-id", name="set-claim-status-by-id")
     */
    public function set_claim_status_by_id()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $record_id = $_POST['record_id'];
        $click_act = $_POST['click_act'];
        $data_t_save = array(
            'record_id' => $record_id,
            'click_act' => $click_act,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->update_claim_status($data_t_save);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Not Updated.";
            return $this->json($respondWith);
        } else {
            $respondWith['status'] = 'ok';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Updated.";
            return $this->json($respondWith);
        }
    }
    /**CHARTS */
    /**
     * @Route("/get-client-chart-data", name="get-client-chart-data")
     */
    public function get_client_chart_data()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $data_t_save = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_user_stat($data_t_save);
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data.";
            return $this->json($respondWith);
        } else {
            $respondWith['status'] = 'ok';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Updated.";
            $respondWith['chart_data'] = $results;
            return $this->json($respondWith);
        }
    }

    /**
     * @Route("/get-policy-uptake-chart-data", name="get-policy-uptake-chart-data")
     */
    public function get_policy_uptake_chart_data()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $moths_between_outputs = [];
        $moths_between_outputs_computation = [];
        $time   = strtotime($start_date);
        $last   = date('M-Y', strtotime($end_date));
        do {
            $month = date('M-Y', $time);
            $month_computation = date('m-Y', $time);
            $total = date('t', $time);
            $moths_between_outputs[] = $month;
            $moths_between_outputs_computation[] = $month_computation;
            $time = strtotime('+1 month', $time);
        } while ($month != $last);
        $data_t_save = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        $repository = $this->getDoctrine()
            ->getRepository(ShellizPolicies::class);
        $results = $repository->get_policy_uptake_stat_product_list($data_t_save);

        $products = array();
        $product_lists = array_filter($results, function ($el) use (&$products) {
            if (in_array($el['product_name'], $products)) {
                return false;
            } else {
                $products[] = $el['product_name'];
                return true;
            }
        });
        
        $json_array_build = array();
        $pie_ini_data = array();
        $total_count=0;

        foreach ($product_lists as $product_list) :
            $count_array = array();
            
            foreach ( $moths_between_outputs_computation as $this_month) :
                $month_this_array = (explode("-", $this_month));
                $the_month = $month_this_array[0];
                $the_year = $month_this_array[1];
                $query_params = array(
                    'the_month' => $the_month,
                    'the_year' => $the_year,
                    'product_name' => $product_list['product_name'],
                );
                            
                $repository = $this->getDoctrine()
                    ->getRepository(ShellizPolicies::class);
                $results_pro_cnt = $repository->get_policy_uptake_stat_sav($query_params);

                if(!empty($results_pro_cnt)){               
                    foreach($results_pro_cnt as $result){
                        array_push($count_array,intval($result['policy_count']));
                    }
                }else{ 
                    array_push($count_array,0);
                }
            endforeach;

            $new_form = array(
                'name' =>  $product_list['product_name'],
                'data' => $count_array,
            );
            
            $pie_data = array(
                'name' =>  $product_list['product_name'],
                'y' => array_sum($count_array),
            );
            array_push($json_array_build, $new_form);
            array_push($pie_ini_data, $pie_data);
        endforeach;
        
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data.";
            return $this->json($respondWith);
        } else {
            $respondWith['status'] = 'ok';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Updated.";
            $respondWith['chart_data'] = $json_array_build;
            $respondWith['era']=$moths_between_outputs;
            $respondWith['pie_data']= $pie_ini_data;
            return $this->json($respondWith);
        }
    }
    
    /**
     * @Route("/get-product-claims-chart-data", name="get-product-claims-chart-data")
     */
    public function get_product_claims_chart_data()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        $userid = '';
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
        } else {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "Fix some errors";
            $respondWith['messages'] = "Please reload this page and try again.";
            return $this->json($respondWith);
            exit;
        }
        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $moths_between_outputs = [];
        $moths_between_outputs_computation = [];
        $time   = strtotime($start_date);
        $last   = date('M-Y', strtotime($end_date));
        do {
            $month = date('M-Y', $time);
            $month_computation = date('m-Y', $time);
            $total = date('t', $time);
            $moths_between_outputs[] = $month;
            $moths_between_outputs_computation[] = $month_computation;
            $time = strtotime('+1 month', $time);
        } while ($month != $last);
        $data_t_save = array(
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        $repository = $this->getDoctrine()
            ->getRepository(ShellizPolicies::class);
        $results = $repository->get_product_claims_stat_product_list($data_t_save);

        $products = array();
        $product_lists = array_filter($results, function ($el) use (&$products) {
            if (in_array($el['product_name'], $products)) {
                return false;
            } else {
                $products[] = $el['product_name'];
                return true;
            }
        });
        
        $json_array_build = array();
        $pie_ini_data = array();

        foreach ($product_lists as $product_list) :
            $count_array = array();
            
            foreach ( $moths_between_outputs_computation as $this_month) :
                $month_this_array = (explode("-", $this_month));
                $the_month = $month_this_array[0];
                $the_year = $month_this_array[1];
                $query_params = array(
                    'the_month' => $the_month,
                    'the_year' => $the_year,
                    'product_name' => $product_list['product_name'],
                );
                            
                $repository = $this->getDoctrine()
                    ->getRepository(ShellizPolicies::class);
                $results_pro_cnt = $repository->get_prod_claim_stat_sav($query_params);

                if(!empty($results_pro_cnt)){               
                    foreach($results_pro_cnt as $result){
                        array_push($count_array,intval($result['policy_count']));
                    }
                }else{ 
                    array_push($count_array,0);
                }
            endforeach;

            $new_form = array(
                'name' =>  $product_list['product_name'],
                'data' => $count_array,
            );
            
            $pie_data = array(
                'name' =>  $product_list['product_name'],
                'y' => array_sum($count_array),
            );

            array_push($json_array_build, $new_form);
            array_push($pie_ini_data, $pie_data);
        endforeach;
        
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data.";
            return $this->json($respondWith);
        } else {
            $respondWith['status'] = 'ok';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Updated.";
            $respondWith['chart_data'] = $json_array_build;
            $respondWith['era']=$moths_between_outputs;
            $respondWith['pie_data']= $pie_ini_data;
            return $this->json($respondWith);
        }
    }

    public function looer(){

    }
    /**SHARED FUNC */
    public function getDateDiff($first_date, $second_date)
    {
        $first_date = new \DateTime($first_date);
        $second_date = new \DateTime($second_date);
        $live_date_diff = $first_date->diff($second_date);
        $nodeResult['total_days'] = $live_date_diff->days;
        $nodeResult['years'] = $live_date_diff->y;
        $nodeResult['months'] = $live_date_diff->m;
        $nodeResult['days'] = $live_date_diff->d;
        $nodeResult['hours'] = $live_date_diff->h;
        $nodeResult['minutes'] = $live_date_diff->i;
        $nodeResult['seconds'] = $live_date_diff->s;
        return $nodeResult;
    }
    public function save_local_files($record_id, $storeFolder, $retrieve_folder, $upl_path)
    {
        $file_data = array();
        $countfiles = count($_FILES);
        $transaction_type = null;
        try {
            foreach ($_FILES as $key => $value) {
                $countfiles = count($value['name']);
                for ($i = 0; $i < $countfiles; $i++) {
                    $theid = time() . rand();
                    $targetFile = $storeFolder . $value['name'][$i];
                    $tempFile = $value['tmp_name'][$i];
                    $file_ext = substr($targetFile, strripos($targetFile, '.'));
                    try {
                        $is_moved = move_uploaded_file($tempFile, $upl_path . $theid . $file_ext);
                    } catch (Eception $e) {
                    }
                    $file_data[] = array(
                        'record_id' => $record_id,
                        'upload_type' => $transaction_type,
                        'file_path' => $retrieve_folder . $theid . $file_ext,
                    );
                }
            }
            return ($file_data);
        } catch (Exception $error) {
            return $file_data;
        }
    }
}
