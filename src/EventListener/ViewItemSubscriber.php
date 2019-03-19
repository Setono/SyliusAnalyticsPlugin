<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Tag\GtagTag;
use Setono\SyliusAnalyticsPlugin\Tag\GtagTagInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ViewItemSubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.show' => [
                'view',
            ],
        ];
    }

    public function view(ResourceControllerEvent $event): void
    {
        $product = $event->getSubject();

        if (!$product instanceof ProductInterface) {
            return;
        }

        if(!$this->hasProperties()) {
            return;
        }

        $item = $this->createItem((string) $product->getCode(), (string) $product->getName());

        $this->tagBag->add(new GtagTag(
            GtagTagInterface::EVENT_VIEW_ITEM,
            Tags::TAG_VIEW_ITEM,
            ['items' => [$item]]
        ), TagBagInterface::SECTION_BODY_END);
    }
}
