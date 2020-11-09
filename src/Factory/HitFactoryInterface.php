<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Factory;

use Setono\SyliusAnalyticsPlugin\Model\HitInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface HitFactoryInterface extends FactoryInterface
{
    public function createNew(): HitInterface;

    public function createWithData(string $url, string $sessionId): HitInterface;
}
