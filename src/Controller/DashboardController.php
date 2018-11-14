<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    public function history()
    {
        return $this->render('dashboard/history.html.twig');
    }

    public function apiKey()
    {
        $apiKey = $this->getUser()->getApiToken();

        return $this->render('dashboard/api_key.html.twig', ['apiKey' => $apiKey]);
    }
}
