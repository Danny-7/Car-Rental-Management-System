<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Entity\Comment;
use App\Service\Car\CarService;
use App\Service\User\UserService;
use App\Service\Bill\BillingService;
use App\Service\Comment\CommentService;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller
 * @Route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{

    private CarService $carService;
    private CommentService $commentService;
    private BillingService $billingService;
    private UserService $userService;

    public function __construct(CarService $carService,
                                BillingService $billingService,
                                UserService $userService,
                                CommentService $commentService)
    {
        $this->carService = $carService;
        $this->billingService = $billingService;
        $this->userService = $userService;
        $this->commentService = $commentService;
    }

    /**
     * @Route("/add-{id}", name="add")
     * @param Billing $bill
     * @param Request $request
     * @return Response
     */
    public function addComment (Billing $bill, Request $request) :Response
    {

        $comment = new Comment();
        $car = $bill->getIdCar();

        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        $user = $bill->getIdUser();

        if($commentForm->isSubmitted()){

            $content = $commentForm->get('content')->getData();

            if($content == null) {
                return $this->redirectToRoute("user_space_client_rentals",[
                    'id' => $user->getId()
                ]);
            }

            $this->commentService->addComment($user, $car, $content);

            return $this->redirectToRoute("user_space_client_rentals",[
                'id' => $user->getId()
            ]);
        }
        return $this->render('comment/comment.add.html.twig', [
            'user' => $user,
            'commentForm' => $commentForm->createView()
        ]);
    }
}