<?php


namespace App\Service\Bill;

use App\Entity\Car;
use App\Entity\User;
use App\Entity\Billing;
use App\Service\Car\CarService;
use App\Service\Cart\CartService;
use App\Repository\BillingRepository;
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

    public function getCountOfRentedCars($idUser) :int
    {
        return $this->repository->getCountOfRentedCarsByUser($idUser);
    }

    public function getCountOfReturnedCars($idUser) :int
    {
        return $this->repository->getCountCarsByUserWithOption($idUser, true);
    }

    public function getCountOfAvailableCars($idUser) :int
    {
        return $this->repository->getCountCarsByUserWithOption($idUser, false);
    }

    public function getTotalAmountPaid(int $idUser) :int
    {
        return $this->repository->getAmountOfRentalsPaid($idUser);
    }

    public function getDashboardInfo(int $idUser) : array
    {
        $bills = $this->repository->findBy(['idUser' => $idUser]);
        $nbRentedCars = $nbReturnedCars = $nbAvailableCars = $totalAmountPaid = $nbUnpaid = $amountCurrMonthRentals = 0;

        foreach ($bills as $bill){
            ++$nbRentedCars;
            if($bill->getPaid()){
                $totalAmountPaid += $bill->getPrice();
            }
            else {
                ++$nbUnpaid;
            }
            if($bill->getReturned()){
                ++$nbReturnedCars;
            }
            else{
                ++$nbAvailableCars;
            }
            if(date('m',strtotime($bill->getStartDate()->format('Y/m/d'))) == date('m')){
                $amountCurrMonthRentals += $bill->getPrice();
            }
        }
        return array($nbRentedCars, $nbReturnedCars, $nbAvailableCars, $totalAmountPaid, $nbUnpaid, $amountCurrMonthRentals);
    }

    public function createBill( UserInterface $user, Car $car, array $rentOptions)
    {
        $hasReduce = false;
        $hasEndDate = false;
        $bills = $this->showBillsOfUser($user->getId());
        $nbRent = count($bills);

        if($nbRent > self::NB_VIP_CAR){
            $hasReduce = true;
        }

        $nbDays = (int)date('t');

        if($rentOptions['endDate']){
            $hasEndDate = true;
            $nbDays = $rentOptions['startDate']->diff($rentOptions['endDate'])->days;
        }
        

        $bill = new Billing();
        $bill->setIdCar($car)
            ->setIdUser($user)
            ->setPrice($hasReduce ? ($car->getAmount()*$nbDays*(1-self::REDUCE_PCT)) :
                $car->getAmount()*$nbDays)
            ->setStartDate($rentOptions['startDate']);
            if($hasEndDate){
                $bill->setEndDate($rentOptions['endDate']);
            }
            $bill->setPaid($rentOptions['paid'])
                ->setReturned(false);

        $this->entityManager->persist($bill);
    }

    public function removeBill (int $id) {

        $bill = $this->repository->find($id);

        $this->entityManager->remove($bill);
        $this->entityManager->flush();
    }

    public function returnCarBill (int $id) {

        $bill = $this->repository->find($id);

        $bill->setReturned(true);
        $this->entityManager->flush();

    }

    public function getBill(int $id) :Billing
    {
        return $this->repository->findOneBy(['id' => $id]);
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

    public function showBillsOfUserReturned(int $id) :array
    {
        return $this->repository->findBy([
            'idUser' => $id,
            'returned' => true            
            ]);
    }

    public function showBillsOfUserNotReturned(int $id) :array
    {
        return $this->repository->findBy([
            'idUser' => $id,
            'returned' => false           
            ]);
    }

    public function showBillsOfCustomer(User $customer) :array
    {
        return $this->showBillsOfUser($customer->getId());
    }


    public function payBill(Billing $bill)
    {
        $bill->setPaid(true);
        $this->entityManager->flush();
    }
}