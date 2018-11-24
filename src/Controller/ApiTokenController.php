<?php

namespace App\Controller;

use App\ApiToken\Updater;
use App\Entity\ApiToken;
use App\Form\ApiTokenType;
use App\Security\ApiTokenVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

    public function __construct(EntityManagerInterface $em, Updater $updater)
    {
        $this->em = $em;
        $this->updater = $updater;
    }

    public function create(Request $request)
    {
        $apiToken = new ApiToken();

        $form = $this->createForm(ApiTokenType::class, $apiToken);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $description = $form->get('description')->getData();

            $this->updater->create($user, $description);

            return $this->redirectToRoute('api_key');
        }

        return $this->render('api_token/create.html.twig', ['form' => $form->createView()]);

    }

    public function edit($id, Request $request)
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->em->getRepository(ApiToken::class)->find($id);

        if(!$apiToken) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted(ApiTokenVoter::EDIT, $apiToken);

        $form = $this->createForm(ApiTokenType::class, $apiToken);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->updater->update($apiToken);

            return $this->redirectToRoute('api_key');
        }

        return $this->render('api_token/edit.html.twig', ['form' => $form->createView()]);
    }

    public function switchActive($id, $option)
    {
        /** @var ApiToken $apiToken */
        $apiToken = $this->em->getRepository(ApiToken::class)->find($id);

        if(!$apiToken) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted(ApiTokenVoter::EDIT, $apiToken);

        $apiToken->setActive((bool)$option);
        $this->em->flush();

        return $this->redirectToRoute('api_key');
    }
}
