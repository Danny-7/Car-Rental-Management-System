<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param CartService $cartService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }
}
