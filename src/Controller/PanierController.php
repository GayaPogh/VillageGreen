<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{
   #[Route('/panier/add/{id}', name: 'panier_add')]
public function add(Produit $produit, SessionInterface $session): JsonResponse
{
    $panier = $session->get('panier', []);

    $id = $produit->getId();

    if(isset($panier[$id])) {
        // Եթե արդեն կա, այլևս ոչինչ չենք անում
        return new JsonResponse([
            'success' => false,
            'message' => $produit->getLibelleLong() . ' est déjà dans le panier !',
            'count' => array_sum($panier)
        ]);
    } else {
        // Եթե չկա, ավելացնում ենք 1-ով
        $panier[$id] = 1;
    }

    $session->set('panier', $panier);

    return new JsonResponse([
        'success' => true,
        'message' => $produit->getLibelleLong() . ' ajouté au panier !',
        'count' => array_sum($panier)
    ]);
}

   #[Route('/panier', name: 'panier_index')]
public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
{
    $panier = $session->get('panier', []); // {id => qty}
    $panierDetails = [];
    $total = 0;

    foreach ($panier as $id => $quantity) {
        $produit = $produitRepository->find($id);
        if ($produit) {
            $subtotal = $produit->getPrixAchat() * $quantity;
            $total += $subtotal;

            $panierDetails[] = [
                'produit' => $produit,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }

    return $this->render('panier/index.html.twig', [
        'items' => $panierDetails,
        'total' => $total,
    ]);
}

}
