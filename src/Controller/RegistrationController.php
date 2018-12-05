<?php

namespace App\Controller;

use App\ApiToken\Updater;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Updater
     */
    private $apiTokenUpdater;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Updater $apiTokenUpdater)
    {
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
