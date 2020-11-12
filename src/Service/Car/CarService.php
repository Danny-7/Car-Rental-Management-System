<?php

namespace App\Service\Car;

use App\Entity\Car;
use App\Entity\User;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarService
{
    private EntityManagerInterface $carEm;
    private CarRepository $carRepository;

    public function __construct( EntityManagerInterface $carEm, CarRepository $carRepository)
    {
        $this->carEm = $carEm;
        $this->carRepository = $carRepository;
    }

    public function getAllCars() :array
    {
        return $this->carRepository->findAll();
    }

    public function getAllCarsByOwnerId(User $owner) :array
    {
        return $this->carRepository->findBy(['idOwner' => $owner->getId()]);
    }

    public function add(Car $car)
    {
        $this->carEm->persist($car);
        $this->carEm->flush();
    }

    public function remove(Car $car)
    {
        $this->carEm->remove($car);
        $this->carEm->flush();
    }

    public function return(Car $carToUpdate)
    {
        $carToUpdate->setQuantity($carToUpdate->getQuantity() + 1);
    }

    /**
     * @param Car $car
     * @param int $quantity
     */
    public function UpdateCarQuantity(Car $car, int $quantity)
    {
        $car->setQuantity($car->getQuantity() - $quantity);
    }


}
