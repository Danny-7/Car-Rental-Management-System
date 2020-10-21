<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=0; $i<10; $i++){
            $car = new Car();

            $car->setType("Peugeot 20${i}")
                ->setAmount(2000)
                ->setDataSheet(["Moteur thermique", "Boite de vitesse automatique", "5 places"])
                ->setRent('disponible')
                ->setImage('https://place-hold.it/500x200')
                ->setIdOwner(null);

            $manager->persist($car);

        }

        $manager->flush();
    }
}
