<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\Continent;
use App\Entity\Dispose;
use App\Entity\Famille;
use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $p1 = new Personne();
        $p1->setNom("Milo");
        $manager->persist($p1);

        $p2 = new Personne();
        $p2->setNom("Tya");
        $manager->persist($p2);

        $p3 = new Personne();
        $p3->setNom("Lili");
        $manager->persist($p3);

        $continent1 = new Continent();
        $continent1->setLibelle('Europe');
        $manager->persist($continent1);

        $continent2 = new Continent();
        $continent2->setLibelle('Asie');
        $manager->persist($continent2);

        $continent3 = new Continent();
        $continent3->setLibelle('Afrique');
        $manager->persist($continent3);

        $continent4 = new Continent();
        $continent4->setLibelle('Océanie');
        $manager->persist($continent4);

        $continent5 = new Continent();
        $continent5->setLibelle('Amérique');
        $manager->persist($continent5);

        $c1 = new Famille();
        $c1->setLibelle('mammifères')
        ->setDescription('Animaux vertébrés nourrissant leurs petits avec du lait')
        ;
        $manager->persist($c1);

        $c2 = new Famille();
        $c2->setLibelle('reptiles')
        ->setDescription('Animaux vertébrés qui rampent')
        ;
        $manager->persist($c2);

        $c3 = new Famille();
        $c3->setLibelle('poissons')
        ->setDescription('Animaux invertébrés du monde aquatique')
        ;
        $manager->persist($c3);


        $a1 = new Animal();
        $a1->setNom('chien')
            ->setDescription('Le meilleur ami de l\'homme')
            ->setImage('chien.png')
            ->setPoids(10)
            ->setDangereux(false)
            ->setFamille($c1)
            ->addContinent($continent1)
            ->addContinent($continent2)
            ->addContinent($continent3)
            ->addContinent($continent4)
            ->addContinent($continent5)
        ;
        $manager->persist($a1);

        $a2 = new Animal();
        $a2->setNom('cochon')
            ->setDescription('Un animal qui aime la boue')
            ->setImage('cochon.png')
            ->setPoids(100)
            ->setDangereux(false)
            ->setFamille($c1)
            ->addContinent($continent1)
            ->addContinent($continent5)
        ;
        $manager->persist($a2);

        $a3 = new Animal();
        $a3->setNom('croco')
            ->setDescription('Un animal dangeureux qui vit dans les marais')
            ->setImage('croco.png')
            ->setPoids(300)
            ->setDangereux(true)
            ->setFamille($c2)
            ->addContinent($continent3)
            ->addContinent($continent4)
        ;
        $manager->persist($a3);

        $a4 = new Animal();
        $a4->setNom('requin')
            ->setDescription('Un animal très dangeureux qui vit dans l\'eau')
            ->setImage('requin.png')
            ->setPoids(1500)
            ->setDangereux(true)
            ->setFamille($c3)
            ->addContinent($continent4)
            ->addContinent($continent5)
        ;
        $manager->persist($a4);

        $a5 = new Animal();
        $a5->setNom('serpent')
            ->setDescription('Un animal au venin mortel')
            ->setImage('serpent.png')
            ->setPoids(50)
            ->setDangereux(true)
            ->setFamille($c2)
            ->addContinent($continent2)
            ->addContinent($continent3)
            ->addContinent($continent4)
        ;
        $manager->persist($a5);

        $d1 = new Dispose();
        $d1->setPersonne($p1)
            ->setAnimal($a1)
            ->setNb(3)
        ;
        $manager->persist($d1);

        $d2 = new Dispose();
        $d2->setPersonne($p3)
            ->setAnimal($a2)
            ->setNb(6)
        ;
        $manager->persist($d2);

        $d3 = new Dispose();
        $d3->setPersonne($p2)
            ->setAnimal($a4)
            ->setNb(1)
        ;
        $manager->persist($d3);

        $manager->flush();
    }
}
