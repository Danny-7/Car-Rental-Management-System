<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Service\Car\CarService;
use App\Service\Cart\CartService;
use App\Service\Bill\BillingService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

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
    private Security $security;

    /**
     * BillController constructor.
     * @param CartService $cartService
     * @param BillingService $billingService
     * @param CarService $carService
     * @param Security $security
     */
    public function __construct(CartService $cartService, BillingService $billingService, CarService $carService, Security $security)
    {
        $this->cartService = $cartService;
        $this->billingService = $billingService;
        $this->carService = $carService;
        $this->security = $security;
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
            $this->carService->UpdateCarQuantity($car, $quantity);
        }
        $this->billingService->flushBill();
        $this->cartService->clear();

        return $this->redirectToRoute('cars');
    }


    /**
     * @Route("/pay-{id}", name="pay")
     * @param Billing $bill
     * @return Response
     */
    public function billPay(Billing $bill) :Response
    {

        $car = $bill->getIdCar();

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
     * @param Billing $bill
     * @return Response
     */
    public function updateBill (Billing $bill) :Response
    {
        $this->billingService->payBill($bill);

        return $this->redirectToRoute('user_space_client_rentals', [
            'id' => $bill->getIdUser()->getId()
        ]);

    }
}