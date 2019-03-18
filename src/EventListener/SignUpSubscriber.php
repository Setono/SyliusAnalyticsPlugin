<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\TagBag\TagBagInterface;

final class SignUpSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.customer.post_register' => [
                'signUp',
            ],
        ];
    }

    public function signUp(): void
    {
        $this->tagBag->add(new GtagTag(
            GtagTagInterface::EVENT_SIGN_UP,
            Tags::TAG_SIGN_UP
        ),TagBagInterface::SECTION_BODY_END);
    }
}
