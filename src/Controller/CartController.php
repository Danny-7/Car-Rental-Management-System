<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     * @param CartService $cartService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(CartService $cartService)
    {
        $cart = $cartService->getFullCart();
        $totalItems = $cartService->getTotalAmount();
        return $this->render('cart/index.html.twig', [
            'items' => $cart,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart.add")
     * @param $id
     * @param CartService $cartService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function add($id, CartService $cartService)
    {
        $cartService->add($id);
        return $this->redirectToRoute('cars');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart.remove")
     * @param $id
     * @param CartService $cartService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove($id, CartService $cartService)
    {

        $cartService->remove($id);
        return $this->redirectToRoute('cart');
    }
}
