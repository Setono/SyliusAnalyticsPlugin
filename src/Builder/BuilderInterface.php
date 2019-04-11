<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Builder;

interface BuilderInterface
{
    public static function create();

    public static function createFromJson(string $json);

    public function getData(): array;

    public function getJson(): string;
}
