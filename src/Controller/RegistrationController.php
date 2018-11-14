<?php

namespace App\Controller;

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
     * @var ApiTokenGenerator
     */
    private $apiTokenGenerator;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ApiTokenGenerator $apiTokenGenerator)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->apiTokenGenerator = $apiTokenGenerator;
    }

    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);
            $this->apiTokenGenerator->generate($user);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "You've been successfully registered! You can now sign in.");

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/register.html.twig', ['form' => $form->createView()]);
    }
}
