<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Comment;
use App\Service\Car\CarService;
use App\Service\User\UserService;
use App\Service\Bill\BillingService;
use App\Service\Comment\CommentService;
use Symfony\Component\Form\Form;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{

    private $carService;
    private $commentService;
    private $billingService;
    private $userService;

    public function __construct(CarService $carService, BillingService $billingService, UserService $userService, CommentService $commentService)
    {
        $this->carService = $carService;
        $this->billingService = $billingService;
        $this->userService = $userService;
        $this->commentService = $commentService;
    }

    /**
     * @Route("/comment", name="comment")
     */
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }


    /**
     * @Route("/comment/add-{id}", name="comment.add")
     * @param $id
     * @param Request $request
     * @param CommentService $commentService
     * @param BillingService $billingService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addComment (int $id, Request $request,CommentService $commentService) {        

        $comment = new Comment();
        $bill = $this->billingService->getBill($id);
        $car = $this->carService->getCar($bill->getIdCar()->getId());

        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        $user = $this->userService->getUser($bill->getIdUser()->getId());
      

        if($commentForm->isSubmitted()){

            $content = $commentForm->get('content')->getData();

            if($content == null) {
                return $this->redirectToRoute("user.space.client.rentals",[
                    'id' => $user->getId()
                ]);
            }

            $commentService->addComment($user, $car, $content);

            return $this->redirectToRoute("user.space.client.rentals",[
                'id' => $user->getId()
            ]);
        }

        return $this->render('comment/comment.add.html.twig', [
            'user' => $user,
            'commentForm' => $commentForm->createView()
        ]);
    }
}
