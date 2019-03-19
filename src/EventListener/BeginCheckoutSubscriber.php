<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class BeginCheckoutSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                'track',
            ],
        ];
    }

    public function track(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

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

        $this->tagBag->add(new GtagTag(
            GtagTagInterface::EVENT_BEGIN_CHECKOUT,
            Tags::TAG_BEGIN_CHECKOUT
        ), TagBagInterface::SECTION_BODY_END);
    }
}
