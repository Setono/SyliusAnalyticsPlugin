<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\TagBag\TagBagInterface;

final class AddPaymentInfoSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_payment' => [
                'add',
            ],
        ];
    }

    public function add(): void
    {
        $this->tagBag->add(new GtagTag(
            GtagTagInterface::EVENT_ADD_PAYMENT_INFO,
            Tags::TAG_ADD_PAYMENT_INFO
        ),TagBagInterface::SECTION_BODY_END);
    }
}
