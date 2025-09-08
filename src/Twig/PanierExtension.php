<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PanierExtension extends AbstractExtension
{
    public function __construct(private RequestStack $requestStack) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('panier_count', [$this, 'getPanierCount']),
        ];
    }

    public function getPanierCount(): int
    {
        $session = $this->requestStack->getSession();
        $panier = $session ? $session->get('panier', []) : [];

        return array_sum($panier);
    }
}
