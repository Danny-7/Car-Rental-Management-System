<?php

namespace App\Controller;

use App\Service\Bill\BillingService;
use App\Service\Cart\CartService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BillController extends AbstractController
{
    /**
     * @Route("/bill", name="bill")
     */
    public function index()
    {
        return $this->render('bill/index.html.twig');
    }

    /**
     * @Route("/bill/new", name="bills.new")
     * @param CartService $cartService
     * @param BillingService $billingService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createBill(CartService $cartService, BillingService $billingService)
    {
        $order = $cartService->getFullCart();
        foreach ($order as $item){
            $rentOptions = $item['rentOptions'];
            $quantity = $item['quantity'];
            $car = $item['item'];
            $billingService->createBill($this->getUser(), $car, $rentOptions, $quantity);
        }
        $billingService->flush();
        $cartService->clear();

        return $this->redirectToRoute('cars');
    }
}
