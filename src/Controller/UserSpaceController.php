<?php

namespace App\Controller;

use App\Service\Car\CarService;
use App\Service\User\UserService;
use App\Service\Bill\BillingService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UserSpaceController
 * @package App\Controller
 * @Route("/user-space", name="user_space_")
 */
class UserSpaceController extends AbstractController
{
    private UserService $userService;
    private CarService $carService;
    private BillingService $billingService;

    public function __construct(UserService $userService, CarService $carService, BillingService $billingService)
    {
        $this->userService = $userService;
        $this->carService = $carService;
        $this->billingService = $billingService;
    }

    /**
     * Dashboard of the user
     * @Route("", name="index")
     * @return Response
     */
    public function index() :Response
    {
        $infos = $this->billingService->getDashboardInfo($this->getUser()->getId());
        return $this->render("user_space/dashboard.html.twig", [
            'infos' => $infos
        ]);
    }


    /**
     * Show actually cars rented ( not returned )
     * @Route("/client/rentals-{id}", name="client_rentals")
     * @param int $id
     * @return Response
     */
    public function showRentals(int $id) :Response
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
     * @Route("/client/bills-{id}", name="client_bills")
     * @param int $id
     * @return Response
     */
    public function showBills(int $id) :Response
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

    private function arrangeBills(array $bills) :array
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
     * @Route("/car/return/{id}", name="car_return")
     * @param int $id
     * @return Response
     */
    public function returnCar(int $id) :Response
    {

        $bill = $this->billingService->getBill($id);

        $this->carService->return($bill->getIdCar()->getId());

        $this->billingService->returnCarBill($id);

        $this->addFlash('message', "Le véhicule à bien été rendu");
        return $this->redirectToRoute('comment_add', [
            'id' => $bill->getId()
        ]);
    }

}

