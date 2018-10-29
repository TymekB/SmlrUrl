<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ShortUrlRepository $shortUrlRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
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
        $result = ['success' => true, 'urlId' => ''];
        $data = json_decode($request->getContent());

        if(!isset($data->url)) {
            $result['success'] = false;
            return $this->json($result, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shortUrl = new ShortUrl();
        $shortUrl->setUrl($data->url);
        $shortUrl->setUrlId(substr(md5(microtime()),rand(0,26),4));

        $errors = $this->validator->validate($shortUrl);

        if(count($errors) > 0) {
            $result['success'] = false;
            return $this->json($result, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->entityManager->persist($shortUrl);
        $this->entityManager->flush();

        $result['urlId'] = $shortUrl->getUrlId();

        return $this->json($result, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $result = ['success' => true];
        $data = json_decode($request->getContent());

        $shortUrl = $this->entityManager->getRepository(ShortUrl::class)->find($id);

        if(!$shortUrl || !isset($data->url)) {
            $result['success'] = false;
            return $this->json($result, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shortUrl->setUrl($data->url);
        $errors = $this->validator->validate($shortUrl);

        if(count($errors) > 0) {
            $result['success'] = false;
            return $this->json($result, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->entityManager->flush();

        return $this->json($result);
    }

    public function delete(Request $request)
    {
        $result = ['success' => true];
        $data = json_decode($request->getContent());

        $shortUrl = $this->shortUrlRepository->find($data->id);

        if(!$shortUrl || !isset($data->id)) {
            return $this->json($result, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->entityManager->remove($shortUrl);
        $this->entityManager->flush();

        return $this->json($result);
    }

}
