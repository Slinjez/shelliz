<?php
namespace App\Controller;

use App\Controller\SessionController;
use App\Entity\Clients;
use App\Service\OpsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ClientSideController extends AbstractController
{
    private $session;
    protected $projectDir;
    public function __construct(SessionInterface $session, KernelInterface $kernel)
    {
        $this->session = $session;
        $this->projectDir = $kernel->getProjectDir();
    }
    /**
     * @Route("/loginAction", name="Shared loginAction")
     */
    public function loginAction()
    {
        sleep(0.5);
        $sescontrol = new SessionController;
        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($email == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your email address.";
            return $this->json($respondWith);
        } else if ($password == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your password.";
            return $this->json($respondWith);
        } else {
            $password = hash('ripemd160', $password);
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
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
                $comment = strlen($username) > 15 ? substr($username, 0, 15) . "..." : $username;
                $profpic = $pic;
                $role = $validity['retval']['role'];
                $token = $sescontrol->getJwt($userid, $role);
                $this->session->set('dropshopuname', $username);
                $this->session->set('dropshopuid', $userid);
                $this->session->set('token', $token);
                $path = '';
                if ($role == 1) {
                    $path = '/client-dash';
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
                $respondWith['path'] = $result['path'];
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
     * @Route("/registerAction", name="Shared registerAction")
     */
    public function register_Action()
    {
        $OpsService = new OpsService;
        $em = $this->getDoctrine()->getManager();
        $_POST['mobile_number'] = 0;
        $username = trim($_POST['username']);
        $work_email = trim($_POST['email']);
        $mobile_number = trim($_POST['mobile_number']);
        $ps1 = trim($_POST['reg_ps']);
        $uppercase = preg_match('@[A-Z]@', $ps1);
        $lowercase = preg_match('@[a-z]@', $ps1);
        $number = preg_match('@[0-9]@', $ps1);
        $specialChars = preg_match('@[^\w]@', $ps1);
        if ($username == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your preferred User Name.";
            return $this->json($respondWith);
        } else if ($work_email == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your Email Address.";
            return $this->json($respondWith);
        } else if ($ps1 == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your Password.";
            return $this->json($respondWith);
        } else {
            $password = hash('ripemd160', $ps1);
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $pre_saved = $repository->confirm_not_already_registered($work_email);
            if (empty($pre_saved)) {
                $last_id = 0;
                $reset_code = $OpsService->generate_random_string($length = 6);
                $user_info = array(
                    'user_name' => $username,
                    'user_email' => $work_email,
                    'password' => $password,
                    'mobile_number' => $mobile_number,
                    'OTP' => $reset_code,
                );
                $user_info = new Clients;
                $user_info->setUserName($username);
                $user_info->setEmailAddress($work_email);
                $user_info->setPhone($mobile_number);
                $user_info->setPassword($password);
                $user_info->setTempOtp($reset_code);
                $user_info->setDateOfJoining(new \Datetime());
                $user_info->setModifiedDate(new \Datetime());
                $em->persist($user_info);
                $em->flush();
                $last_id = $user_info->getRecordId();
                $reset_code = $OpsService->generate_random_string($length = 6);
                $save_data = array(
                    "work_email" => $work_email,
                    "ps1" => $password,
                    'OTP' => $reset_code,
                );
                $repository = $this->getDoctrine()
                    ->getRepository(Clients::class);
                $user_id = $repository->update_user_password($save_data);
                if ($last_id > 0) {
                    $html_msg = $this->renderView('emails/email-otp.html.twig', [
                        'title' => "Document Render",
                        'client_name' => $username,
                        'otp' => $reset_code,
                    ]);
                    $message = $OpsService->send_email_reg_otp($last_id, $username, $work_email, $html_msg);
                    $obsfcate_email = preg_replace("/(?!^).(?=[^@]+@)/", "*", $work_email);
                    $respondWith['status'] = 'ok';
                    $respondWith['messages'] = "Hello " . $username . ". Please enter the OTP we sent to your email " . $obsfcate_email . ".";
                    return $this->json($respondWith);
                } else {
                    $respondWith['status'] = 'fail';
                    $respondWith['messages'] = "Something went wrong. Please try again. If the issue persists, kindly contact support.";
                    return $this->json($respondWith);
                }
            } else {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "We already have your account. Kindly login.";
                return $this->json($respondWith);
            }
        }
    }
    /**
     * @Route("/resetpsaction-client", name="Shared reset ps action client")
     */
    public function reset_ps_action()
    {
        $OpsService = new OpsService;
        $workemail = trim($_POST['email']);
        $ps1 = trim($_POST['password']);
        $uppercase = preg_match('@[A-Z]@', $ps1);
        $lowercase = preg_match('@[a-z]@', $ps1);
        $number = preg_match('@[0-9]@', $ps1);
        $specialChars = preg_match('@[^\w]@', $ps1);
        if ($workemail == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your Email Address.";
            return $this->json($respondWith);
        } else if ($ps1 == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your Password.";
            return $this->json($respondWith);
        } else if (!$uppercase || !$lowercase || !$number || strlen($ps1) < 5) {
            $respondWith['status'] = 'fail';
            $respondWith['title'] = 'Error';
            $respondWith['messages'] = "Password should be at least 5 characters long and should include at least one upper case letter or one number. You can use one or more special character.";
            return $this->json($respondWith);
        } else {
            $password = hash('ripemd160', $ps1);
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $validity = $repository->verify_registration_reset($workemail);
            $user_info = array();
            if (empty($validity)) {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "Sorry, Action not allowed for this email address.";
                return $this->json($respondWith);
            } else {
                $reset_code = $OpsService->generate_random_string($length = 6);
                $save_data = array(
                    "work_email" => $workemail,
                    "ps1" => $password,
                    'OTP' => $reset_code,
                );
                $repository = $this->getDoctrine()
                    ->getRepository(Clients::class);
                $user_id = $repository->update_user_password($save_data);
                $saved = 1;
                $last_id = $saved;
                if ($saved > 0) {
                    $html_msg = $this->renderView('emails/email-otp.html.twig', [
                        'title' => "Document Render",
                        'client_name' => $validity[0]['user_name'],
                        'otp' => $reset_code,
                    ]);
                    $message = $OpsService->send_email_reg_otp($last_id, $validity[0]['user_name'], $workemail, $html_msg);
                    $obsfcate_email = preg_replace("/(?!^).(?=[^@]+@)/", "*", $workemail);
                    $respondWith['status'] = 'ok';
                    $respondWith['messages'] = "Hello " . $validity[0]['user_name'] . ". Please enter the OTP we sent to your email " . $obsfcate_email . ".";
                    return $this->json($respondWith);
                } else {
                    $respondWith['status'] = 'fail';
                    $respondWith['messages'] = "We will sent reset password instructions to your email if you have registered an account with us.";
                    return $this->json($respondWith);
                }
            }
        }
    }
    /**
     * @Route("/update-auth-client-password", name="update-auth-client-password")
     */
    public function reset_ps_action_auth()
    {
        $OpsService = new OpsService;
        $sescontrol = new SessionController;
        $old_password = trim($_POST['old_password']);
        $new_password = trim($_POST['new_password']);
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
        $uppercase = preg_match('@[A-Z]@', $new_password);
        $lowercase = preg_match('@[a-z]@', $new_password);
        $number = preg_match('@[0-9]@', $new_password);
        $specialChars = preg_match('@[^\w]@', $new_password);
        if ($old_password == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your current Password.";
            return $this->json($respondWith);
        } else if (!$uppercase || !$lowercase || !$number || strlen($new_password) < 5) {
            $respondWith['status'] = 'fail';
            $respondWith['title'] = 'Error';
            $respondWith['messages'] = "Password should be at least 5 characters long and should include at least one upper case letter or one number. You can use one or more special character.";
            return $this->json($respondWith);
        } else {
            $old_password_hash = hash('ripemd160', $old_password);
            $password = hash('ripemd160', $new_password);
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $validity = $repository->verify_registration_reset_by_id($userid);
            $user_info = array();
            if (empty($validity)) {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "Sorry, Action not allowed for this email address.";
                return $this->json($respondWith);
            } else {
                if ($old_password_hash != $validity[0]['password']) {
                    $respondWith['status'] = 'fail';
                    $respondWith['messages'] = "Invalid current password.";
                    return $this->json($respondWith);
                }
                $reset_code = $OpsService->generate_random_string($length = 6);
                $save_data = array(
                    "work_email" => $validity[0]['email_address'],
                    "ps1" => $password,
                    'OTP' => $reset_code,
                );
                $repository = $this->getDoctrine()
                    ->getRepository(Clients::class);
                $user_id = $repository->update_user_password_authenticated($save_data);
                $saved = 1;
                $last_id = $saved;
                if ($saved > 0) {
                    $respondWith['status'] = 'ok';
                    $respondWith['messages'] = "Hello " . $validity[0]['user_name'] . ". you have updated your password.";
                    return $this->json($respondWith);
                } else {
                    $respondWith['status'] = 'fail';
                    $respondWith['messages'] = "We will sent reset password instructions to your email if you have registered an account with us.";
                    return $this->json($respondWith);
                }
            }
        }
    }
    /**
     * @Route("/update-auth-client-profile-picture", name="update-auth-client-profile-picture")
     */
    public function reset_pprof_pic_action_auth()
    {
        /**files */
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $em = $this->getDoctrine()->getManager();
        $storeFolder = '/public/uploads/user/profiles/';
        $retrieve_folder = '/uploads/user/profiles/';
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

        if (!empty($userid)) {
            $file_datas = $this->save_local_files($userid, $storeFolder, $retrieve_folder, $upl_path);
            if (empty($file_datas)) {
                $respondWith['status'] = 'fail';
                $respondWith['title'] = "Error";
                $respondWith['messages'] = "Not updated.";
                return $this->json($respondWith);
            }
            foreach ($file_datas as $file_data):
                $file_path = $file_data['file_path'];
                $repository = $this->getDoctrine()
                    ->getRepository(Clients::class);
                $repository->update_user_profile_picture($file_data);
            endforeach;
        } else {
            $respondWith['status'] = 'fail';
            $respondWith['title'] = "Error";
            $respondWith['messages'] = "Not updated.";
            return $this->json($respondWith);
        }
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "Ok";
        $respondWith['messages'] = "Profile updated.";
        $respondWith['file_path'] = $file_path;
        return $this->json($respondWith);
    }
    /**
     * @Route("/activate-otp-client", name="Shared activate-otp-client")
     */
    public function activate_otp()
    {
        $OpsService = new OpsService;
        $ver_otp = trim($_POST['ver_otp']);
        $assocemail = trim($_POST['assocemail']);
        if ($assocemail == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Error occurred. Please contact support.";
            return $this->json($respondWith);
        } else if ($ver_otp == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter the OTP we sent to your email.";
            return $this->json($respondWith);
        } else {
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $set_otp = $repository->get_acc_otp($assocemail);
            if (empty($set_otp)) {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "OTP purged. Please contact support.";
                return $this->json($respondWith);
            }
            $otp_time = $set_otp[0]['otp_time'];
            $time_now = strtotime("now");
            $datetime1 = new \DateTime();
            $datetime2 = new \DateTime($otp_time);
            $interval = $datetime1->diff($datetime2);
            $interval_val = $interval->format('%H');
            if ($interval_val >= 24) {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "OTP purged. You took too long. Kindly reload and reset password again.";
                return $this->json($respondWith);
            }
            if ($set_otp[0]['temp_otp'] == '') {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "OTP purged. Please contact support.";
                return $this->json($respondWith);
            }
            if ($ver_otp == $set_otp[0]['temp_otp']) {
                $repository = $this->getDoctrine()
                    ->getRepository(Clients::class);
                $repository->activate_acc_otp($assocemail);
                $respondWith['status'] = 'ok';
                $respondWith['messages'] = "You have activated your account! You can now log in.";
                return $this->json($respondWith);
            } else {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "You have entered a wrong OTP.";
                return $this->json($respondWith);
            }
        }
    }
    /**
     * @Route("/logout", name="log user out")
     */
    public function logout()
    {
        $this->session->remove('token');
        $respondWith['status'] = 'ok';
        $respondWith['messages'] = "Logged out";
        $respondWith['sendto'] = 1;
        return $this->json($respondWith);
    }
    /**
     * @Route("/get-profile-details", name="get-profile-details")
     */
    public function get_profile_details()
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
     * @Route("/get-bank-details-by-id", name="get-bank-details-by-id")
     */
    public function get_bank_details_by_id()
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
        $results_banking = $repository->get_bank_details_by_id($attr_id);
        if (empty($results_banking)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $ops_service_bank_response = $ops_service->get_bank_compiled_params($results_banking);
        $results[0]['results_banking'] = $ops_service_bank_response;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $results[0];
        return $this->json($respondWith);
    }
    /**
     * @Route("/get-country-list", name="get-country-list")
     */
    public function get_country_list()
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
        $results = $repository->get_country_list();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $result_ui['country_list'] = $results;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $result_ui;
        return $this->json($respondWith);
    }
    /**
     * @Route("/update-bio", name="update-bio")
     */
    public function update_bio()
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
        $client_full_name = trim($_POST['client_full_name']);
        $client_phone = trim($_POST['client_phone']);
        $address = trim($_POST['address']);
        $id_number = trim($_POST['id_number']);
        $pin = trim($_POST['pin']);
        $city = trim($_POST['city']);
        $postal_code = trim($_POST['postal_code']);
        $country = trim($_POST['country']);
        if ($client_full_name == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your full name.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($client_phone == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your mobile number.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($address == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your location.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($id_number == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your id number.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($pin == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your KRA PIN.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($city == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your city.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($postal_code == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your postal code.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($country == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please select your country.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if (!is_numeric($country)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please select your country.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $user_details = array(
            'userid' => $userid,
            'client_full_name' => $client_full_name,
            'client_phone' => $client_phone,
            'address' => $address,
            'id_number' => $id_number,
            'pin' => $pin,
            'city' => $city,
            'postal_code' => $postal_code,
            'country' => $country,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $update_client_results = $repository->update_client_profile($user_details);
        if (!$update_client_results) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Save failed. Kindly try again later.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->update_client_extra_profile($user_details);
        if (!$results) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Save failed. Kindly try again later.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $user_details;
        return $this->json($respondWith);
    }
    /**
     * @Route("/save-bank", name="save-bank")
     */
    public function save_bank()
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
        $bank_name = trim($_POST['bank_name']);
        $bank_branch = trim($_POST['bank_branch']);
        $account_number = trim($_POST['account_number']);
        if ($bank_name == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your bank name.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($bank_branch == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your bank branch.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($account_number == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your account number.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $bank_details = array(
            'userid' => $userid,
            'bank_name' => $bank_name,
            'bank_branch' => $bank_branch,
            'account_number' => $account_number,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $existing_bank = $repository->check_bank_details($bank_details);
        if (empty($existing_bank)) {
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $update_bank_results = $repository->save_bank_details($bank_details);
            if (!$update_bank_results) {
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
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $update_bank_results = $repository->update_bank_details($bank_details);
            if (!$update_bank_results) {
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
     * @Route("/save-call-back", name="call-back")
     */
    public function save_call_back()
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
        $call_back_product_id = trim($_POST['call_back_product_id']);
        $call_back_date = trim($_POST['call_back_date']);

        if ($call_back_product_id == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please reload page.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($call_back_date == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please select call back date.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }

        $cb_details = array(
            'userid' => $userid,
            'call_back_product_id' => $call_back_product_id,
            'call_back_date' => $call_back_date,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $existing_bank = $repository->check_cb_details($cb_details);
        if (empty($existing_bank)) {
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $update_bank_results = $repository->save_CB_details($cb_details);
            if (!$update_bank_results) {
                $respondWith['status'] = 'false';
                $respondWith['title'] = "null";
                $respondWith['messages'] = "Save failed. Kindly try again later.";
                $respondWith['data'] = 0;
                return $this->json($respondWith);
            } else {
                $respondWith['status'] = 'ok';
                $respondWith['title'] = "Ok";
                $respondWith['messages'] = "One of our agents will contact you on " . $call_back_date . ".";
                return $this->json($respondWith);
            }
        } else {
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $update_bank_results = $repository->update_CB_details($cb_details);
            if (!$update_bank_results) {
                $respondWith['status'] = 'false';
                $respondWith['title'] = "null";
                $respondWith['messages'] = "Update failed. Kindly try again later.";
                $respondWith['data'] = 0;
                return $this->json($respondWith);
            } else {
                $respondWith['status'] = 'ok';
                $respondWith['title'] = "Ok";
                $respondWith['messages'] = "One of our agents will contact you on " . $call_back_date . ".";
                return $this->json($respondWith);
            }
        }
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        return $this->json($respondWith);
    }
    /**
     * @Route("/save-ticket", name="save-ticket")
     */
    public function save_ticket()
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

        $ticket_type = trim($_POST['ticket_type']);
        $ticket_subject = trim($_POST['ticket_subject']);
        $message = trim($_POST['message']);

        if ($ticket_type == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Kindly select ticket type.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($ticket_subject == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Kindly enter ticket subject.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($message == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Kindly enter ticket message.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }

        $ticket_details = array(
            'userid' => $userid,
            'ticket_type' => $ticket_type,
            'ticket_subject' => $ticket_subject,
            'message' => $message,
        );

        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $update_ticket_results = $repository->save_ticket_details($ticket_details);

        if (!$update_ticket_results) {
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

    }
    /**
     * @Route("/update-bank", name="update-bank")
     */
    public function update_bank()
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
        $bank_id_up = trim($_POST['bank_id_up']);
        $bank_name = trim($_POST['bank_name']);
        $bank_branch = trim($_POST['bank_branch']);
        $account_number = trim($_POST['account_number']);
        if ($bank_id_up == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Invalid attr_id kindly reload page and try again.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($bank_name == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your bank name.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($bank_branch == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your bank branch.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($account_number == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter your account number.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $bank_details = array(
            'bank_id_up' => $bank_id_up,
            'userid' => $userid,
            'bank_name' => $bank_name,
            'bank_branch' => $bank_branch,
            'account_number' => $account_number,
        );
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $update_bank_results = $repository->update_bank_details_by_id($bank_details);
        if (!$update_bank_results) {
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
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        return $this->json($respondWith);
    }
    /**
     * @Route("/client-toggle-bank-status", name="client-toggle-bank-status")
     */
    public function client_toggle_bank_status()
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
        $results = $repository->update_bank_status($data_t_save);
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
    /**DASHBOARD */
    /**
     * @Route("/get-dashboard-details", name="get-dashboard-details")
     */
    public function get_dashboard_details()
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
        $results = $repository->get_client_profile($userid);

        $full_user_name = $results[0]['user_name'];
        $pieces = explode(" ", $full_user_name);
        $first_name = $pieces[0];

        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }

        $client_age = floor((time() - strtotime($results[0]['date_of_joining'])) / 31556926);
        $profile_array[0]['client_tenure'] = $client_age;
        $profile_array[0]['member_since'] = date('dS M Y', strtotime($results[0]['date_of_joining']));
        $live_date_diff = $this->getDateDiff($results[0]['date_of_joining'], date("Y-m-d H:i:s"));

        $tenure_string = '';

        $total_days = $live_date_diff['total_days'];
        $months = $live_date_diff['months'];
        $years = $live_date_diff['years'];

        if ($total_days <= 1) {
            $tenure_string = 'A Day!';
        } else if ($months < 1) {
            $tenure_string = $total_days . ' days';
        } else if ($months == 1) {
            $tenure_string = $months . ' month';
        } else if ($months > 1) {
            $tenure_string = $months . ' months';
        } else if ($years < 1) {
            $tenure_string = $months . ' months';
        } else if ($years <= 1 && $months >= 1) {
            $tenure_string = $years . ' year and ' . $months . ' months';
        } else if ($years > 1) {
            $tenure_string = $years . ' years';
        }
        $profile_array[0]['first_name'] = $first_name;
        $profile_array[0]['client_tenure_var'] = $live_date_diff;
        $profile_array[0]['tenure_string'] = $tenure_string;

        $respondWith['status'] = 'ok';
        $respondWith['title'] = "Fetched";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $profile_array[0];
        return $this->json($respondWith);
    }

    /**
     * @Route("/get-dashboard-overview", name="get-dashboard-overview")
     */
    public function get_dashboard_overview()
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
        $ticket_count_array = $repository->get_client_ticket_count($userid);
        $ticket_count = $ticket_count_array[0]['ticket_count'];

        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $client_beneficiary_array = $repository->get_client_beneficiary_count($userid);
        $beneficiary_count = $client_beneficiary_array[0]['beneficiary_count'];

        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $client_policy_array = $repository->get_client_policy_count($userid);
        $policy_count = $client_policy_array[0]['policy_count'];

        $respondWith['status'] = 'ok';
        $respondWith['title'] = "Fetched";
        $respondWith['messages'] = "Done.";
        $respondWith['ticket_count'] = $ticket_count;
        $respondWith['beneficiary_count'] = $beneficiary_count;
        $respondWith['policy_count'] = $policy_count;
        return $this->json($respondWith);
    }

    /**
     * @Route("/client-get-policy-profile-details", name="client-get-policy-profile-details")
     */
    public function client_get_client_policy_profile_details()
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
        $results = $repository->get_policy_by_client_id($userid);

        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }

        $i = 1;
        $pendingString = '';
        foreach ($results as $result):
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
            $ops_service_response = $ops_service->get_client_policy_params($record_id, $status = 1);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];

            $returnarray['data'][] = array(
                // '<a class="badge rounded-pill bg-info mt-2"  href="#" >' . $user_name . '</a><br>',
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
     * @Route("/get-policy-type-list", name="get-policy-type-list")
     */
    public function get_policy_type_list()
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
        $results = $repository->get_policy_type_list();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $result_ui['country_list'] = $results;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $result_ui;
        return $this->json($respondWith);
    }

    /**
     * @Route("/get-product-list", name="get-product-list")
     */
    public function get_product_list()
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
        $results = $repository->get_product_list();

        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $result_ui['data_list'] = $results;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $result_ui;
        return $this->json($respondWith);
    }

    /**
     * @Route("/get-ticket-type-list", name="get-ticket-type-list")
     */
    public function get_ticket_type_list()
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
        $results = $repository->get_ticket_type_list();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $result_ui['country_list'] = $results;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $result_ui;
        return $this->json($respondWith);
    }

    /**
     * @Route("/client-fetch-my-tickets", name="client-fetch-my-tickets")
     */
    public function client_fetch_my_tickets()
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
        $results = $repository->get_my_tickets($userid);

        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }

        $i = 1;
        $pendingString = '';
        foreach ($results as $result):
            $record_id = $result['record_id'];
            $user_name = $result['user_name'];
            $ticket_type_description = $result['ticket_type_description'];
            $ticket_subject = $result['ticket_subject'];
            $message = $result['message'];
            $status = $result['stage'];
            $created_date = $result['on_date'];

            $display_date = date('dS-M Y', strtotime($created_date));

            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_ticket_params($record_id, $status);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];

            $returnarray['data'][] = array(
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
     * @Route("/get-policy-frequency", name="get-policy-frequency")
     */
    public function get_policy_frequency()
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
        $results = $repository->get_policy_frequency_list();
        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        $result_ui['country_list'] = $results;
        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        $respondWith['data'] = $result_ui;
        return $this->json($respondWith);
    }

    /**
     * @Route("/client-get-policy-profile-details", name="client-get-policy-profile-details")
     */
    public function client_get_policy_profile_details_X()
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
        //$userid_array = $sescontrol->getUserid($token);
        //$userid = $token;

        $userid_array = $sescontrol->getUserid($token);
        $userid = $userid_array['userId'];

        //$record_id = $_GET['record_id'];

        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $lead_details = $repository->get_client_policy_client_profile($userid);

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
     * @Route("/client-get-policy-details", name="client-get-policy-details")
     */
    public function client_get_policy_details()
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
        $record_id = $_POST['record_id'];

        // $repository = $this->getDoctrine()
        //     ->getRepository(Clients::class);
        // $product_lead_details = $repository->get_lead_product_id($userid);

        if (trim($record_id) == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }

        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $resultz = $repository->get_client_policy_by_id($userid, $record_id);
        //var_dump($resultz);
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
        //$result = $resultz[0];
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
     * @Route("/client-fetch-client-policies", name="client-fetch-client-policies")
     */
    public function client_fetch_client_policies()
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
        //$record_id = $_GET['user-id-c'];

        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_client_policy_by_client_id($userid);

        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }

        $i = 1;
        $pendingString = '';
        foreach ($results as $result):
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
            $ops_service_response = $ops_service->get_client_policy_params($record_id, $status = 1);
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
     * @Route("/client-fetch-policies", name="client-fetch-policies")
     */
    public function client_fetch_policies()
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
        $results = $repository->get_all_client_policies_ovr_1($userid);

        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }

        $i = 1;
        $pendingString = '';
        foreach ($results as $result):
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
            $ops_service_response = $ops_service->get_client_policy_params($record_id, $status = 1);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];

            $returnarray['data'][] = array(
                //'<a class="badge rounded-pill bg-info mt-2"  href="#" >' . $user_name . '</a><br>',
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
     * @Route("/client-fetch-claim-policies", name="client-fetch-claim-policies")
     */
    public function client_fetch_claim_policies()
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
        //$record_id = $_GET['user-id-c'];

        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $results = $repository->get_client_policy_by_client_id($userid);

        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "No data found.";
            return $this->json($respondWith);
        }

        $i = 1;
        $pendingString = '';
        foreach ($results as $result):
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
            $ops_service_response = $ops_service->get_claim_policy_params($record_id, $status = 1);
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
     * @Route("/save-claim", name="save-claim")
     */
    public function save_claim()
    {
        /**files */
        $sescontrol = new SessionController;
        $ops_service = new OpsService;
        $em = $this->getDoctrine()->getManager();
        $storeFolder = '/public/uploads/claims/icons/';
        $retrieve_folder = '/uploads/claims/icons/';
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

        $policy_id = trim($_POST['policy_id']);
        $incident_date = trim($_POST['incident_date']);
        $description = trim($_POST['claim_desc']);

        if ($policy_id == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please reload page.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($incident_date == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please select incident date.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }
        if ($description == '') {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Please enter claim description.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }

        $prod_details = array(
            'userid' => $userid,
            'policy_id' => $policy_id,
            'incident_date' => $incident_date,
            'description' => $description,
        );
        $product_id = 0;
        $repository = $this->getDoctrine()
            ->getRepository(Clients::class);
        $existing_claim = $repository->check_claim_details($prod_details);

        if (!empty($existing_claim)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "Claim pre-reported, see claim listing.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }

        if (empty($existing_claim)) {
            $repository = $this->getDoctrine()
                ->getRepository(Clients::class);
            $update_prod_results = $repository->save_claim($prod_details);

            $product_id = $update_prod_results;

            if ($product_id > 0) {
                $file_datas = $this->save_local_files($product_id, $storeFolder, $retrieve_folder, $upl_path);
                if (!empty($file_datas)) {
                    foreach ($file_datas as $file_data):
                        $file_path = $file_data['file_path'];
                        $repository = $this->getDoctrine()
                            ->getRepository(Clients::class);
                        $repository->save_claim_images($file_data);
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
                $respondWith['messages'] = "Claim reported.";
                return $this->json($respondWith);
            }
        }

        $respondWith['status'] = 'ok';
        $respondWith['title'] = "null";
        $respondWith['messages'] = "Done.";
        return $this->json($respondWith);
    }

    /**
     * @Route("/get-client-claim-list", name="get-client-claim-list")
     */
    public function get_client_claim_list()
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
        $results = $repository->get_client_claim_data($userid);

        if (empty($results)) {
            $respondWith['status'] = 'false';
            $respondWith['title'] = "null";
            $respondWith['messages'] = "You have no data.";
            $respondWith['data'] = 0;
            return $this->json($respondWith);
        }

        $i = 1;
        $pendingString = '';
        foreach ($results as $result):
            $record_id = $result['record_id'];
            $product_name = $result['product_name'];
            $on_date = $result['on_date'];
            $claim_status = $result['claim_status'];
            $status_description = $result['status_description'];

            $on_date = date('dS-M Y', strtotime($on_date));
            
            $ops_service = new OpsService;
            $dropdown = '';
            $ops_service_response = $ops_service->get_claim_policy_params_ovr($record_id, $claim_status,$status_description);
            $dropdown = $ops_service_response['dropdown'];
            $unit_ui_display = $ops_service_response['unit_ui_display'];

            $returnarray['data'][] = array(
                //$record_id,
                $product_name,
                $on_date,
                $unit_ui_display,
                $dropdown,
            );
            
        endforeach;
        return $this->json($returnarray);
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
