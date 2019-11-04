<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

use InvalidArgumentException;

interface ItemsAwareBuilderInterface extends BuilderInterface
{
    /**
     * @param array|BuilderInterface|mixed $item
     *
     * @throws InvalidArgumentException if $item is not correct type
     */
    public function addItem($item): BuilderInterface;
}
