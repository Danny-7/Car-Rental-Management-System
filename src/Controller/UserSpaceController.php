<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserSpaceController extends AbstractController
{

    /**
     * @Route("/user/space", name="user.space.cars")
     * @param CarRepository $carRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCars(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();

        return $this->render("user_space/cars.html.twig", [
            'cars' => $cars
        ]);
    }
}
