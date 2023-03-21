<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Item\Item;
use Sylius\Component\Core\Model\OrderInterface;

interface ItemsResolverInterface
{
    /**
     * @return list<Item>
     */
    public function resolveFromOrder(OrderInterface $order): array;
}
