<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use App\ShortUrl\ShortUrlUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Hashids\Hashids;
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
    /**
     * @var ShortUrlUpdater
     */
    private $shortUrlUpdater;

    public function __construct(ShortUrlRepository $shortUrlRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator, ShortUrlUpdater $shortUrlUpdater)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->shortUrlUpdater = $shortUrlUpdater;
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

        $token = substr(md5(microtime()),rand(0,26),4);
        $this->shortUrlUpdater->create($data->url, $token);

        $result['urlId'] = $token;
        return $this->json($result, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent());

        if(!isset($data->url)) {
            throw $this->createNotFoundException();
        }

        $this->shortUrlUpdater->update($id, $data->url);

        return $this->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $data = json_decode($request->getContent());

        if(!isset($data->id)) {
            throw $this->createNotFoundException();
        }

        $this->shortUrlUpdater->delete($data->id);

        return $this->json(['success' => true]);
    }

}
