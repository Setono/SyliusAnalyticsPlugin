<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Items;

use Setono\GoogleAnalyticsEvents\Event\Item\Item;
use Sylius\Component\Core\Model\OrderInterface;

interface ItemsResolverInterface
{
    /**
     * @return list<Item>
     */
    public function resolveFromOrder(OrderInterface $order): array;
}
