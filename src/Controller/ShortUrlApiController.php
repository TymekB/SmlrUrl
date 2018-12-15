<?php

namespace App\Controller;

use App\Dto\ShortUrlDto;
use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use App\Security\ShortUrlVoter;
use App\ShortUrl\Exception\ShortUrlDataNotFound;
use App\ShortUrl\TokenDecorator;
use App\ShortUrl\Updater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    /**
     * @var TokenDecorator
     */
    private $shortUrlTokenDecorator;

    /**
     * ShortUrlApiController constructor.
     * @param ShortUrlRepository $shortUrlRepository
     * @param EntityManagerInterface $em
     * @param Updater $shortUrlUpdater
     * @param TokenDecorator $shortUrlTokenDecorator
     */
    public function __construct(ShortUrlRepository $shortUrlRepository, EntityManagerInterface $em, Updater $shortUrlUpdater, TokenDecorator $shortUrlTokenDecorator)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->em = $em;
        $this->shortUrlUpdater = $shortUrlUpdater;
        $this->shortUrlTokenDecorator = $shortUrlTokenDecorator;
    }

    /**
     * @return JsonResponse
     */
    public function read(): JsonResponse
    {
        $urls = $this->getUser()->getShortUrls();
        $this->shortUrlTokenDecorator->encodeTokenFromCollection($urls);

        return $this->json($urls);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \App\ShortUrl\Exception\ShortUrlNotFoundException
     */
    public function readSingle($id): JsonResponse
    {
        $shortUrl = $this->shortUrlRepository->getOneById($id);

        $this->denyAccessUnlessGranted(ShortUrlVoter::VIEW, $shortUrl);

        $this->shortUrlTokenDecorator->encodeToken($shortUrl);

        return $this->json($shortUrl);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ShortUrlDataNotFound
     * @throws \App\ShortUrl\Exception\ShortUrlIsNotValidException
     */
    public function create(Request $request): JsonResponse
    {
        $result = ['success' => true, 'urlId' => ''];
        $data = json_decode($request->getContent());

        if(!isset($data->url)) {
            throw new ShortUrlDataNotFound();
        }

        $shortUrlDto = new ShortUrlDto();
        $shortUrlDto->setUrl($data->url)->setUser($this->getUser());

        $shortUrl = $this->shortUrlUpdater->create($shortUrlDto);

        $this->shortUrlTokenDecorator->encodeToken($shortUrl);
        $result['token'] = $shortUrl->getToken();

        return $this->json($result, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ShortUrlDataNotFound
     * @throws \App\ShortUrl\Exception\ShortUrlIsNotValidException
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent());
        /** @var ShortUrl $shortUrl */
        $shortUrl = $this->em->getRepository(ShortUrl::class)->getOneById($id);

        if(!isset($data->url)) {
            throw new ShortUrlDataNotFound();
        }

        $this->denyAccessUnlessGranted(ShortUrlVoter::EDIT, $shortUrl);

        $shortUrlDto = new ShortUrlDto();
        $shortUrlDto->setUrl($data->url);

        $this->shortUrlUpdater->update($shortUrl, $shortUrlDto);

        return $this->json(['success' => true]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ShortUrlDataNotFound
     */
    public function delete(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        if(!isset($data->id)) {
            throw new ShortUrlDataNotFound();
        }

        /** @var ShortUrl $shortUrl */
        $shortUrl = $this->em->getRepository(ShortUrl::class)->getOneById($data->id);

        $this->denyAccessUnlessGranted(ShortUrlVoter::EDIT, $shortUrl);

        $this->shortUrlUpdater->delete($shortUrl);

        return $this->json(['success' => true]);
    }

}
