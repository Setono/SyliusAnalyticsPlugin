<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

use InvalidArgumentException;

interface ItemsAwareBuilderInterface
{
    /**
     * @param array|BuilderInterface $item
     *
     * @return BuilderInterface
     *
     * @throws InvalidArgumentException if $item is not correct type
     */
    public function addItem($item): BuilderInterface;
}
