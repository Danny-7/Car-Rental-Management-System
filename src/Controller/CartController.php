<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package App\Controller
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    private CartService $cartService;

    /**
     * CartController constructor.
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @Route("", name="index")
     * @return Response
     */
    public function index() :Response
    {
        $cart = $this->cartService->getFullCart();
        $totalItems = $this->cartService->getTotalAmount();
        return $this->render('cart/index.html.twig', [
            'items' => $cart,
            'totalItems' => $totalItems
        ]);
    }


    /**
     * @Route("/remove/{id}", name="remove")
     * @param $id
     * @return RedirectResponse
     */
    public function remove($id) :Response
    {
        $this->cartService->remove($id);
        return $this->redirectToRoute('cart_index');
    }

}
