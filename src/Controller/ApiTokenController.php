<?php

namespace App\Controller;

use App\ApiToken\Updater;
use App\Dto\ApiTokenDto;
use App\Entity\ApiToken;
use App\Form\ApiTokenType;
use App\Security\ApiTokenVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Updater
     */
    private $updater;

    /**
     * ApiTokenController constructor.
     * @param EntityManagerInterface $em
     * @param Updater $updater
     */
    public function __construct(EntityManagerInterface $em, Updater $updater)
    {
        $this->em = $em;
        $this->updater = $updater;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        $apiTokenDto = new ApiTokenDto();

        $form = $this->createForm(ApiTokenType::class, $apiTokenDto);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $apiTokenDto->setUser($this->getUser());
            $this->updater->create($apiTokenDto);

            return $this->redirectToRoute('api_key');
        }

        return $this->render('api_token/create.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request): Response
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->em->getRepository(ApiToken::class)->getOneById($id);

        $this->denyAccessUnlessGranted(ApiTokenVoter::EDIT, $apiToken);

        $form = $this->createForm(ApiTokenType::class, $apiToken);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->updater->update($apiToken);

            return $this->redirectToRoute('api_key');
        }

        return $this->render('api_token/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param $id
     * @param $option
     * @return RedirectResponse
     */
    public function switchActive($id, $option): RedirectResponse
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->em->getRepository(ApiToken::class)->getOneById($id);

        $this->denyAccessUnlessGranted(ApiTokenVoter::EDIT, $apiToken);

        $apiToken->setActive((bool)$option);
        $this->em->flush();

        return $this->redirectToRoute('api_key');
    }
}
