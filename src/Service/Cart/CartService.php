<?php

namespace App\Service\Cart;

use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    private SessionInterface $session;
    private CarRepository $carRepository;
    private array $cartData;

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

