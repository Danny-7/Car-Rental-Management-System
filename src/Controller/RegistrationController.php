<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app.register")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request,EntityManagerInterface $em,
                             UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $registerForm = $this->createForm(RegistrationType::class, $user);

        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $em->persist($user);
            $em->flush($user);

            $this->redirectToRoute("app.login");
        }

        return $this->render('registration/index.html.twig', [
            'form' => $registerForm->createView()
        ]);
    }
}
