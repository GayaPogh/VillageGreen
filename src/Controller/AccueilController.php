<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
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
