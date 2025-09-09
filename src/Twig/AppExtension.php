<?php

namespace App\Twig;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\ProduitRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private RequestStack $requestStack,
        private ProduitRepository $produitRepository,
        private FormFactoryInterface $formFactory
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('panier_count', [$this, 'getPanierCount']),
            new TwigFunction('panier_items', [$this, 'getPanierItems']),
            new TwigFunction('panier_total', [$this, 'getPanierTotal']),
            new TwigFunction('registration_form', [$this, 'getRegistrationForm']),
        ];
    }

    // ===== Panier =====
    public function getPanierCount(): int
    {
        $session = $this->requestStack->getSession();
        $panier = $session ? $session->get('panier', []) : [];
        return array_sum($panier);
    }

    public function getPanierItems(): array
    {
        $session = $this->requestStack->getSession();
        $panier = $session ? $session->get('panier', []) : [];
        $items = [];

        foreach ($panier as $id => $quantity) {
            $produit = $this->produitRepository->find($id);
            if ($produit) {
                $items[] = [
                    'produit' => $produit,
                    'quantity' => $quantity,
                    'subtotal' => $produit->getPrixAchat() * $quantity
                ];
            }
        }

        return $items;
    }

    public function getPanierTotal(): float
    {
        $total = 0;
        foreach ($this->getPanierItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    // ===== Registration Form =====
    public function getRegistrationForm(): \Symfony\Component\Form\FormView
    {
        $utilisateur = new Utilisateur();
        $form = $this->formFactory->create(RegistrationFormType::class, $utilisateur);
        return $form->createView();
    }
}
