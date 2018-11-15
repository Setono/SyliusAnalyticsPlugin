<?php

namespace Setono\SyliusAnalyticsPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface GoogleAnalyticConfigInterface extends ResourceInterface
{
    public function getTrackingId(): int;
}
