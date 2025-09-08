<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class TwigGlobalSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private RequestStack $requestStack;

    public function __construct(Environment $twig, RequestStack $requestStack)
    {
        $this->twig = $twig;
        $this->requestStack = $requestStack;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $session = $this->requestStack->getSession();
        $panier = $session ? $session->get('panier', []) : [];
        $totalItems = array_sum($panier);

        $this->twig->addGlobal('panier_count', $totalItems);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
