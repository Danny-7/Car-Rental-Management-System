<?php

namespace App\Repository;

use App\Entity\Billing;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Billing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Billing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Billing[]    findAll()
 * @method Billing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Billing::class);
    }

    public function findAllBills(): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.idUser', 'u')
            ->innerJoin('b.idCar', 'c')
            ->addSelect('u')
            ->addSelect('c')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }

    public function findAllBillsOfCustomer(int $idUser): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.idUser', 'u')
            ->innerJoin('b.idCar', 'c')
            ->andWhere('b.idUser = :id')
            ->setParameter('id', $idUser)
            ->addSelect('u')
            ->addSelect('c')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }


    /**
     * Return all bills of a customer with an option (returned or not)
     * @param int $idUser
     * @param bool $isReturned
     * @return array
     */
    public function findAllBillsOfCustomerWithOption(int $idUser, bool $isReturned): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.idUser', 'u')
            ->innerJoin('b.idCar', 'c')
            ->andWhere('b.idUser = :id')
            ->andWhere('b.returned = :val')
            ->setParameter('id', $idUser)
            ->setParameter('val', $isReturned)
            ->addSelect('u')
            ->addSelect('c')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }

    public function findAllBillsOfRenter(int $idUser)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.idUser', 'u')
            ->innerJoin('b.idCar', 'c')
            ->andWhere('c.idOwner = :id')
            ->setParameter('id', $idUser)
            ->addSelect('u')
            ->addSelect('c')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }

    public function findAllBillsOfCar(int $idCar): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.idCar', 'c')
            ->andWhere('b.idCar = :id')
            ->setParameter('id', $idCar)
            ->select('b')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_OBJECT);
    }
}
