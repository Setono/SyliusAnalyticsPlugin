<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Item\Item;
use Sylius\Component\Core\Model\OrderItemInterface;

interface ItemResolverInterface
{
    public function resolveFromOrderItem(OrderItemInterface $orderItem): Item;
}
