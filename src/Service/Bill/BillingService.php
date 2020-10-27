<?php


namespace App\Service\Bill;

use App\Entity\Billing;
use App\Entity\Car;
use App\Repository\BillingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BillingService
{
    private $entityManager;
    private $repository;

    /**
     * BillingService constructor.
     * @param $entityManager
     * @param $repository
     */
    public function __construct(EntityManagerInterface $entityManager, BillingRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function createBill( UserInterface $user, Car $car, array $rentOptions) {
        $bill = new Billing();
        $bill->setIdCar($car)
            ->setIdUser($user)
            ->setPrice($car->getAmount())
            ->setStartDate($rentOptions['startDate'])
            ->setEndDate($rentOptions['endDate'])
            ->setPaid(true);
        $this->entityManager->persist($bill);
    }

    public function flush(){
        $this->entityManager->flush();
    }

}