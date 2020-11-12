<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Entity\User;
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
        /**
         * @var User $customer
         */
        $customer = $this->getUser();
        $infos = $this->billingService->getDashboardInfo($customer, 'CUSTOMER');
        return $this->render("user_space/dashboard.html.twig", [
            'infos' => $infos
        ]);
    }


    /**
     * Show actually cars rented ( not returned )
     * @Route("/client/rentals-{id}", name="client_rentals")
     * @param User $customer
     * @return Response
     */
    public function showRentals(User $customer) :Response
    {
        $bills = $this->billingService->showBillsOfCustomerNotReturned($customer);
        $billsFormatted = array();
        foreach ($bills as $bill) {
            $car = $bill->getIdCar();
            array_push($billsFormatted, [$bill, $car]);
        }
        return $this->render('user_space/client/rentals.html.twig', [
            'bills' => $billsFormatted,
            'id' => $customer->getId()
        ]);
    }

    /**
     * Show all bills of the client
     * @Route("/client/bills-{id}", name="client_bills")
     * @param User $customer
     * @return Response
     */
    public function showBills(User $customer) :Response
    {
        $bills = $this->billingService->showBillsOfCustomer($customer);
        $billsFormatted = array();
        foreach ($bills as $bill) {
            $car = $bill->getIdCar();
            $renter =  $bill->getIdUser();
            array_push($billsFormatted, [$bill, $car, $renter]);
        }
        return $this->render('user_space/client/bills.html.twig', [
            'bills' => $billsFormatted
        ]);
    }

    /**
     * @Route("/car/return/{id}", name="car_return")
     * @param Billing $bill
     * @return Response
     */
    public function returnCar(Billing $bill) :Response
    {
        $this->carService->return($bill->getIdCar());
        $this->billingService->returnCarBill($bill);

        $this->addFlash('message', "Le véhicule à bien été rendu");
        return $this->redirectToRoute('comment_add', [
            'id' => $bill->getId()
        ]);
    }

}
