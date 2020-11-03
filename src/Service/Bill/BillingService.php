<?php


namespace App\Service\Bill;

use App\Entity\Car;
use App\Entity\Billing;
use App\Repository\BillingRepository;
use App\Service\Car\CarService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BillingService
{
    private $entityManager;
    private $repository;
    private $carService;
    private const NB_VIP_CAR = 10;
    private const REDUCE_PCT = 0.1;

    /**
     * BillingService constructor.
     * @param EntityManagerInterface $entityManager
     * @param BillingRepository $repository
     * @param CarService $carService
     */
    public function __construct(EntityManagerInterface $entityManager, BillingRepository $repository, CarService $carService)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->carService = $carService;
    }

    public function createBill( UserInterface $user, Car $car, array $rentOptions)
    {
        $hasReduce = false;
        $bills = $this->showBillsOfUser($user->getId());
        $nbRent = count($bills);

        if($nbRent > self::NB_VIP_CAR){
            $hasReduce = true;
        }
        $bill = new Billing();
        $bill->setIdCar($car)
            ->setIdUser($user)
            ->setPrice($hasReduce ? ($car->getAmount())*(1-self::REDUCE_PCT) :
                $car->getAmount())
            ->setStartDate($rentOptions['startDate'])
            ->setEndDate($rentOptions['endDate'])
            ->setPaid(true);
        $this->entityManager->persist($bill);
    }

    public function flushBill(){
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