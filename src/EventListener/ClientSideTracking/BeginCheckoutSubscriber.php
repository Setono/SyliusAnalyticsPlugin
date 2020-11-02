<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener\ClientSideTracking;

use Setono\TagBag\Tag\GtagEvent;
use Setono\TagBag\Tag\GtagEventInterface;
use Setono\TagBag\Tag\GtagLibrary;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class BeginCheckoutSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'track',
        ];
    }

    public function track(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest() || !$this->isShopContext($request)) {
            return;
        }

        if (!$request->attributes->has('_route')) {
            return;
        }

        $route = $request->attributes->get('_route');
        if ('sylius_shop_checkout_start' !== $route) {
            return;
        }

        if (!$this->hasProperties()) {
            return;
        }

        $this->tagBag->addTag(
            (new GtagEvent(GtagEventInterface::EVENT_BEGIN_CHECKOUT))
                ->addDependency(GtagLibrary::NAME)
        );
    }
}
