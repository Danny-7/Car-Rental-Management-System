<?php


namespace App\Service\Comment;

use App\Entity\Car;
use App\Entity\Billing;
use App\Entity\Comment;
use App\Repository\BillingRepository;
use App\Service\Car\CarService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentService
{
    private $entityManager;
    private $repository;

    /**
     * BillingService constructor.
     * @param EntityManagerInterface $entityManager
     * @param BillingRepository $repository
     * @param CarService $carService
     */
    public function __construct(EntityManagerInterface $entityManager, BillingRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function addComment(UserInterface $user, Car $car, String $content) {

        $comment = new Comment();

        $comment->setContent($content)
                ->setAuthor($user->getName())
                ->setCreatedAt(new \DateTime('now'))
                ->setCar($car);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

  
}