<?php

namespace App\Service\Cart;

use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    private $session;
    private $carRepository;
    private $cartData;

    public function __construct(SessionInterface $session, CarRepository $carRepository)
    {
        $this->session = $session;
        $this->carRepository = $carRepository;
        $this->cartData = [];
    }

    public function add(int $id)
    {
        $cart = $this->session->get('cart', []);

        $cart[$id] = $id ;

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
                'item' => $this->carRepository->find($id)
            ];
        }

        return $this->cartData;
    }

    public function getTotalAmount() : float
    {
        $totalItems = 0;
        foreach ($this->cartData as $car){
            $totalItems+= $car['item']->getAmount();
        }

        return $totalItems;
    }
}

