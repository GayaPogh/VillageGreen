<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Produit;
use App\Form\RegistrationFormType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PanierController extends AbstractController
{
    // ---- Ավելացնել ապրանք ----
    #[Route('/panier/add/{id}', name: 'panier_add')]
    public function add(Produit $produit, SessionInterface $session): JsonResponse
    {
        $panier = $session->get('panier', []);
        $id = $produit->getId();

        if(isset($panier[$id])) {
            return new JsonResponse([
                'success' => false,
                'message' => $produit->getLibelleLong() . ' est déjà dans le panier !',
                'count' => array_sum($panier)
            ]);
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        return new JsonResponse([
            'success' => true,
            'message' => $produit->getLibelleLong() . ' ajouté au panier !',
            'count' => array_sum($panier)
        ]);
    }

    // ---- Ցուցադրել panier ----
    #[Route('/panier', name: 'panier_index')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository, Request $request): Response
    {
        $panier = $session->get('panier', []);
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

        // Registration Form
        $utilisateur = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $utilisateur)->createView();

        return $this->render('panier/index.html.twig', [
            'items' => $panierDetails,
            'total' => $total,
            'form' => $form, // Twig-ում մոդալի համար
        ]);
    }

    // ---- Հեռացնել ապրանք ----
    #[Route('/panier/remove/{id}', name: 'panier_remove')]
    public function remove(Produit $produit, SessionInterface $session): JsonResponse
    {
        $panier = $session->get('panier', []);
        unset($panier[$produit->getId()]);
        $session->set('panier', $panier);

        return new JsonResponse([
            'success' => true,
            'count' => array_sum($panier)
        ]);
    }

    // ---- Թարմացնել քանակը ----
    #[Route('/panier/update/{id}/{qty}', name: 'panier_update')]
    public function update(Produit $produit, int $qty, SessionInterface $session): JsonResponse
    {
        $panier = $session->get('panier', []);
        if($qty > 0) {
            $panier[$produit->getId()] = $qty;
        } else {
            unset($panier[$produit->getId()]);
        }
        $session->set('panier', $panier);

        return new JsonResponse([
            'success' => true,
            'count' => array_sum($panier)
        ]);
    }

    #[Route('/panier/valider', name: 'panier_valider')]
public function valider(SessionInterface $session): Response
{
    $panier = $session->get('panier', []);

    if (empty($panier)) {
        $this->addFlash('warning', 'Votre panier est vide.');
        return $this->redirectToRoute('panier_index');
    }

    // 👉 Այստեղ հետո կավելացնենք Commande / Paiement logic
    $session->remove('panier'); // ժամանակավոր՝ մաքրում է panier-ը

    $this->addFlash('success', 'Votre commande a été validée avec succès !');

    return $this->redirectToRoute('app_accueil');
}

}
