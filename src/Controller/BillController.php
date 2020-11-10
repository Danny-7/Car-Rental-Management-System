<?php

namespace App\Controller;

use App\Service\Car\CarService;
use App\Service\Cart\CartService;
use App\Service\Bill\BillingService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BillController
 * @package App\Controller
 * @Route("/bill", name="bill_")
 */
class BillController extends AbstractController
{
    private CartService $cartService;
    private BillingService $billingService;
    private CarService $carService;

    /**
     * BillController constructor.
     * @param CartService $cartService
     * @param BillingService $billingService
     * @param CarService $carService
     */
    public function __construct(CartService $cartService, BillingService $billingService, CarService $carService)
    {
        $this->cartService = $cartService;
        $this->billingService = $billingService;
        $this->carService = $carService;
    }

    /**
     * @Route("/new", name="new")
     * @return Response
     */
    public function createBill() :Response
    {
        $order = $this->cartService->getFullCart();
        foreach ($order as $item){
            $rentOptions = $item['rentOptions'];
            $quantity = $item['quantity'];
            $car = $item['item'];
            for($i = 0; $i < $quantity; $i++){
                $this->billingService->createBill($this->getUser(), $car, $rentOptions);
            }
            $this->carService->UpdateCarQuantity($car->getId(), $quantity);
        }
        $this->billingService->flushBill();
        $this->cartService->clear();

        return $this->redirectToRoute('cars');
    }


    /**
     * @Route("/pay", name="pay")
     * @param $id
     * @return Response
     */
    public function billPay(int $id) :Response
    {

        $bill = $this->billingService->getBill($id);
        $car = $this->carService->getCar($bill->getIdCar()->getId());

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
     * @Route("/update/{id}", name="update")
     * @param int $id
     * @return Response
     */
    public function updateBill (int $id) :Response
    {
        $bill = $this->billingService->getBill($id);

        $this->billingService->payBill($bill);
        
        return $this->redirectToRoute('user_space_client_rentals', [
            'id' => $bill->getIdUser()->getId()
        ]);

    }
}
