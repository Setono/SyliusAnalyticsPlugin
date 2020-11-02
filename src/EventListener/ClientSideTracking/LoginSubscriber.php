<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener\ClientSideTracking;

use Setono\TagBag\Tag\GtagEvent;
use Setono\TagBag\Tag\GtagEventInterface;
use Setono\TagBag\Tag\GtagLibrary;

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

        $this->tagBag->addTag(
            (new GtagEvent(GtagEventInterface::EVENT_LOGIN))
                ->addDependency(GtagLibrary::NAME)
        );
    }
}
