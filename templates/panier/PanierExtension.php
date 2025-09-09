<?php
// src/Twig/PanierExtension.php
namespace App\Twig;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PanierExtension extends AbstractExtension
{
    public function __construct(
        private RequestStack $requestStack,
        private ProduitRepository $produitRepository
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('panier_items', [$this, 'getPanierItems']),
            new TwigFunction('panier_total', [$this, 'getPanierTotal']),
        ];
    }

    public function getPanierItems(): array
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier', []);

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
}
