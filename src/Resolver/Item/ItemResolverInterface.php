<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver\Item;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Item\Item;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface ItemResolverInterface
{
    public function resolveFromOrderItem(OrderItemInterface $orderItem): Item;

    public function resolveFromProduct(ProductInterface $product): Item;
}
