<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\NewCarType;
use App\Service\Car\CarService;
use App\Service\FileUpload\FileUploader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class CarController extends AbstractController
{
    private $security;
    private $carService;

    public function __construct(Security $security, CarService $carService)
    {
        $this->security = $security;
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
     * @param EntityManagerInterface $em
     * @param CarRepository $carRepo
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

            // $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
            // $json_string = $serializer->serialize($datasheet, 'json');
            $car->setDataSheet($datasheet);

             if ($carFile) {
                 $carFileName = $fileUploader->upload($carFile);
                 $car->setImage($carFileName);
             }
            $car->setRent("disponible");
            $car->setIdOwner($this->security->getUser());

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
     * @param CarRepository $carRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCar($id)
    {
        $car = $this->carService->getCar($id);

        return $this->render('car/car.show.html.twig', [
            'car' => $car
        ]);
    }

    /**
     * @Route("/user/cars/{id}", name="cars.renter")
     * @param $id
     * @param CarRepository $carRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ShowCarsOfRenter($id, CarRepository $carRepo)
    {
        $cars = $carRepo->find($id);

        return $this->render('user_space/index.html.twig', [
            'cars' => $cars
        ]);
    }


}
