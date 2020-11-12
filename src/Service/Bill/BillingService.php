<?php


namespace App\Service\Bill;

use App\Entity\Car;
use App\Entity\User;
use App\Entity\Billing;
use App\Service\Car\CarService;
use App\Repository\BillingRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BillingService
{
    private EntityManagerInterface $entityManager;
    private BillingRepository $repository;
    private CarService $carService;
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

    public function getDashboardInfo(?User $user, string $role) : array
    {
        $bills = array();
        if($user){
            if($role == 'RENTER'){
                $bills = $this->repository->findAllBillsOfRenter($user->getId());
            }
            else {
                $bills = $this->repository->findAllBillsOfCustomer($user->getId());
            }
        }
        else {
            $bills = $this->repository->findAllBills();
        }
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

    public function createBill(UserInterface $user, Car $car, array $rentOptions)
    {
        $hasReduce = false;
        $hasEndDate = false;
        $isPaid = false;

        $user_tmp = $user;
        /**
         * @var User $user_tmp
         */
        $bills = $this->showBillsOfCustomer($user_tmp);
        $nbRent = count($bills);

        if($nbRent > self::NB_VIP_CAR){
            $hasReduce = true;
        }

        $nbDays = (int)date('t');
        if($rentOptions['endDate']){
            /**
             * @var DateTimeInterface $date
             */
            $date = $rentOptions['endDate'];
            $currDate = new DateTime();
            if($currDate->format('n') === $date->format('n') ) {
                $isPaid = true;
            }
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
        $bill->setPaid($isPaid)
            ->setReturned(false);

        $this->entityManager->persist($bill);
    }

    public function removeBill (Billing $bill)
    {
        $this->entityManager->remove($bill);
    }

    /**
     * @param Billing $bill
     */
    public function returnCarBill (Billing $bill)
    {
        $bill->setReturned(true);
        $this->entityManager->flush();

    }

    public function flushBill()
    {
        $this->entityManager->flush();
    }

    public function showBills() :array
    {
        return $this->repository->findAllBills();
    }

    public function showBillsOfCustomer(User $customer) :array
    {
        return $this->repository->findAllBillsOfCustomer($customer->getId());
    }

    public function showBillsOfRenter(User $renter)
    {
        return $this->repository->findAllBillsOfRenter($renter->getId());

    }

    public function showBillsOfCustomerReturned(User $customer) :array
    {
        return $this->repository->findAllBillsOfCustomerWithOption($customer->getId(), true);
    }

    public function showBillsOfCustomerNotReturned(User $customer) :array
    {
        return $this->repository->findAllBillsOfCustomerWithOption($customer->getId(), false);
    }

    public function payBill(Billing $bill)
    {
        $bill->setPaid(true);
        $this->entityManager->flush();
    }

}