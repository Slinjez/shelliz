<?php
namespace App\Controller;
class NavGeneratorController
{
    public function defaultAdminNavigen($role = null)
    {
        $home = array(
            'navitem' => array(
                'navelem' => array(
                    'islone' => true,
                    'icon' => 'zwicon-home',
                    'Name' => 'HOME',
                    'href' => '/defaulthome',
                    'ul' => '',
                ),
            ));
        $defaultNav = array(
            'home' => $home,
        );       
        return $defaultNav;
    }
    public function defaultUserNavigen($role = null)
    {
        $home = array(
            'navitem' => array(
                'navelem' => array(
                    'islone' => true,
                    'icon' => 'zwicon-home',
                    'Name' => 'HOME',
                    'href' => '/home',
                    'ul' => '',
                ),
            ));
        $defaultNav = array(
            'home' => $home,
        );
        return $defaultNav;
    }
}
