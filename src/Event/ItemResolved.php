<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Item\Item;

/**
 * Is fired when an item has been resolved. Use this to do common manipulations on the item before it's sent to Google
 */
final class ItemResolved
{
    public function __construct(
        public Item $item,
        /** @var array<string, mixed> $context */
        public array $context = [],
    ) {
    }
}
