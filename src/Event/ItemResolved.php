<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

use Setono\GoogleAnalyticsEvents\Event\Item\Item;

/**
 * Is fired when an item has been resolved. Use this to do common manipulations on the item before it's sent to Google
 */
final class ItemResolved
{
    public Item $item;

    /** @var array<string, mixed> */
    public array $context = [];

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(Item $item, array $context = [])
    {
        $this->item = $item;
        $this->context = $context;
    }
}
