<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Context;

use Setono\SyliusAnalyticsPlugin\Model\PropertyInterface;

interface PropertyContextInterface
{
    /**
     * Returns the properties enabled for the active channel
     *
     * @return PropertyInterface[]
     */
    public function getProperties(): array;
}
