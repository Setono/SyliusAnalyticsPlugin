<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Model;

use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;

interface OrderInterface extends BaseOrderInterface
{
    /**
     * The Google Analytics client id for the user that made this order
     */
    public function getGoogleAnalyticsClientId(): ?string;

    public function setGoogleAnalyticsClientId(?string $googleAnalyticsClientId): void;
}
