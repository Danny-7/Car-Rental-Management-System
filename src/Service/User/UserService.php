<?php


namespace App\Service\User;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * UserService constructor.
     * @param $userRepository
     * @param $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function getUser(int $id): User
    {
        return $this->userRepository->find($id);
    }
    public function getUsers() :array
    {
        return $this->userRepository->findAll();
    }
}