<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\TagBag\TagBagInterface;

final class LoginSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'security.interactive_login' => [
                'login',
            ],
        ];
    }

    public function login(): void
    {
        $this->tagBag->add(new GtagTag(
            GtagTagInterface::EVENT_LOGIN,
            Tags::TAG_ADD_PAYMENT_INFO
        ), TagBagInterface::SECTION_BODY_END);
    }
}
