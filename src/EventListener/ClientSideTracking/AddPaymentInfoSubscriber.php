<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener\ClientSideTracking;

use Setono\TagBag\Tag\GtagEvent;
use Setono\TagBag\Tag\GtagEventInterface;
use Setono\TagBag\Tag\GtagLibrary;

final class AddPaymentInfoSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_payment' => 'track',
        ];
    }

    public function track(): void
    {
        if (!$this->isShopContext() || !$this->hasProperties()) {
            return;
        }

        $this->tagBag->addTag(
            (new GtagEvent(GtagEventInterface::EVENT_ADD_PAYMENT_INFO))
                ->addDependency(GtagLibrary::NAME)
        );
    }
}
