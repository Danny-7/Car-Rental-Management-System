<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\NewCarType;
use App\Service\Car\CarService;
use App\Repository\CarRepository;
use App\Service\FileUpload\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserSpaceController extends AbstractController
{

    private $security;
    private $carService;

    public function __construct(Security $security, CarService $carService)
    {
        $this->security = $security;
        $this->carService = $carService;
    }

    /**
     * @Route("/user/space", name="user.space")
     */
    public function index()
    {
        return $this->render("user_space/index.html.twig");
    }

    /**
     * @Route("/user/space/cars/{id}", name="user.space.cars")
     * @param CarRepository $carRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCars(int $id, CarRepository $carRepository)
    {
        $cars = $this->carService->getAllCarsByOwnerId($id);
        return $this->render("user_space/cars.html.twig", [
            'cars' => $cars
        ]);
    }

    /**
     * @Route("/user/space/car/delete/{id}", name="user.space.car.delete")
     */
    public function removeCar(int $id) {
        $this->carService->remove($id);
        $this->addFlash('message', "Votre véhicule a bien été supprimé");
        return $this->redirectToRoute("user.space.cars");
    }

    /**
     * @Route("/user/space/car/edit/{id}", name="user.space.car.edit")
     */
    public function editCar(Request $request, FileUploader $fileUploader, int $id) {
        $car = $this->carService->getCar($id);

        $form = $this->createForm(NewCarType::class, $car);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $carFile = $form->get('attachment')->getData();

            $datasheet = array();

            $datasheet['category'] = $form->get('category')->getData();
            $datasheet['fuel'] = $form->get('fuel')->getData();
            $datasheet['engine'] = $form->get('engine')->getData();
            $datasheet['shift'] = $form->get('shift')->getData();
            $datasheet['nb_portes'] = $form->get('nb_portes')->getData();

            $car->setDataSheet($datasheet);

             if ($carFile) {
                 $carFileName = $fileUploader->upload($carFile);
                 $car->setImage($carFileName);
             }
            $car->setRent("disponible");
            $car->setIdOwner($this->security->getUser());

            $this->carService->add($car);
            $this->addFlash('message',"Votre véhicule à bien été modifié");
            
            return $this->redirectToRoute('user.space.cars');

        }
        
        return $this->render("car/car.new.html.twig", [
            'form' => $form->createView()
        ]);
    }

}
