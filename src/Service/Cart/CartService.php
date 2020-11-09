<?php

namespace App\Service\Cart;

use App\Service\Bill\BillingService;
use App\Service\Car\CarService;
use App\Repository\CarRepository;
use App\Repository\BillingRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Billing;

class CartService {

    private $session;
    private $carRepository;
    private $billRepository;
    private $billingService;
    private $cartData;

    public function __construct(SessionInterface $session, CarRepository $carRepository)
    {
        $this->session = $session;
        $this->carRepository = $carRepository;
        $this->cartData = [];
    }

    public function add(int $id, array $rentOptions, int $quantity)
    {
        $cart = $this->session->get('cart', []);
        $startDate = $rentOptions['startDate'];
        $endDate = $rentOptions['endDate'];
        $paid = $rentOptions['paid'];
        $nbDays = (int)date('t');
        if($endDate) {
            $nbDays = $startDate->diff($endDate)->days;
        }
        $cart[$id] = [$id, $rentOptions, $quantity, $nbDays, $paid];

        $this->session->set('cart', $cart);
    }

    public function remove(int $id)
    {
        $cart = $this->session->get('cart', []);
        if(!empty($cart)){
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function getFullCart() :array
    {
        $cart = $this->session->get('cart', []);
        foreach ($cart as $id){
            $this->cartData[] = [
                'item' => $this->carRepository->find($id[0]),
                'rentOptions' => $id[1],
                'quantity' => $id[2],
                'nbDays' => $id[3]
            ];
        }

        return $this->cartData;
    }

    public function getItemCart() : Billing
    {
        $cart = $this->session->get('cart', [0]);
        $bill = $this->billRepository->find($cart[0]);

        return $this->bill;
    }

    public function getTotalAmount() : float
    {
        $totalItems = 0;
        foreach ($this->cartData as $cart){
            $totalItems+= ($cart['item']->getAmount()*$cart['nbDays'])*$cart['quantity'];
        }
        return $totalItems;
    }

    public function clear()
    {
        $this->session->set('cart', []);
        $this->cartData = [];
    }

}

