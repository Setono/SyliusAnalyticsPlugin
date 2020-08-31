<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\TagBag\Tag\GtagEvent;
use Setono\TagBag\Tag\GtagEventInterface;
use Setono\TagBag\Tag\GtagLibrary;

final class SignUpSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.customer.post_register' => 'signUp',
        ];
    }

    public function signUp(): void
    {
        if (!$this->isShopContext() || !$this->hasProperties()) {
            return;
        }

        $this->tagBag->addTag(
            (new GtagEvent(GtagEventInterface::EVENT_SIGN_UP))
                ->addDependency(GtagLibrary::NAME)
        );
    }
}
