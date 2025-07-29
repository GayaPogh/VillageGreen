<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie', name: 'app_categorie_')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findRootCategories();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

   #[Route('/{id}/sous-categories', name: 'sous')]
public function sousCategories(Categorie $categorie): Response
{
    return $this->render('accueil/categorie.html.twig', [
        'categorie' => $categorie,
        'sousCategories' => $categorie->getEnfants(),
    ]);
}

}
