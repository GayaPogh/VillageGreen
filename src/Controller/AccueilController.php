<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findBy(['parent' => null]);


        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categorie/{id}', name: 'categorie_show')]
    public function showCategorie(Categorie $categorie): Response
    {
        return $this->render('accueil/categorie.html.twig', [
            'categorie' => $categorie,
            'sousCategories' => $categorie->getEnfants()
        ]);
    }
}
