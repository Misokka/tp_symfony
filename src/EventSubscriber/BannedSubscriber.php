<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

class BannedSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private RouterInterface $router;

    public function __construct(\Symfony\Bundle\SecurityBundle\Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }
    public function onKernelController(ControllerEvent $event): void
    {
        $user = $this->security->getUser();
        $currentRoute = $event->getRequest()->attributes->get('_route');

        if ($user && $this->security->isGranted('ROLE_BANNED') && $currentRoute !== 'app_banned') {
            $event->setController(function () {
                return new RedirectResponse($this->router->generate('app_banned'));
            });
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onKernelController',
        ];
    }
}
