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

class AngularApiController extends AbstractController
{
    /**
     * @Route("/angular-api-base", name="angular_api")
     */
    public function index(): Response
    {
        $respondWith['status'] = 'ok';
        $respondWith['messages'] = "Base response on";
        $respondWith['sendto'] = 1;
        return $this->json($respondWith);
    }

    
    /**
     * @Route("/angular-api-get-product-list", name="angular get-product-list")
     */
    public function get_product_list()
    {
        $sescontrol = new SessionController;
        $returnarray = array();
        /* $userid = '';
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
        $userid = $userid_array['userId']; */
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
}
