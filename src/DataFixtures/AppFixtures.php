<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Fournisseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Գլխավոր կատեգորիաների անունները և իրենց ենթակատեգորիաները
        $categoriesData = [
            'Claviers' => ['Synthétiseur', 'Piano numérique'],
            'Cordes' => ['Guitare', 'Violon'],
            'Cuivres' => ['Trompette', 'Tuba'],
            'Bois' => ['Flûte', 'Clarinette'],
            'Percussion' => ['Caisse claire', 'Xylophone'],
            'Accessoires' => ['Câbles', 'Support']
        ];

        // Գլխավոր կատեգորիաները պահելու համար
        $categoryObjects = [];

        // Նախ ստեղծում ենք գլխավոր կատեգորիաները
        foreach ($categoriesData as $categoryName => $subCategories) {
            $parent = new Categorie();
            $parent->setNom($categoryName);
            $manager->persist($parent);

            $categoryObjects[$categoryName] = $parent;
        }

        // Այժմ ստեղծում ենք ենթակատեգորիաները և կապում parent-ին
        foreach ($categoriesData as $categoryName => $subCategories) {
            $parent = $categoryObjects[$categoryName];
            foreach ($subCategories as $subCategoryName) {
                $subCategory = new Categorie();
                $subCategory->setNom($subCategoryName);
                $subCategory->setParent($parent);
                $manager->persist($subCategory);
            }
        }

        // Ստեղծում ենք fournisseur
        $fournisseur = new Fournisseur();
        $fournisseur->setNom('Yamaha');

        // Կապում ենք fournisseur-ին մի քանի կատեգորիաների հետ, օրինակ՝ Claviers
        if (isset($categoryObjects['Claviers'])) {
            $fournisseur->addCategorie($categoryObjects['Claviers']);
        }

        $manager->persist($fournisseur);

        $manager->flush();
    }
}
