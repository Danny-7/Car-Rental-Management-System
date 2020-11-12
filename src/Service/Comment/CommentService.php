<?php


namespace App\Service\Comment;

use App\Entity\Car;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\BillingRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentService
{
    private EntityManagerInterface $entityManager;
    private BillingRepository $repository;

    /**
     * BillingService constructor.
     * @param EntityManagerInterface $entityManager
     * @param BillingRepository $repository
     */
    public function __construct(EntityManagerInterface $entityManager, BillingRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param UserInterface $user
     * @param Car $car
     * @param String $content
     */
    public function addComment(UserInterface $user, Car $car, String $content)
    {

        $comment = new Comment();

        /**
         * @var User $user
         */
        $comment->setContent($content)
            ->setAuthor($user->getName())
            ->setCreatedAt(new DateTime('now'))
            ->setCar($car);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }


}