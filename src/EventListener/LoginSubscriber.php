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
            'security.interactive_login' => 'login',
        ];
    }

    public function login(): void
    {
        if (!$this->isShopContext() || !$this->hasProperties()) {
            return;
        }

        $this->tagBag->add(new GtagTag(
            Tags::TAG_LOGIN,
            GtagTagInterface::EVENT_LOGIN
        ), TagBagInterface::SECTION_BODY_END);
    }
}
