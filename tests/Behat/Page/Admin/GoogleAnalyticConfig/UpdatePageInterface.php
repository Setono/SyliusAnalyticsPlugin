<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Behat\Page\Admin\GoogleAnalyticConfig;

use Sylius\Behat\Page\Admin\Crud\UpdatePageInterface as BaseUpdatePageInterface;

interface UpdatePageInterface extends BaseUpdatePageInterface
{
    public function fillTrackingId(string $trackingId): void;

    public function fillName(string $name): void;
}
