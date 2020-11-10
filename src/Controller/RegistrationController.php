<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param UserAuthenticator $userAuthenticator
     * @return Response
     */
    public function register(Request $request,EntityManagerInterface $em,
                             UserPasswordEncoderInterface $encoder,
                             GuardAuthenticatorHandler $guardAuthenticatorHandler,
                             UserAuthenticator $userAuthenticator) :Response
    {
        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('cars');
        }
        $user = new User();

        $registerForm = $this->createForm(RegistrationType::class, $user);

        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $em->persist($user);
            $em->flush();
            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess($user,
                $request,
                $userAuthenticator,
                'main');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $registerForm->createView()
        ]);
    }
}
