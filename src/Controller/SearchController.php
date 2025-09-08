<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search_products')]
    public function search(Request $request, ProduitRepository $produitRepo): Response
    {
        $query = $request->query->get('q', '');
        $produits = [];

        if ($query) {
            $produits = $produitRepo->createQueryBuilder('p')
                ->where('p.libelleLong LIKE :q OR p.libelleCourt LIKE :q')
                ->setParameter('q', '%'.$query.'%')
                ->getQuery()
                ->getResult();
        }

        return $this->render('search/results.html.twig', [
            'query' => $query,
            'produits' => $produits,
        ]);
    }
}
