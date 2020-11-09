<?php

namespace App\Controller;

use App\Service\Car\CarService;
use App\Service\Cart\CartService;
use App\Service\Bill\BillingService;
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


    /**
     * @Route("/cart/pay/{id}", name="cart.pay")
     * @param $id
     * @param BillingService $billingService
     * @param CarService $carService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function billPay(int $id,  BillingService $billingService, CarService $carService)
    {

        $bill = $billingService->getBill($id);
        $car = $carService->getCar($bill->getIdCar()->getId());

        $nbDays = $bill->getStartDate()->diff(new \DateTime('now'))->days;
        if($nbDays == 0){
            ++$nbDays;
        }

        return $this->render('cart/cart.pay.html.twig', [
            'bill' => $bill,
            'car' => $car,
            'nbDays' => $nbDays
        ]);
    }


    /**
     * @Route("/cart/bill/update/{id}", name="bills.update")
     * @param $id
     * @param BillingService $billingService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateBill (int $id, BillingService $billingService)
    {
        $bill = $billingService->getBill($id);

        $billingService->payBill($bill);
        
        return $this->redirectToRoute('user.space.client.rentals', [
            'id' => $bill->getIdUser()->getId()
        ]);

    }
}
