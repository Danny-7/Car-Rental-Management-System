<?php

namespace App\Service\Car;

use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarService
{  
    private $carEm;
    private $carRepository;
    
    public function __construct( EntityManagerInterface $carEm, CarRepository $carRepository)
    {
       $this->carEm = $carEm;
       $this->carRepository = $carRepository;
    } 

    public function getAllCars() :array
    {
        $cars = $this->carRepository->findAll();
        return $cars;
    }

    public function getAllCarsByOwnerId(int $ownerId) :array
    {
        $cars = $this->carRepository->findBy(['idOwner' => $ownerId]);
        return $cars;
    }

    public function getCar(int $id) :Car
    {
        return $this->carRepository->find($id);
    }

    public function add(Car $car)
    {
        $this->carEm->persist($car);
        $this->carEm->flush($car);
    }

    public function remove(int $id)
    {
        $car = new Car();
        $car = $this->carRepository->find($id);

        $this->carEm->remove($car);
        $this->carEm->flush();
    }
    
}