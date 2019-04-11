<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

/**
 * @method ItemBuilder setId(string $id)
 * @method ItemBuilder setName(string $name)
 * @method ItemBuilder setListName(string $listName)
 * @method ItemBuilder setBrand(string $brand)
 * @method ItemBuilder setCategory(string $category)
 * @method ItemBuilder setVariant(string $variant)
 * @method ItemBuilder setListPosition(int $listPosition)
 * @method ItemBuilder setQuantity(int $quantity)
 * @method ItemBuilder setPrice(float $price)
 */
final class ItemBuilder extends Builder
{
    public const EVENT_NAME = 'setono_sylius_analytics.builder.item';
}
