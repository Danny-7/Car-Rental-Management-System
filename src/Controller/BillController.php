<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createBill(CartService $cartService)
    {
        $order = $cartService->getFullCart();
        $input = $this->createFormBuilder()
            ->add('endDate', DateType::class)
            ->getForm();

        return $this->render('bill/bills.new.html.twig', [
            'order' => $order,
            'input' => $input
        ]);
    }
}
