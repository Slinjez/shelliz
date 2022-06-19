<?php

namespace App\Controller;

use App\Controller\SessionController;
use App\Service\OpsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private $session;
    protected $projectDir;
    public function __construct(SessionInterface $session, KernelInterface $kernel)
    {
        $this->session = $session;
        $this->projectDir = $kernel->getProjectDir();
    }
     /**
     * @Route("/", name="index slash")
     * @Route("/home", name="index home")
     * @Route("/index", name="index index")
     * @Route("/client-auth", name="index client auth")
     */
    public function index(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;

        $pageinfo = array(
            'page_name' => 'Client Home',
            'page_description' => 'Welcome to Shelliz Insurance!',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/dashboard.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/fe-auth-forgot-password", name="fe-auth-forgot-password auth")
     */
    public function view_client_forgot_password(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Client Home',
            'page_description' => 'Welcome to Shelliz Insurance!',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/forgot-password.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/forgot-password.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/forgot-password.html.twig');
        } else {
            return $this->render('client_side/forgot-password.html.twig');
        }
    }
    /**
     * @Route("/client-registration", name="client_side registration")
     */
    public function view_client_registration(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Client Registration',
            'page_description' => 'Welcome to Shelliz Insurance!',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/register.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/register.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/register.html.twig');
        } else {
            return $this->render('client_side/register.html.twig');
        }
    }
    
    /**
     * @Route("/client-dash", name="client_side dashboard")
     */
    public function view_client_dash(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;

        $pageinfo = array(
            'page_name' => 'Client Home',
            'page_description' => 'Welcome to Shelliz Insurance!',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/dashboard.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/client-vw-own-profile", name="client-vw-own-profile client-vw-items")
     */
    public function view_client_own_profile(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $sesok = $this->verifySession();
        
        // $repository = $this->getDoctrine()
        //         ->getRepository(Clients::class);
        // $validity = $repository->login($email, $password);

        $pageinfo = array(
            'page_name' => 'Client Profile',
            'page_description' => 'Manage my profile',
        );


        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/view_profile.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/client-vw-edit-profile", name="client-vw-edit-profile client-vw-edit-profile")
     */
    public function view_client_edit_profile(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Update My Profile',
            'page_description' => 'Update my profile',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/edit_profile.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }

    /**
     * @Route("/client-vw-product-listing", name="client-vw-product-listing client-vw-product-listing")
     */
    public function view_client_product_listing(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Product Listing',
            'page_description' => 'Product Listing',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/product_listing.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }

    /**
     * @Route("/client-vw-request-call-back", name="client-vw-request-call-back")
     */
    public function view_client_request_callback(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Request Callback',
            'page_description' => 'Request Callback',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/request_callback.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }

    /**
     * @Route("/client-vw-tickets", name="client-vw-vw-tickets")
     */
    public function view_client_view_tickets(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Tickets',
            'page_description' => 'Tickets',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/tickets.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/client-policy-details", name="client-view-policy-details")
     */
    public function view_client_policy_details(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Client Policies',
            'page_description' => 'View Policy details!',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/view_policy_details.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/client-view-policies", name="client-view-policies")
     */
    public function view_client_policies(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Client Policies',
            'page_description' => 'View Policies!',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/policy_list.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/client-vw-new-claim", name="client-view-claim")
     */
    public function view_client_new_claim(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Client Claims',
            'page_description' => 'New Claim',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/new-claim.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/client-report-claim", name="client-report-claim-vw")
     */
    public function view_client_report_claim(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Client Claims',
            'page_description' => 'New Claim',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/create_claim.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/client-view-claim-listing", name="client-view-claim-listing")
     */
    public function view_client_view_claim_list(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Client Claims',
            'page_description' => 'Claim List',
        );
        $sesok = $this->verifySession();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 1) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('client_side/claim_list.html.twig', $pageinfo);
            } else {
                return $this->render('client_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('client_side/index.html.twig');
        } else {
            return $this->render('client_side/index.html.twig');
        }
    }
    

    /**
     *
     * ADMIN
     *
     */





    /**
     * @Route("/admin-auth", name="admin_side auth")
     */
    public function view_admin_auth(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Home',
            'page_description' => 'Welcome to Shelliz!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/dashboard.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }

    /**
     * @Route("/resetpsaction-adm", name="Shared resetpsaction")
     */
    public function reset_ps_action_adm()
    {
        $OpsService = new OpsService;
        $workemail = trim($_POST['reset_email_btn']);
        $ps1 = trim($_POST['ps1']);
        $ps2 = trim($_POST['ps2']);
        $uppercase = preg_match('@[A-Z]@', $ps1);
        $lowercase = preg_match('@[a-z]@', $ps1);
        $number = preg_match('@[0-9]@', $ps1);
        $specialChars = preg_match('@[^\w]@', $ps1);
        if ($workemail == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your Work Email Addres.";
            return $this->json($respondWith);
            exit;
        } else if ($ps1 == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter your Password.";
            return $this->json($respondWith);
            exit;
        } else if (!$uppercase || !$lowercase || !$number || strlen($ps1) < 5) {
            $respondWith['status'] = 'fail';
            $respondWith['title'] = 'Error';
            $respondWith['messages'] = "Password should be at least 5 characters long and should include at least one upper case letter or one number. You can use one or more special character.";
            return $this->json($respondWith);
            exit;
        } else if ($ps1 != $ps2) {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Your passwords mis-match.";
            return $this->json($respondWith);
            exit;
        } else {
            $password = hash('ripemd160', $ps1);
            $repository = $this->getDoctrine()
                ->getRepository(Users::class);
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
                    ->getRepository(Users::class);
                $user_id = $repository->update_user_password($save_data);
                $saved = 1;
                $last_id = $saved;
                if ($saved > 0) {
                    $html_msg = $this->renderView('emails/email-otp.html.twig', [
                        'title' => "Document Render",
                        'client_name' => $validity[0]['username'],
                        'otp' => $reset_code,
                    ]);
                    $message = $OpsService->send_email_reg_otp($last_id, $validity[0]['username'], $workemail, $html_msg);
                    $obsfcate_email = preg_replace("/(?!^).(?=[^@]+@)/", "*", $workemail);
                    $respondWith['status'] = 'ok';
                    $respondWith['messages'] = "Hello " . $validity[0]['username'] . ". Please enter the OTP we sent to your email " . $obsfcate_email . ".";
                    return $this->json($respondWith);
                    exit;
                } else {
                    $respondWith['status'] = 'fail';
                    $respondWith['messages'] = "We already have your account. Kindly login.";
                    return $this->json($respondWith);
                    exit;
                }
            }
        }
    }
    /**
     * @Route("/activate-otp-adm", name="Shared activate-otp")
     */
    public function activate_otp_adm()
    {
        $OpsService = new OpsService;
        $ver_otp = trim($_POST['ver_otp']);
        $assocemail = trim($_POST['assocemail']);
        if ($assocemail == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Error occured. Please contact support.";
            return $this->json($respondWith);
            exit;
        } else if ($ver_otp == '') {
            $respondWith['status'] = 'fail';
            $respondWith['messages'] = "Please enter the OTP we sent to your email.";
            return $this->json($respondWith);
            exit;
        } else {
            $repository = $this->getDoctrine()
                ->getRepository(Logins::class);
            $set_otp = $repository->get_acc_otp($assocemail);
            if (empty($set_otp)) {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "OTP purged. Please contact support.";
                return $this->json($respondWith);
            }
            if ($set_otp[0]['temp_otp'] == '') {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "OTP purged. Please contact support.";
                return $this->json($respondWith);
            }
            if ($ver_otp == $set_otp[0]['temp_otp']) {
                $repository = $this->getDoctrine()
                    ->getRepository(Logins::class);
                $repository->activat_acc_otp($assocemail);
                $respondWith['status'] = 'ok';
                $respondWith['messages'] = "You have activated your account! Now log in.";
                return $this->json($respondWith);
            } else {
                $respondWith['status'] = 'fail';
                $respondWith['messages'] = "You have entred a wrong OTP.";
                return $this->json($respondWith);
            }
        }
    }
    /**
     * @Route("/admin-dash", name="admin_side dashboard")
     */
    public function view_admin_dash(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Home',
            'page_description' => 'Welcome to Shelliz!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/dashboard.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    /**
     * @Route("/admin-view-clients", name="admin_view-clients")
     */
    public function view_admin_clients(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Clients',
            'page_description' => 'Welcome to Shelliz!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/clients.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    /**
     * @Route("/admin-view-client-profile", name="admin_view-client-profile")
     */
    public function view_admin_client_profile(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Client profile',
            'page_description' => 'Welcome to Shelliz!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/view_client_profile.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    /**
     * @Route("/admin-product-listing", name="admin_view-product-list")
     */
    public function view_admin_product_listing(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Products',
            'page_description' => 'Welcome to Shelliz!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/product_list.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    /**
     * @Route("/admin-callback-requests", name="admin_callback-requests")
     */
    public function view_admin_callback_requests(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Callback Request',
            'page_description' => 'Callback Request!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/client_callback.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    /**
     * @Route("/admin-ticket-listing", name="admin-ticket-listing")
     */
    public function view_admin_ticket_listing(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Ticket Listing',
            'page_description' => 'Admin Ticket Listing.',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/tickets.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/admin-ticket-details", name="admin-ticket-details")
     */
    public function view_admin_ticket_details(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Ticket Details',
            'page_description' => 'Admin Ticket Details.',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/ticket_details.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/admin-view-lead-conversion", name="admin_view-lead-conversion")
     */
    public function view_admin_lead_conversion(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Lead Conversion',
            'page_description' => 'Lead Conversion!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/view_lead_conversion.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/admin-view-policies", name="admin-view-policies")
     */
    public function view_admin_policies(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Policies',
            'page_description' => 'View Policies!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/policy_list.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/admin-view-claim-list", name="admin-view-claim-list")
     */
    public function view_admin_claim_list(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Claims',
            'page_description' => 'View Claims!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/claim_list.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/admin-policy-details", name="admin-view-policy-details")
     */
    public function view_admin_policy_details(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Policies',
            'page_description' => 'View Policy details!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/view_policy_details.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/admin-claim-details", name="admin-view-claim-details")
     */
    public function view_admin_claim_details(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin claims',
            'page_description' => 'View claim details!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/view_claim_details.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/admin-vw-reports-users", name="admin-vw-reports-users")
     */
    public function view_admin_vw_reports_users(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Reports',
            'page_description' => 'View User Statistics!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/view_user_stats.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }
    
    /**
     * @Route("/admin-vw-reports-prod-claims", name="admin-vw-reports-prod-claims")
     */
    public function view_admin_vw_reports_prod_claims(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Reports',
            'page_description' => 'View Products and claims!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/view_prod_claims_stats.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }

    /**
     * @Route("/admin-vw-reports-prod-uptake", name="admin-vw-reports-prod-uptake")
     */
    public function view_admin_vw_reports_prod_uptake(): Response
    {
        $navGen = new NavGeneratorController;
        $ops_service = new OpsService;
        $pageinfo = array(
            'page_name' => 'Admin Reports',
            'page_description' => 'View Products Market Penetration!',
        );
        $sesok = $this->verify_admin_session();
        if ($sesok['status'] == 'ok') {
            if ($sesok['userRole'] == 2) {
                $nav = $navGen->defaultUserNavigen($role = null);
                $pageinfo['nav'] = $nav;
                return $this->render('admin_side/view_prod_update_stats.html.twig', $pageinfo);
            } else {
                return $this->render('admin_side/index.html.twig');
            }
        } else if ($sesok['status'] == 'expired') {
            return $this->render('admin_side/index.html.twig');
        } else {
            return $this->render('admin_side/index.html.twig');
        }
    }

    public function verifySession()
    {
        $sescontrol = new SessionController;
        $session = $this->session;
        $sesStatus = 0;
        $userid = 0;
        $token = '';
        if (!$this->session) {
            $sesStatus = 0;
        } else {
            $userid = $this->session->get('dropshopuid');
            $token = $this->session->get('token');
            if ($token == '') {
                $sesStatus = 0;
            } else {
                $sesStatus = 1;
            }
            if ($userid == '' || $userid == null) {
                $respondWith['status'] = 'killsess';
                $respondWith['messages'] = "bad session.";
                $retval = array(
                    'in pos' => 'failed in 2 1<br>',
                    'status' => 'badsession',
                    'minutes' => 0,
                );
                return $retval;
                exit;
            } else {
                $sesStatus = 1;
            }
        }
        if ($token != '') {
            $sesstatus = $sescontrol->verifyJwt($token);
            if (isset($sesstatus['exp'])) {
                $sestill = $sesstatus['exp'];
                $sestill = date('Y-m-d H:i:s', strtotime($sestill));
                $timme = date('Y-m-d H:i:s');
                $diff = (new \DateTime($timme))->diff(new \DateTime($sestill));
                $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                $retval = array(
                    'in pos' => 'failed in 1 1<br>',
                    'status' => 'expired',
                    'minutes' => 0,
                );
            }
            if ($sesstatus['status'] == 'ok') {
                $respondWith['status'] = 'ok';
                $respondWith['messages'] = "active session. Till:" . $sesstatus['exp'];
                $respondWith['token'] = $sesstatus['token'];
                $respondWith['userRole'] = $sesstatus['userRole'];
                $retval = array(
                    'in pos' => 'passed in 3 1<br>',
                    'status' => 'ok',
                    'minutes' => $minutes,
                    'userRole' => $sesstatus['userRole'],
                );
            } else {
                $respondWith['status'] = 'killsess';
                $respondWith['messages'] = "bad session.";
                $retval = array(
                    'in pos' => 'failed in 2 1<br>',
                    'status' => 'badsession',
                    'minutes' => 0,
                );
            }
        } else {
            $respondWith['status'] = 'false';
            $respondWith['messages'] = "Not logged in.";
            $retval = array(
                'in pos' => 'failed in 3 1<br>',
                'status' => 'nosession',
                'minutes' => 0,
            );
        }
        return $retval;
    }
    public function verify_admin_session()
    {
        $sescontrol = new SessionController;
        $session = $this->session;
        $sesStatus = 0;
        $userid = 0;
        $token = '';
        if (!$this->session) {
            $sesStatus = 0;
        } else {
            $userid = $this->session->get('dsladminuid');
            $token = $this->session->get('token');
            if ($token == '') {
                $sesStatus = 0;
            } else {
                $sesStatus = 1;
            }
            if ($userid == '' || $userid == null) {
                $respondWith['status'] = 'killsess';
                $respondWith['messages'] = "bad session.";
                $retval = array(
                    'in pos' => 'failed in 2 1<br>',
                    'status' => 'badsession',
                    'minutes' => 0,
                );
                return $retval;
                exit;
            } else {
                $sesStatus = 1;
            }
        }
        if ($token != '') {
            $sesstatus = $sescontrol->verifyJwt($token);
            if (isset($sesstatus['exp'])) {
                $sestill = $sesstatus['exp'];
                $sestill = date('Y-m-d H:i:s', strtotime($sestill));
                $timme = date('Y-m-d H:i:s');
                $diff = (new \DateTime($timme))->diff(new \DateTime($sestill));
                $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                $retval = array(
                    'in pos' => 'failed in 1 1<br>',
                    'status' => 'expired',
                    'minutes' => 0,
                );
            }
            if ($sesstatus['status'] == 'ok') {
                $respondWith['status'] = 'ok';
                $respondWith['messages'] = "active session. Till:" . $sesstatus['exp'];
                $respondWith['token'] = $sesstatus['token'];
                $respondWith['userRole'] = $sesstatus['userRole'];
                $retval = array(
                    'in pos' => 'passed in 3 1<br>',
                    'status' => 'ok',
                    'minutes' => $minutes,
                    'userRole' => $sesstatus['userRole'],
                );
            } else {
                $respondWith['status'] = 'killsess';
                $respondWith['messages'] = "bad session.";
                $retval = array(
                    'in pos' => 'failed in 2 1<br>',
                    'status' => 'badsession',
                    'minutes' => 0,
                );
            }
        } else {
            $respondWith['status'] = 'false';
            $respondWith['messages'] = "Not logged in.";
            $retval = array(
                'in pos' => 'failed in 3 1<br>',
                'status' => 'nosession',
                'minutes' => 0,
            );
        }
        return $retval;
    }
}
