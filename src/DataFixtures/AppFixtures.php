<?php

namespace App\DataFixtures;

use App\Entity\Gallery;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i < 19; $i++){
            $image = new Gallery();
            $image->setUrl("uploads/images/img$i.jpg");
            $manager->persist($image);
        }

        $services = [
            '1' => [
                'name' => 'Manucure',
                'description' => 'Limage des ongles, Repoussement des cuticules, Application d’un vernis transparent',
                'price' => 20,
                'duration' => new \DateTime('00:20:00'),
                'shortName' => 'manucure'
            ],
            '2' => [
                'name' => 'Vernis classique',
                'description' => '20 teintes disponibles !',
                'price' => 8.50,
                'duration' => new \DateTime('00:15:00'),
                'shortName' => 'vernisClas'
            ],
            '3' => [
                'name' => 'Gel/Resine',
                'description' => 'Pose complete ongles french/couleur',
                'price' => 50,
                'duration' => new \DateTime('01:00:00'),
                'shortName' => 'gel_resine'
            ],
            '4' => [
                'name' => 'Remplissage french/couleur',
                'description' => 'Environ toutes les 3 semaines',
                'price' => 38,
                'duration' => new \DateTime('01:00:00'),
                'shortName' => 'remplissage'
            ],
            '5' => [
                'name' => 'Dépose ongles  gel ou résine',
                'description' => 'xxxx',
                'price' => 22.50,
                'duration' => new \DateTime('00:30:00'),
                'shortName' => 'depot'
            ],
        ];

        foreach ($services as $service) {
            $serv = new Service();
            $serv->setDescription($service['description']);
            $serv->setDuration($service['duration']);
            $serv->setName($service['name']);
            $serv->setPrice($service['price']);
            $serv->setShortName($service['shortName']);
            $manager->persist($serv);
        }


        $manager->flush();
    }
}
