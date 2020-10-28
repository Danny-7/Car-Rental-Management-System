<?php


namespace App\Service\Bill;

use App\Entity\Car;
use App\Entity\Billing;
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

    public function createBill( UserInterface $user, Car $car, array $rentOptions, int $quantity)
    {
        $bill = new Billing();
        $bill->setIdCar($car)
            ->setIdUser($user)
            ->setPrice($car->getAmount()*$quantity)
            ->setStartDate($rentOptions['startDate'])
            ->setEndDate($rentOptions['endDate'])
            ->setPaid(true);
        $this->entityManager->persist($bill);
    }

    public function flush(){
        $this->entityManager->flush();
    }

    public function showBills() :array
    {
        return $this->repository->findAll();
    }

    public function showBillsOfUser(int $id) :array
    {
        return $this->repository->findBy(['idUser' => $id]);
    }


}