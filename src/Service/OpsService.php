<?php
namespace App\Service;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class OpsService extends AbstractController
{
    private $session;
    protected $projectDir;

    // public function __construct(SessionInterface $session, KernelInterface $kernel)
    // {
    //     $this->session = $session;
    //     $this->projectDir = $kernel->getProjectDir();
    // }

    //  /**
    //  * @required
    //  */
    // public function setMailer(SessionInterface $session, KernelInterface $kernel): void
    // {

    /**
     * @required
     * @return static
     */
    public function withSession(SessionInterface $session, KernelInterface $kernel): self
    {
        echo 'calling with session';
        $this->session = $session;
        $this->projectDir = $kernel->getProjectDir();
    }

    public function getHappyMessage()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }

    public function generate_random_string($length = 6)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function send_email_reg_otp($last_id, $username, $user_mail, $html_msg)
    {
        $compute_array = array(
            'username' => '',
            'email' => $user_mail,
            'message-html' => $html_msg,
            'message-text' => $html_msg,
            'subject' => 'Registration OTP',
        );

        $mail_sender = $_ENV['email_sender'];
        $mail_sender_credentials = $_ENV['email_cred'];
        $ticket_email_sender = $_ENV['email_sender_name'];

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail = new PHPMailer(true);

            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $mail_sender;
            $mail->Password = $mail_sender_credentials;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipients
            $mail->setFrom($mail_sender, $ticket_email_sender);

            $mail->addAddress($user_mail, $username);

            //$mail->addBCC($ticket_email_receiver_email, $ticket_email_receiver_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $compute_array['subject'];
            $mail->Body = $compute_array['message-html'];
            $mail->AltBody = $compute_array['message-text'];

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function get_bank_compiled_params($bank_arrays)
    {
        $returnarray = array();
        foreach ($bank_arrays as $bank_array):
            $record_id = $bank_array['record_id'];
            $bank_name = $bank_array['bank_name'];
            $bank_branch = $bank_array['bank_branch'];
            $account_number = $bank_array['account_number'];
            $status = $bank_array['status'];
            $bank_params = $this->get_bank_status_params($record_id, $status);

            $returnarray['bank_data'][] = array(
                'record_id'=>$record_id,
                'bank_name'=>$bank_name,
                'bank_branch'=>$bank_branch,
                'account_number'=>$account_number,
                'bank_params'=>$bank_params,
            );
        endforeach;
        return $returnarray;
    }

    public function get_bank_status_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m "  attr-act=0 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-danger mt-2">Inactive</span>';
            $action_link = '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >Activate</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Active</span>';
            $action_link = '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
        "       <a class=\"dropdown-item action-button-veup\" href=\"javascript:void(0)\" attr-act=0 attr-id=". $record_id .">Update</a>" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }

    
    public function get_client_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m "  attr-act=0 attr-id=' . $record_id . '  href="/admin-view-client-profile?param='.$record_id.'" >View Profile</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-danger mt-2">Inactive</span>';
            $action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >Activate</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Active</span>';
            $action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }

    
    public function get_product_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="#" >Preview</a><br>';
        $action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-danger mt-2">Inactive</span>';
            $action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >Activate</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Active</span>';
            $action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
    
    public function get_call_back_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '';
        
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-danger mt-2">Inactive</span>';
            $action_link .= '<a class="dropdown-item m action-previewz"  attr-act=1 attr-id=' . $record_id . '  href="/admin-view-lead-conversion?param='.$record_id.'" >Lead Conversion</a><br>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >Activate</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-danger mt-2">Inactive</span>';
            
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">Unbooked Lead</span>';
            $action_link .= '<a class="dropdown-item m action-previewz"  attr-act=1 attr-id=' . $record_id . '  href="/admin-view-lead-conversion?param='.$record_id.'" >Lead Conversion</a><br>';
            
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Policy Booked</span>';
            $action_link .= '<a class="dropdown-item m action-button-status-togglez"  attr-act=0 attr-id=' . $record_id . '  href="#" >-View Policy</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
    
    public function get_product_compiled_params($prod_arrays)
    {
        $returnarray = array();
        foreach ($prod_arrays as $prod_array):
            $record_id = $prod_array['record_id'];
            $product_name = $prod_array['product_name'];
            $product_type_name = $prod_array['product_type_name'];
            $description = $prod_array['description'];
            $icon_path = $prod_array['icon_path'];
            $status = $prod_array['status'];
            $prod_params = $this->get_product_params($record_id, $status);

            $returnarray['prod_data'][] = array(
                'record_id'=>$record_id,
                'product_name'=>$product_name,
                'product_type_name'=>$product_type_name,
                'description'=>$description,
                'icon_path'=>$icon_path,
                'prod_params'=>$prod_params,
            );
        endforeach;
        return $returnarray;
    }

    
    public function get_ticket_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link='';
        //$action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="#" >More Details</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">In Queue</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >New Ticket</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-blue mt-2">Processing</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        } else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Closed</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }

    
    public function get_admin_ticket_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link='';
        //$action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="/admin-ticket-details?param='.$record_id.'" >More Details</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">In Queue</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >New Ticket</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-blue mt-2">Processing</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        } else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Closed</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
        
    public function get_policy_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="/admin-policy-details?param='.$record_id.'" >More Details</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">In Queue</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >New Ticket</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-blue mt-2">Processing</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        } else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Closed</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
    
    public function get_client_policy_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="/client-policy-details?param='.$record_id.'" >More Details</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">In Queue</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >New Ticket</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-blue mt-2">Processing</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        } else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Closed</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
    
    public function get_claim_policy_params($record_id, $status)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="/client-view-claim?param='.$record_id.'" >Report Claim</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">In Queue</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >New Ticket</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-blue mt-2">Processing</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        } else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">Closed</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
    
    public function get_claim_policy_params_ovr($record_id, $status,$status_description)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="/client-view-claim?param='.$record_id.'" >View Claim Details</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >New Ticket</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-blue mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        } else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-warning mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }else {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
    
    public function get_admin_claim_policy_params_ovr($record_id, $status,$status_description)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="/admin-claim-details?param='.$record_id.'" >View Claim Details</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >New Ticket</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-blue mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        } else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-warning mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }else {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
    
    public function get_admin_claim_policy_params_ovr_with_toggle($record_id, $status,$status_description)
    {
        $unit_ui_display = '<span class=" badge-info btn-sm radius-30 centered-text">Active</span>';
        $action_link = '<a class="dropdown-item m action-preview"  attr-act=1 attr-id=' . $record_id . '  href="/admin-claim-details?param='.$record_id.'" >View Claim Details</a><br>';
        $action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=2 attr-id=' . $record_id . '  href="#" >Set Processing</a><br>';
        $action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=3 attr-id=' . $record_id . '  href="#" >Set Declined</a><br>';
        $action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=4 attr-id=' . $record_id . '  href="#" >Set Paid Up</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        //$action_link.= '<a class="dropdown-item m action-edit"  attr-act=1 attr-id=' . $record_id . '  href="#" >Edit</a><br>';
        if ($status == 0) {
            $unit_ui_display = '<span class="badge rounded-pill bg-info mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=1 attr-id=' . $record_id . '  href="#" >New Ticket</a><br>';
        } else if ($status == 1) {
            $unit_ui_display = '<span class="badge rounded-pill bg-blue mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        } else if ($status == 2) {
            $unit_ui_display = '<span class="badge rounded-pill bg-warning mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }else {
            $unit_ui_display = '<span class="badge rounded-pill bg-success mt-2">'.$status_description.'</span>';
            //$action_link .= '<a class="dropdown-item m action-button-status-toggle"  attr-act=0 attr-id=' . $record_id . '  href="#" >De-Activate</a><br>';
        }
        $dropdown = "<div class=\"dropdown\">" .
        " <button type=\"button\" class=\"btn btn-info dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">" .
        "   <i class=\"fas fa-info-circle me-2\"></i>Action" .
        " </button>" .
        "    <div class=\"dropdown-menu\" style=\"\">" .
                $action_link .
        "    </div>" .
        " </div>";
        $response = array(
            //'check_box'=>$check_box,
            'dropdown' => $dropdown,
            'unit_ui_display' => $unit_ui_display,
        );
        return $response;
    }
}
