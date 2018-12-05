<?php

namespace App\Controller;

use App\Conversion\NumberConverter;
use App\Repository\ShortUrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /**
     * RedirectController constructor.
     * @param ShortUrlRepository $shortUrlRepository
     * @param NumberConverter $converter
     */
    public function __construct(ShortUrlRepository $shortUrlRepository, NumberConverter $converter)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->converter = $converter;
    }

    /**
     * @param $token
     * @return RedirectResponse
     */
    public function index($token): RedirectResponse
    {
        $token = $this->converter->decode($token, NumberConverter::TOKEN);

        $shortUrl = $this->shortUrlRepository->findOneBy(['token' => $token]);

        if(!$shortUrl) {
            throw $this->createNotFoundException();
        }

        return $this->redirect($shortUrl->getUrl());
    }
}
