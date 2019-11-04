<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

interface BuilderInterface
{
    public function getData(): array;

    public function getJson(): string;
}
