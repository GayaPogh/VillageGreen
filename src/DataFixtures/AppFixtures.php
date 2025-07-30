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
        $mainCategories = [];

        // Գլխավոր կատեգորիաներ
        foreach (['Claviers', 'Cordes', 'Cuivres', 'Bois', 'Percussion', 'Accessoires'] as $name) {
            $cat = new Categorie();
            $cat->setNom($name);
            $manager->persist($cat);
            $mainCategories[$name] = $cat;
        }

        // Sous-catégories + assign images sc1.avif, sc2.avif...
        $subCategoryData = [
            'Claviers'    => ['Synthétiseur', 'Piano numérique', 'Royale'],
            'Cordes'      => ['Guitare', 'Violon'],
            'Cuivres'     => ['Trompette', 'Tuba'],
            'Bois'        => ['Flûte', 'Clarinette'],
            'Percussion'  => ['Caisse claire', 'Xylophone'],
            'Accessoires' => ['Câbles', 'Support']
        ];

        $imageIndex = 1;

        foreach ($subCategoryData as $parentName => $children) {
            $parent = $mainCategories[$parentName];

            foreach ($children as $childName) {
                $sub = new Categorie();
                $sub->setNom($childName);
                $sub->setParent($parent);
                $sub->setImage('sc' . $imageIndex . '.avif');
                $imageIndex++;
                $manager->persist($sub);
            }
        }

        // Fournisseur
        $fournisseur = new Fournisseur();
        $fournisseur->setNom('Yamaha');
        $fournisseur->addCategorie($mainCategories['Claviers']);
        $manager->persist($fournisseur);

        $manager->flush();
    }
}
