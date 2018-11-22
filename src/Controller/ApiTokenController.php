<?php

namespace App\Controller;

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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        }

        return $this->render('api_token/edit.html.twig', ['form' => $form->createView()]);
    }
}
