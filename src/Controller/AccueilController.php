<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FournisseurRepository;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(
        CategorieRepository $categorieRepository,
        FournisseurRepository $fournisseurRepository
    ): Response
    {
        $categories = $categorieRepository->findBy(['parent' => null]);
        $fournisseurs = $fournisseurRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'fournisseurs' => $fournisseurs,
        ]);
    }

   #[Route('/categorie/{id}', name: 'categorie_show')]
public function showCategorie(Categorie $categorie): Response
{
    $sousCategories = $categorie->getEnfants();

    return $this->render('accueil/categorie.html.twig', [
        'categorie' => $categorie,
        'sousCategories' => $sousCategories,
    ]);
}

#[Route('/souscategorie/{id}', name: 'souscategorie_show')]
public function showSousCategorie(Categorie $sousCategorie): Response
{
    // Ստանալ արտադրանքներ սուբկատեգորիայի և sub-sub categories–ից
    $produits = $sousCategorie->getProduits()->toArray();

    foreach ($sousCategorie->getEnfants() as $enfant) {
        $produits = array_merge($produits, $enfant->getProduits()->toArray());
    }

    return $this->render('accueil/souscategorie.html.twig', [
        'sousCategorie' => $sousCategorie,
        'produits' => $produits,
    ]);
}

}
