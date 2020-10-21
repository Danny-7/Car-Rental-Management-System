<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\NewCarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarController extends AbstractController
{


    /**
     * @Route("/cars", name="cars")
     */
    public function index(CarRepository $carRepo)
    {
        $cars = $carRepo->findAll();
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
    public function createCar(Request $request, EntityManagerInterface $em,
                              CarRepository $carRepo)
    {
        $car = new Car();

        $form = $this->createForm(NewCarType::class);

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
    public function showCar($id, CarRepository $carRepo)
    {
        $car = $carRepo->find($id);

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
