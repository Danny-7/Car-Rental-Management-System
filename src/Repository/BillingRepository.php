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

    public function getCountOfRentedCarsByUser(int $idUser) :int
    {
        return $this->createQueryBuilder('b')
            ->select('count(b)')
            ->andWhere('b.idUser = :id')
            ->setParameter('id', $idUser)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getCountCarsByUserWithOption(int $idUser, bool $isReturned) :int
    {
        return $this->createQueryBuilder('b')
            ->select('count(b)')
            ->andWhere('b.idUser = :id')
            ->andWhere('b.returned = :val')
            ->setParameter('id', $idUser)
            ->setParameter('val', $isReturned)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
