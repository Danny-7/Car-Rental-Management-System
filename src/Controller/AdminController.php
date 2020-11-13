<?php


namespace App\Controller;


use App\Entity\Car;
use App\Form\NewCarType;
use App\Service\Car\CarService;
use App\Service\User\UserService;
use App\Service\Bill\BillingService;
use App\Service\FileUpload\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    private CarService $carService;
    private BillingService $billingService;
    private UserService $userService;

    /**
     * AdminController constructor.
     * @param CarService $carService
     * @param BillingService $billingService
     * @param UserService $userService
     */
    public function __construct(CarService $carService, BillingService $billingService, UserService $userService)
    {
        $this->carService = $carService;
        $this->billingService = $billingService;
        $this->userService = $userService;
    }

    /**
     * @Route("", name="index")
     */
    public function index() :Response
    {
        $infos = $this->billingService->getDashboardInfo(null, 'ADMIN');
        return $this->render("admin/index.html.twig", [
            'infos' => $infos
        ]);
    }

    /**
     * @Route("/cars", name="cars")
     * @return Response
     */
    public function showCars() :Response
    {
        $cars = $this->carService->getAllCars();
        return $this->render("admin/cars.html.twig", [
            'cars' => $cars
        ]);
    }

    /**
     * @Route("/car/edit/{id}", name="car_edit")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param Car $car
     * @return Response
     */
    public function editCar(Request $request, FileUploader $fileUploader, Car $car) :Response
    {
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

            return $this->redirectToRoute('admin_cars');

        }

        return $this->render("admin/car.new.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /** The renter can remove a car from his list
     * @Route("/car/delete/{id}", name="car_delete")
     * @param Car $car
     * @return Response
     */
    public function removeCar(Car $car) :Response
    {   if($this->billingService->hasBillsRelated($car)){
            return $this->redirectToRoute('admin_cars');
        }
        $this->carService->remove($car);
        $this->addFlash('message', "Votre véhicule a bien été supprimé");
        return $this->redirectToRoute("admin_cars");
    }

    /**
     * @Route("/car/new", name="car_new")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function createCar(Request $request, FileUploader $fileUploader) :Response
    {
        $car = new Car();

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
            return $this->redirectToRoute('admin_cars');
        }

        return $this->render('admin/car.new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    private function arrangeBills(array $bills) :array
    {
        $filteredBills = array();
        foreach ($bills as $bill){
            $car = $bill->getIdCar();
            $renter =  $bill->getIdUser();
            array_push($filteredBills, [$bill, $car, $renter]);
        }
        return $filteredBills;
    }

    /**
     * @Route("/rentals", name="rentals")
     * @return Response
     */
    public function showRentals() :Response
    {
        $rentals = $this->arrangeBills($this->billingService->showBills());
        return $this->render("admin/rentals.html.twig", [
            'rentals' => $rentals
        ]);
    }

    /**
     * @Route("/bills", name="bills")
     * @return Response
     */
    public function showBills() :Response
    {
        $bills = $this->arrangeBills($this->billingService->showBills());
        return $this->render("admin/bills.html.twig", [
            'bills' => $bills
        ]);
    }

    /**
     * @Route("/users", name="users")
     * @return Response
     */
    public function showUsers() :Response
    {
        $users = $this->userService->getUsers();
        return $this->render("admin/users.html.twig", [
            'users' => $users
        ]);
    }

}