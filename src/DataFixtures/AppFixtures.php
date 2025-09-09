<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Client;
use App\Entity\Commercial;
use App\Entity\Fournisseur;
use App\Entity\Produit;
use Faker\Factory as Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        // 1️⃣ Commercials
        $commercials = [];
        foreach (['Jean Dupont', 'Marie Curie', 'Louis Pasteur'] as $name) {
            $c = new Commercial();
            $c->setId(strtoupper(substr($name,0,1)) . rand(100,999)); // <-- setId, ոչ setIdCommercial
            $c->setNom($name);
            $c->setEmail(strtolower(str_replace(' ','.',$name)) . '@example.com');
            $manager->persist($c);
            $commercials[] = $c;
}
        // 2️⃣ Clients
        $clients = [];
        for ($i=0; $i<10; $i++) {
                        $client = new Client();
            $client->setNom($faker->lastName());
            $client->setPrenom($faker->firstName());
            // $client->setTypeClient('particulier'); // եթե entity–ում չկա, հանիր
            $client->setTelephone($faker->phoneNumber());
            $client->setEmail(strtolower($client->getPrenom().'.'.$client->getNom().'@example.com')); // <-- ավելացնել email
            $client->setCommercial($faker->randomElement($commercials));
            $manager->persist($client);

            $clients[] = $client;
}

        // 3️⃣ Categorie + sous-categories
        $mainCategories = [];
        $subCategoriesList = [];
        $imageIndex = 1;

        foreach (['Claviers', 'Cordes', 'Cuivres', 'Bois', 'Percussion', 'Accessoires'] as $name) {
            $cat = new Categorie();
            $cat->setNom($name);
            $manager->persist($cat);
            $mainCategories[$name] = $cat;
        }

        $subCategoryData = [
            'Claviers'    => ['Synthétiseur', 'Piano numérique', 'Royale'],
            'Cordes'      => ['Guitare', 'Violon', 'Violoncelle'],
            'Cuivres'     => ['Trompette', 'Tuba', 'Saxophone'],
            'Bois'        => ['Flûte', 'Clarinette', 'Accordion'],
            'Percussion'  => ['Caisse claire', 'Xylophone', 'Batterie'],
            'Accessoires' => ['Câbles', 'Support', 'Casques']
        ];

        foreach ($subCategoryData as $parentName => $children) {
            $parent = $mainCategories[$parentName];
            foreach ($children as $childName) {
                $sub = new Categorie();
                $sub->setNom($childName);
                $sub->setParent($parent);
                $sub->setImage("sc{$imageIndex}.jpeg");
                $manager->persist($sub);
                $subCategoriesList[] = $sub;
                $imageIndex++;
            }
        }

        // 4️⃣ Fournisseurs
        $fournisseursArray = [];
        $fournisseursData = [
            ['nom'=>'Yamaha','categorie'=>'Claviers'],
            ['nom'=>'Fender','categorie'=>'Cordes'],
            ['nom'=>'Pearl','categorie'=>'Percussion'],
            ['nom'=>'Selmer','categorie'=>'Bois'],
            ['nom'=>'Roland','categorie'=>'Claviers'],
            ['nom'=>'Zildjian','categorie'=>'Percussion']
        ];

        foreach ($fournisseursData as $data) {
            $f = new Fournisseur();
            $f->setNom($data['nom']);
            $f->setEmail(strtolower($data['nom']) . '@example.com');
            $f->setTelephone('01 23 45 67 89');
            $f->addCategorie($mainCategories[$data['categorie']]);
            $manager->persist($f);
            $fournisseursArray[] = $f;
        }

        // 5️⃣ Produits
        for ($i=0; $i<30; $i++) {
            $p = new Produit();
            $p->setLibelleLong($faker->sentence(3));
            $p->setLibelleCourt($faker->word());
            $p->setPrixAchat($faker->randomFloat(2,50,2000));
            $p->setPhoto("p{$i}.jpeg");
            $p->setReferenceFournisseur(strtoupper($faker->bothify('REF-####')));
            $p->setActif($faker->boolean(90));
            $p->setPublie($faker->boolean(70));
            $p->setCreatedAt(new \DateTimeImmutable());
            $p->setUpdatedAt(new \DateTimeImmutable());
            $p->setCategorie($faker->randomElement($subCategoriesList));
            // եթե Produit entity-ում կա relation Fournisseur:
            // $p->setFournisseur($faker->randomElement($fournisseursArray));
            $manager->persist($p);
        }

        $manager->flush();
    }
}
