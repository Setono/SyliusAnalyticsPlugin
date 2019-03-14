<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Repository;

use Setono\SyliusAnalyticsPlugin\Model\AnalyticsConfigInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AnalyticsConfigRepositoryInterface extends RepositoryInterface
{
    public function findConfig(): ?AnalyticsConfigInterface;
}
