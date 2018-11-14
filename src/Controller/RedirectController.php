<?php

namespace App\Controller;

use App\Conversion\NumberConverter;
use App\Repository\ShortUrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    /**
     * @var ShortUrlRepository
     */
    private $shortUrlRepository;
    /**
     * @var NumberConverter
     */
    private $converter;

    public function __construct(ShortUrlRepository $shortUrlRepository, NumberConverter $converter)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->converter = $converter;
    }

    public function index($token)
    {
        $token = $this->converter->decode($token, NumberConverter::TOKEN);

        $shortUrl = $this->shortUrlRepository->findOneBy(['token' => $token]);

        if(!$shortUrl) {
            throw $this->createNotFoundException();
        }

        return $this->redirect($shortUrl->getUrl());
    }
}
