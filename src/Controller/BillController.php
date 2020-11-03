<?php

namespace App\Controller;

use App\Service\Bill\BillingService;
use App\Service\Car\CarService;
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
     * @param CarService $carService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createBill(CartService $cartService, BillingService $billingService, CarService $carService)
    {
        $order = $cartService->getFullCart();
        foreach ($order as $item){
            $rentOptions = $item['rentOptions'];
            $quantity = $item['quantity'];
            $car = $item['item'];
            for($i = 0; $i < $quantity; $i++){
                $billingService->createBill($this->getUser(), $car, $rentOptions);
            }
            $carService->UpdateCarQuantity($car->getId(), $quantity);
        }
        $billingService->flushBill();
        $cartService->clear();

        return $this->redirectToRoute('cars');
    }
}
