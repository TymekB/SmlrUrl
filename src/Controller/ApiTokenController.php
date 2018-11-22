<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        if(!$apiToken) {
            $this->createNotFoundException();
        }

        $active = $apiToken->getActive();
        $apiToken->setActive(!$active);

        $this->em->flush();

        return $this->redirectToRoute('api_key');
    }
}
