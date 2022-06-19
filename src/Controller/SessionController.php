<?php
/** Autoloading The required Classes **/
namespace App\Controller;
use App\Controller\JWT;
class SessionController
{
    public function getJwt($userid,$role)
    {
        $serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';
        $sesstimenbf = new \DateTime();
        $sesstime = new \DateTime();
        $sesstime->modify("+1 hour +30 minutes");
        $sestime = $sesstime->format('Y-m-d H:i:s');
        $sesstimenbf = $sesstimenbf->format('Y-m-d H:i:s');
        $nbf = $sesstimenbf;
        $exp = $sestime;
        $payloadArray = array();
        $payloadArray['userId'] = $userid;
        $payloadArray['userRole'] = $role;
        if (isset($nbf)) {$payloadArray['nbf'] = $nbf;}
        if (isset($exp)) {$payloadArray['exp'] = $exp;}
        try{            
            $token = JWT::encode($payloadArray, $serverKey);
        }catch(Exception $e){
            ;
        }
        return $token;
    }

    public function verifyJwt($token)
    {
        $retval = 'ok';
        if (!is_null($token)) {
            $sesstimenbf = new \DateTime();
            $sesstime = new \DateTime();
            $serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';
            $sesstime->modify("+30 minutes");
            $sestime = $sesstime->format('Y-m-d H:i:s');
            $sesstimenbf = $sesstimenbf->format('Y-m-d H:i:s');
            $nbf = $sesstimenbf;
            $exp = $sestime;
            $userid = 0;
            $returnArray['token'] = '';
            try {
                $returnArray['exp'] = '';
                $payload = JWT::decode($token, $serverKey, array('HS256'));
                $userid = $payload->userId;
                $returnArray = array(
                    'userId' => $payload->userId,
                    'userRole' => $payload->userRole,
                );
                if(isset($payload->sts)){
                    if($payload->sts=='expired token'){
                        $returnArray = array('error' => 'expired token.');
                        $returnArray['status'] = 'expired';
                    }
                } else{
                    if (isset($payload->nbf)&&isset($payload->exp)) {
                        $returnArray['status'] = 'ok';
                        $returnArray['exp'] = date(\DateTime::ISO8601, $payload->exp);
                        $returnArray['token'] = $token;
                    }
                }
            } catch (Exception $e) {
                $returnArray = array('error' => $e->getMessage());
                $returnArray['status'] = 'fail';
            }
        } else {
            $returnArray = array('error' => 'You are not logged in with a valid token.');
            $returnArray['status'] = 'fail';
        }
        return $returnArray;
    }

    public function getUserid($token)
    {
        $serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';
        $userid = 0;        
        try {
            $payload = JWT::decode($token, $serverKey, array('HS256'));
            if(!$payload){
                return false;
                exit;
            }else{
                $returnArray = array(
                    'userId' => $payload->userId,
                    'userRole' => $payload->userRole
                );
                $userid = $payload->userId; 
            }          
        } catch (Exception $e) {
            $returnArray = array(
                'userId' => 0,
                'userRole' => 0
            );
        } 
        return $returnArray;
    }
    
    public function generateRandomString()
    {
        try{            
            $String = random_bytes(25);
        }catch(Exception $e){
            ;
        }
        return bin2hex($String);
    }
}
