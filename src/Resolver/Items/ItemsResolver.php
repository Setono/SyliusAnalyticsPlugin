<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Items;

use Setono\SyliusAnalyticsPlugin\Resolver\Item\ItemResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class ItemsResolver implements ItemsResolverInterface
{
    private ItemResolverInterface $itemResolver;

    public function __construct(ItemResolverInterface $itemResolver)
    {
        $this->itemResolver = $itemResolver;
    }

    public function resolveFromOrder(OrderInterface $order): array
    {
        $items = [];
        foreach ($order->getItems() as $item) {
            $items[] = $this->itemResolver->resolveFromOrderItem($item);
        }

        return $items;
    }
}
