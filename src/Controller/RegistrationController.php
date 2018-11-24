<?php

namespace App\Controller;

use App\ApiToken\Updater;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\UserType;
use App\User\ApiTokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Updater
     */
    private $apiTokenUpdater;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, Updater $apiTokenUpdater)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->apiTokenUpdater = $apiTokenUpdater;
    }

    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->apiTokenUpdater->create($user);

            $this->addFlash('success', "You've been successfully registered! You can now sign in.");

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/register.html.twig', ['form' => $form->createView()]);
    }
}
