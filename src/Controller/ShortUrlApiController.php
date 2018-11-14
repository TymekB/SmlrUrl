<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use App\Security\ShortUrlVoter;
use App\ShortUrl\Exception\ShortUrlDataNotFound;
use App\ShortUrl\Updater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlApiController extends AbstractController
{
    /**
     * @var ShortUrlRepository
     */
    private $shortUrlRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Updater
     */
    private $shortUrlUpdater;

    public function __construct(ShortUrlRepository $shortUrlRepository, EntityManagerInterface $em, Updater $shortUrlUpdater)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->em = $em;
        $this->shortUrlUpdater = $shortUrlUpdater;
    }

    public function read()
    {
        $urls = $this->getUser()->getShortUrls();

        return $this->json($urls);
    }

    public function readSingle($id)
    {
        $shortUrl = $this->shortUrlRepository->find($id);

        $this->denyAccessUnlessGranted(ShortUrlVoter::VIEW, $shortUrl);

        return $this->json($shortUrl);
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

        $user = $this->getUser();
        $this->shortUrlUpdater->create($data->url, $token, $user);

        $result['urlId'] = $token;
        return $this->json($result, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent());

        if(!isset($data->url)) {
            throw new ShortUrlDataNotFound();
        }

        $shortUrl = $this->em->getRepository(ShortUrl::class)->find($id);
        $this->denyAccessUnlessGranted(ShortUrlVoter::EDIT, $shortUrl);

        $this->shortUrlUpdater->update($shortUrl, $data->url);

        return $this->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $data = json_decode($request->getContent());

        if(!isset($data->id)) {
            throw new ShortUrlDataNotFound();
        }

        $shortUrl = $this->em->getRepository(ShortUrl::class)->find($data->id);
        $this->denyAccessUnlessGranted(ShortUrlVoter::EDIT, $shortUrl);

        $this->shortUrlUpdater->delete($shortUrl);

        return $this->json(['success' => true]);
    }

}
