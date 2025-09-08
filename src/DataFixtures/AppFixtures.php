<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Fournisseur;
use App\Entity\Produit;
use Faker\Factory as Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $mainCategories = [];
        $subCategoriesList = [];

        // 1️⃣ Գլխավոր կատեգորիաներ
        foreach (['Claviers', 'Cordes', 'Cuivres', 'Bois', 'Percussion', 'Accessoires'] as $name) {
            $cat = new Categorie();
            $cat->setNom($name);
            $manager->persist($cat);
            $mainCategories[$name] = $cat;
        }

        // 2️⃣ Sous-catégories + images
        $subCategoryData = [
            'Claviers'    => ['Synthétiseur', 'Piano numérique', 'Royale'],
            'Cordes'      => ['Guitare', 'Violon', 'Violoncelle'],
            'Cuivres'     => ['Trompette', 'Tuba', 'Saxophone'],
            'Bois'        => ['Flûte', 'Clarinette', 'Accordion'],
            'Percussion'  => ['Caisse claire', 'Xylophone', 'Batterie'],
            'Accessoires' => ['Câbles', 'Support', 'Casques']
        ];

        $imageIndex = 1;
        foreach ($subCategoryData as $parentName => $children) {
            $parent = $mainCategories[$parentName];

            foreach ($children as $childName) {
                $sub = new Categorie();
                $sub->setNom($childName);
                $sub->setParent($parent);
                $sub->setImage('sc' . $imageIndex . '.jpeg');
                $manager->persist($sub);
                $subCategoriesList[] = $sub; // պահում ենք array–ում products–ի համար
                $imageIndex++;
            }
        }

        // 3️⃣ Fournisseurs
        $fournisseursArray = [];
        $fournisseursData = [
            ['nom' => 'Yamaha', 'categorie' => 'Claviers'],
            ['nom' => 'Fender', 'categorie' => 'Cordes'],
            ['nom' => 'Pearl', 'categorie' => 'Percussion'],
            ['nom' => 'Selmer', 'categorie' => 'Bois'],
            ['nom' => 'Roland', 'categorie' => 'Claviers'],
            ['nom' => 'Zildjian', 'categorie' => 'Percussion'],
        ];

        foreach ($fournisseursData as $data) {
            $f = new Fournisseur();
            $f->setNom($data['nom']);
            $f->setEmail(strtolower($data['nom']).'@example.com');
            $f->setTelephone('01 23 45 67 89');
            $f->addCategorie($mainCategories[$data['categorie']]);
            $manager->persist($f);
            $fournisseursArray[] = $f;
        }

        // 4️⃣ Produit–ներ
        $faker = Faker::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $produit = new Produit();
            $produit->setLibelleLong($faker->sentence(3));
            $produit->setLibelleCourt($faker->word());
            $produit->setPrixAchat($faker->randomFloat(2, 50, 2000));
            $produit->setPhoto("p" . $i . ".jpeg");
            $produit->setReferenceFournisseur(strtoupper($faker->bothify('REF-####')));
            $produit->setActif($faker->boolean(90));
            $produit->setPublie($faker->boolean(70));
            $produit->setCreatedAt(new \DateTimeImmutable());
            $produit->setUpdatedAt(new \DateTimeImmutable());

            // Random sous-catégorie
            $randomCategorie = $faker->randomElement($subCategoriesList);
            $produit->setCategorie($randomCategorie);

            // Random fournisseur
            $randomFournisseur = $faker->randomElement($fournisseursArray);
            // եթե կա relation Produit–Fournisseur
            // $produit->setFournisseur($randomFournisseur);

            $manager->persist($produit);
        }

        $manager->flush();
    }
}
