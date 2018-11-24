<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use App\Repository\ShortUrlRepository;
use App\Security\ShortUrlVoter;
use App\ShortUrl\Exception\ShortUrlDataNotFound;
use App\ShortUrl\Exception\ShortUrlNotFoundException;
use App\ShortUrl\TokenDecorator;
use App\ShortUrl\Updater;
use App\ShortUrl\Validator;
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
    /**
     * @var TokenDecorator
     */
    private $shortUrlTokenDecorator;
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(ShortUrlRepository $shortUrlRepository, EntityManagerInterface $em, Updater $shortUrlUpdater, TokenDecorator $shortUrlTokenDecorator, Validator $validator)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->em = $em;
        $this->shortUrlUpdater = $shortUrlUpdater;
        $this->shortUrlTokenDecorator = $shortUrlTokenDecorator;
        $this->validator = $validator;
    }

    public function read()
    {
        $urls = $this->getUser()->getShortUrls();
        $this->shortUrlTokenDecorator->encodeTokenFromCollection($urls);

        return $this->json($urls);
    }

    public function readSingle($id)
    {
        $shortUrl = $this->shortUrlRepository->find($id);

        $this->denyAccessUnlessGranted(ShortUrlVoter::VIEW, $shortUrl);

        $this->shortUrlTokenDecorator->encodeToken($shortUrl);

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

        $shortUrl = $this->shortUrlUpdater->create($this->getUser(), $data->url);

        $this->shortUrlTokenDecorator->encodeToken($shortUrl);
        $result['token'] = $shortUrl->getToken();

        return $this->json($result, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent());
        /** @var ShortUrl $shortUrl */
        $shortUrl = $this->em->getRepository(ShortUrl::class)->find($id);

        if(!isset($data->url)) {
            throw new ShortUrlDataNotFound();
        }

        if(!$shortUrl) {
            throw new ShortUrlNotFoundException();
        }

        $this->denyAccessUnlessGranted(ShortUrlVoter::EDIT, $shortUrl);

        $this->shortUrlUpdater->update($shortUrl, $data->url);

        return $this->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $data = json_decode($request->getContent());
        /** @var ShortUrl $shortUrl */
        $shortUrl = $this->em->getRepository(ShortUrl::class)->find($data->id);

        if(!isset($data->id)) {
            throw new ShortUrlDataNotFound();
        }

        if(!$shortUrl) {
            throw new ShortUrlNotFoundException();
        }

        $this->denyAccessUnlessGranted(ShortUrlVoter::EDIT, $shortUrl);

        $this->shortUrlUpdater->delete($shortUrl);

        return $this->json(['success' => true]);
    }

}
