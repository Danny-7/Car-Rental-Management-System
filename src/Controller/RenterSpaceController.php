<?php


namespace App\Controller;


use App\Entity\SearchBillData;
use App\Form\BillsFilterType;
use App\Form\NewCarType;
use App\Service\Bill\BillingService;
use App\Service\Car\CarService;
use App\Service\FileUpload\FileUploader;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RenterSpaceController
 * @package App\Controller
 * @Route("/user-space/renter", name="user_space_renter_")
 */
class RenterSpaceController extends AbstractController
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
     * @Route("/cars/{id}", name="cars")
     * @param int $id
     * @return Response
     */
    public function showCars(int $id) :Response
    {
        $cars = $this->carService->getAllCarsByOwnerId($id);
        return $this->render("user_space/cars.html.twig", [
            'cars' => $cars
        ]);
    }

    private function arrangeBills(array $bills) :array
    {
        $filteredBills = array();
        foreach ($bills as $bill){
            $car = $this->carService->getCar($bill->getIdCar()->getId());
            $owner = $this->userService->getUser($car->getIdOwner()->getId());
            $renter =  $this->userService->getUser($bill->getIdUser()->getId());
            if($owner->getId() === $this->getUser()->getId()){
                array_push($filteredBills, [$bill, $car, $renter]);
            }
        }
        return $filteredBills;
    }

    /**
     * Show the rented cars of a renter
     * @Route("/rented/cars", name="cars_rented")
     * @param Request $request
     * @return Response
     */
    public function showRentedCars(Request $request)
    {
        $searchData = new SearchBillData();
        $searchForm = $this->createForm(BillsFilterType::class, $searchData);

        $filteredBills = array();
        $searchForm->handleRequest($request);
        if($searchForm->isSubmitted() && $searchForm->isValid()){
            $bills = $this->billingService->showBillsOfCustomer($searchData->getUser());
            $filteredBills = $this->arrangeBills($bills);
        }
        else {
            $bills = $this->billingService->showBills();
            $filteredBills = $this->arrangeBills($bills);
        }

        return $this->render('user_space/rentedCars.html.twig', [
            'bills' => $filteredBills,
            'form' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("car/edit/{id}", name="car_edit")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param int $id
     * @return Response
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

            return $this->redirectToRoute('user_space_renter_cars');

        }

        return $this->render("car/car.new.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /** The renter can remove a car from his list
     * @Route("/car/delete/{id}", name="car_delete")
     * @param int $id
     */
    public function removeCar(int $id) :Response {
        $this->carService->remove($id);
        $this->addFlash('message', "Votre véhicule a bien été supprimé");
        return $this->redirectToRoute("user_space_renter_cars");
    }

}