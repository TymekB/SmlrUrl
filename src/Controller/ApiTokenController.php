<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Form\ApiTokenType;
use App\Security\ApiTokenVoter;
use App\User\ApiTokenGenerator;
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
     * @var ApiTokenGenerator
     */
    private $apiTokenGenerator;

    public function __construct(EntityManagerInterface $em, ApiTokenGenerator $apiTokenGenerator)
    {
        $this->em = $em;
        $this->apiTokenGenerator = $apiTokenGenerator;
    }
    
    public function switchActive($id)
    {
        $apiToken = $this->em->getRepository(ApiToken::class)->find($id);
        $this->denyAccessUnlessGranted(ApiTokenVoter::EDIT, $apiToken);

        $active = $apiToken->getActive();
        $apiToken->setActive(!$active);

        $this->em->flush();

        return $this->redirectToRoute('api_key');
    }

    public function edit($id, Request $request)
    {
        $apiToken = $this->em->getRepository(ApiToken::class)->find($id);
        $this->denyAccessUnlessGranted(ApiTokenVoter::EDIT, $apiToken);

        $form = $this->createForm(ApiTokenType::class, $apiToken);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirect('api_token');
        }

        return $this->render('api_token/edit.html.twig', ['form' => $form->createView()]);
    }

    public function create(Request $request)
    {
        $apiToken = new ApiToken();

        $form = $this->createForm(ApiTokenType::class, $apiToken);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->apiTokenGenerator->generate($this->getUser(), $apiToken, $form->get('description')->getData());

            $this->em->persist($apiToken);
            $this->em->flush();

            return $this->redirectToRoute('api_key');
        }

        return $this->render('api_token/create.html.twig', ['form' => $form->createView()]);

    }
}
