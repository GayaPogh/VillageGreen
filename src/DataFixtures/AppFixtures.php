<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
   public function load(ObjectManager $manager): void
{
    $categorie = new Categorie();
    $categorie->setNom('Guitares');
    $manager->persist($categorie);

    $fournisseur = new Fournisseur();
    $fournisseur->setNom('Yamaha');
    $fournisseur->addcategorie($categorie);
    $manager->persist($fournisseur);

    $manager->flush();
}
}