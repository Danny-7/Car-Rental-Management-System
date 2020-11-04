<?php

namespace App\Controller;

use App\Form\NewCarType;
use App\Service\Bill\BillingService;
use App\Service\Car\CarService;
use App\Service\FileUpload\FileUploader;
use App\Service\User\UserService;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/user/space", name="user.space")
     */
    public function index()
    {
        return $this->render("user_space/index.html.twig");
    }

    /**
     * @Route("/user/space/cars/{id}", name="user.space.cars")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCars(int $id)
    {
        $cars = $this->carService->getAllCarsByOwnerId($id);
        return $this->render("user_space/cars.html.twig", [
            'cars' => $cars
        ]);
    }

    /**
     * @Route("/user/space/client/rentals-{id}", name="user.space.client.rentals")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showRentals(int $id)
    {
        $bills = $this->billingService->showBillsOfUserNotReturned($id);
        $billsFormatted = array();
        foreach ($bills as $bill){
            $car = $this->carService->getCar($bill->getIdCar()->getId());
            array_push($billsFormatted, [$bill, $car]);
        }

        return $this->render('user_space/client/rentals.html.twig', [
            'bills' => $billsFormatted
        ]);
    }

    /**
     * @Route("/user/space/client/bills-{id}", name="user.space.client.bills")
     * @param int $id
     */
    public function showBills(int $id)
    {
        $bills = $this->billingService->showBillsOfUserReturned($id);
        $billsFormatted = array();
        foreach ($bills as $bill){
            $car = $this->carService->getCar($bill->getIdCar()->getId());
            $renter = $this->userService->getUser($bill->getIdUser()->getId());
            array_push($billsFormatted, [$bill, $car, $renter]);
        }

        return $this->render('user_space/client/bills.html.twig', [
            'bills' => $billsFormatted
        ]);
    }

    /**
     * @Route("/user/space/renter/rented/cars", name="user.space.renter.cars.rented")
     */
    public function showRentedCars()
    {
        $bills = $this->billingService->showBills();
        $filteredBills = array();
        foreach ($bills as $bill){
            $car = $this->carService->getCar($bill->getIdCar()->getId());
            $owner = $this->userService->getUser($car->getIdOwner()->getId());
            $renter =  $this->userService->getUser($bill->getIdUser()->getId());
            if($owner->getId() === $this->getUser()->getId()){
                array_push($filteredBills, [$bill, $car, $renter]);
            }
        }
//        dd($filteredBills);

        return $this->render('user_space/rentedCars.html.twig', [
            'bills' => $filteredBills
        ]);
    }


    /**
     * @Route("/user/space/car/delete/{id}", name="user.space.car.delete")
     */
    public function removeCar(int $id) {
        $this->carService->remove($id);
        $this->addFlash('message', "Votre véhicule a bien été supprimé");
        return $this->redirectToRoute("user.space.cars");
    }

    /**
     * @Route("/user/space/car/return/{id}", name="user.space.car.return")
     */
    public function returnCar(int $id) {

        $bill = $this->billingService->getBill($id);

        $this->carService->return($bill->getIdCar()->getId());

        $this->billingService->returnCarBill($id);

        $this->addFlash('message', "Le véhicule à bien été rendu");
        return $this->redirectToRoute("user.space.client.rentals", [
            'id' => $this->getUser()->getId()
        ]);
    }

    /**
     * @Route("/user/space/car/edit/{id}", name="user.space.car.edit")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCar(Request $request, FileUploader $fileUploader, int $id) {
        $car = $this->carService->getCar($id);

        $form = $this->createForm(NewCarType::class, $car);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $carFile = $form->get('attachment')->getData();

            $datasheet = array();

            $datasheet['category'] = $form->get('category')->getData();
            $datasheet['fuel'] = $form->get('fuel')->getData();
            $datasheet['engine'] = $form->get('engine')->getData();
            $datasheet['shift'] = $form->get('shift')->getData();
            $datasheet['nb_portes'] = $form->get('nb_portes')->getData();

            $car->setDataSheet($datasheet);

             if ($carFile) {
                 $carFileName = $fileUploader->upload($carFile);
                 $car->setImage($carFileName);
             }
            $car->setRent("disponible");
            $car->setIdOwner($this->getUser());

            $this->carService->add($car);
            $this->addFlash('message',"Votre véhicule à bien été modifié");
            
            return $this->redirectToRoute('user.space.cars');

        }
        
        return $this->render("car/car.new.html.twig", [
            'form' => $form->createView()
        ]);
    }

}
