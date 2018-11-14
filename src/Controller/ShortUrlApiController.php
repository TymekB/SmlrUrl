<?php

namespace App\Controller;

use App\Conversion\NumberConverter;
use App\Entity\ShortUrl;
use App\Entity\User;
use App\Repository\ShortUrlRepository;
use App\Security\ShortUrlVoter;
use App\ShortUrl\Exception\ShortUrlDataNotFound;
use App\ShortUrl\ShortUrlTokenDecorator;
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
    /**
     * @var NumberConverter
     */
    private $converter;
    /**
     * @var ShortUrlTokenDecorator
     */
    private $shortUrlTokenDecorator;

    public function __construct(ShortUrlRepository $shortUrlRepository, EntityManagerInterface $em, Updater $shortUrlUpdater, NumberConverter $converter, ShortUrlTokenDecorator $shortUrlTokenDecorator)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->em = $em;
        $this->shortUrlUpdater = $shortUrlUpdater;
        $this->converter = $converter;
        $this->shortUrlTokenDecorator = $shortUrlTokenDecorator;
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

        $randomNumber = mt_rand(10000000, 99999999);
        $shortUrl = $this->shortUrlUpdater->create($data->url, $randomNumber, $this->getUser());

        $this->shortUrlTokenDecorator->encodeToken($shortUrl);
        $result['token'] = $shortUrl->getToken();

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
