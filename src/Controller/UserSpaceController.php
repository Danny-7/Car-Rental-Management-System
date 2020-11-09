<?php

namespace App\Controller;

use App\Service\Bill\BillingService;
use App\Service\Car\CarService;
use App\Service\User\UserService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserSpaceController extends AbstractController
{
    private $userService;
    private $carService;
    private $billingService;

    public function __construct(UserService $userService, CarService $carService, BillingService $billingService)
    {
        $this->userService = $userService;
        $this->carService = $carService;
        $this->billingService = $billingService;
    }

    /**
     * Dashboard of the user
     * @Route("/user/space", name="user.space")
     */
    public function index()
    {
        return $this->render("user_space/dashboard.html.twig");
    }


    /**
     * Show actually cars rented ( not returned )
     * @Route("/user/space/client/rentals-{id}", name="user.space.client.rentals")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showRentals(int $id)
    {
        $bills = $this->billingService->showBillsOfUserNotReturned($id);
        $billsFormatted = array();
        foreach ($bills as $bill) {
            $car = $this->carService->getCar($bill->getIdCar()->getId());
            array_push($billsFormatted, [$bill, $car]);
        }

        return $this->render('user_space/client/rentals.html.twig', [
            'bills' => $billsFormatted,
            'id' => $this->getUser()->getId()
        ]);
    }

    /**
     * Show all bills of the client
     * @Route("/user/space/client/bills-{id}", name="user.space.client.bills")
     * @param int $id
     */
    public function showBills(int $id)
    {
        $bills = $this->billingService->showBillsOfUser($id);
        $billsFormatted = array();
        foreach ($bills as $bill) {
            $car = $this->carService->getCar($bill->getIdCar()->getId());
            $renter = $this->userService->getUser($bill->getIdUser()->getId());
            array_push($billsFormatted, [$bill, $car, $renter]);
        }

        return $this->render('user_space/client/bills.html.twig', [
            'bills' => $billsFormatted
        ]);
    }

    private function arrangeBills(array $bills): array
    {
        $filteredBills = array();
        foreach ($bills as $bill) {
            $car = $this->carService->getCar($bill->getIdCar()->getId());
            $owner = $this->userService->getUser($car->getIdOwner()->getId());
            $renter = $this->userService->getUser($bill->getIdUser()->getId());
            if ($owner->getId() === $this->getUser()->getId()) {
                array_push($filteredBills, [$bill, $car, $renter]);
            }
        }
        return $filteredBills;
    }


    /**
     * @Route("/user/space/car/return/{id}", name="user.space.car.return")
     */
    public function returnCar(int $id)
    {

        $bill = $this->billingService->getBill($id);

        $this->carService->return($bill->getIdCar()->getId());

        $this->billingService->returnCarBill($id);

        $this->addFlash('message', "Le véhicule à bien été rendu");
        return $this->redirectToRoute("comment.add", [
            'id' => $bill->getId()
        ]);
    }

    /**
     * @Route("/user/space/car/pay/{id}, name="user.space.car.pay")
     * @param int $id
     */
    /*public function payCar(int $id) {

    }*/
}

