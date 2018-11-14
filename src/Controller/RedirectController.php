<?php

namespace App\Controller;

use App\Repository\ShortUrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    /**
     * @var ShortUrlRepository
     */
    private $shortUrlRepository;

    public function __construct(ShortUrlRepository $shortUrlRepository)
    {
        $this->shortUrlRepository = $shortUrlRepository;
    }

    public function index($urlId)
    {
        $shortUrl = $this->shortUrlRepository->findOneBy(['token' => $urlId]);

        if(!$shortUrl) {
            throw $this->createNotFoundException();
        }

        return $this->redirect($shortUrl->getUrl());
    }
}
