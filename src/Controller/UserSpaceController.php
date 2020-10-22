<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\NewCarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserSpaceController extends AbstractController
{

    /**
     * @Route("/user/space", name="user.space")
     */
    public function index()
    {
        return $this->render("user_space/index.html.twig");
    }

    /**
     * @Route("/user/space/cars", name="user.space.cars")
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
