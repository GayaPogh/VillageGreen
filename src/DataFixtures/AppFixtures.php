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

        // Sous-catégories + assign images sc1.jpeg, sc2.jpeg...
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
                $sub->setImage('sc' . $imageIndex . '.jpeg');
                $imageIndex++;
                $manager->persist($sub);
            }
        }

        // Fournisseur

        $fournisseurs = [
    ['nom' => 'Yamaha', 'categorie' => 'Claviers'],
    ['nom' => 'Fender', 'categorie' => 'Cordes'],
    ['nom' => 'Pearl', 'categorie' => 'Percussion'],
    ['nom' => 'Selmer', 'categorie' => 'Bois'],
    ['nom' => 'Roland', 'categorie' => 'Claviers'],
    ['nom' => 'Zildjian', 'categorie' => 'Percussion'],
    
];

foreach ($fournisseurs as $data) {
    $f = new Fournisseur();
    $f->setNom($data['nom']);
    $f->setEmail(strtolower($data['nom']).'@example.com');
    $f->setTelephone('01 23 45 67 89');
    $f->addCategorie($mainCategories[$data['categorie']]);
    $manager->persist($f);
}
        $manager->flush();
    }
}
