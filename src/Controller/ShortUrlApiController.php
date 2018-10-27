<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class ShortUrlApiController extends AbstractController
{
    /**
     * @var ShortUrlRepository
     */
    private $shortUrlRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ShortUrlRepository $shortUrlRepository, EntityManagerInterface $entityManager)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->entityManager = $entityManager;
    }

    public function read()
    {
        $urls = $this->shortUrlRepository->findAll();

        return $this->json($urls);
    }

    public function readSingle($id)
    {
        $url = $this->shortUrlRepository->find($id);

        return $this->json($url);
    }

    public function create(Request $request)
    {
        $data = json_decode($request->getContent());

        $shortUrl = new ShortUrl();
        $shortUrl->setUrl($data->url);
        $shortUrl->setUrlId(substr(md5(microtime()),rand(0,26),5));

        $this->entityManager->persist($shortUrl);
        $this->entityManager->flush();

        return $this->json($shortUrl->getUrlId());
    }

    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent());
        $shortUrl = $this->entityManager->getRepository(ShortUrl::class)->find($id);

        if(!$shortUrl) {
            return $this->createNotFoundException();
        }

        $shortUrl->setUrl($data->url);

        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $data = json_decode($request->getContent());
        $shortUrl = $this->shortUrlRepository->find($data->id);

        if(!$shortUrl) {
            return $this->createNotFoundException();
        }

        $this->entityManager->remove($shortUrl);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }

}
