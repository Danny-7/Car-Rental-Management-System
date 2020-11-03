<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Entity\Car;
use App\Form\NewCarType;
use App\Form\RentCarType;
use App\Service\Car\CarService;
use App\Service\Cart\CartService;
use App\Service\FileUpload\FileUploader;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Sodium\add;

class CarController extends AbstractController
{
    private $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }


    /**
     * @Route("/cars", name="cars")
     */
    public function index()
    {
        $cars = $this->carService->getAllCars();
        return $this->render('car/index.html.twig', [
            'cars' => $cars
        ]);
    }

    /**
     * @Route("/car/new", name="car.new")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createCar(Request $request, FileUploader $fileUploader)
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
            return $this->render('user_space/index.html.twig', [
                'v_type' => $car->getType()
            ]);
        }

        return $this->render('car/car.new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/car/{id}", name="car.show")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCar($id)
    {
        $car = $this->carService->getCar($id);

        return $this->render('car/car.show.html.twig', [
            'car' => $car,
        ]);
    }

    /**
     * @Route("/car/rent/{id}", name="car.rent")
     * @param $id
     * @param Request $request
     * @param CartService $cartService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rentCar($id, Request $request, CartService $cartService)
    {
        $car = $this->carService->getCar($id);
        $bill = new Billing();
        $form = $this->createForm(RentCarType::class, $bill);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data= $form->getData();
            $rentOptions = [
                'startDate' => $data->getStartDate(),
                'endDate' => $data->getEndDate(),
                'quantity' => (int)$form->get('quantity')->getData()
            ];
            $cartService->add($id, $rentOptions, $rentOptions['quantity']);
            return $this->redirectToRoute('cars');
        }

        return $this->render('car/car.rent.html.twig', [
            'car' => $car,
            'form' => $form->createView()
        ]);
    }


}
