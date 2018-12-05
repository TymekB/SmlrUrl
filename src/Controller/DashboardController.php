<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    public function history(): Response
    {
        return $this->render('dashboard/history.html.twig');
    }

    public function apiKey(): Response
    {
        $apiKeys = $this->getUser()->getApiTokens();

        return $this->render('dashboard/api_key.html.twig', ['apiKeys' => $apiKeys]);
    }
}
