<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Message\Command;

use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\PurchaseEvent;

final class SendPurchaseEvent implements CommandInterface
{
    public PurchaseEvent $event;

    public int $orderId;

    public string $clientId;

    public function __construct(PurchaseEvent $event, int $orderId, string $clientId)
    {
        $this->event = $event;
        $this->orderId = $orderId;
        $this->clientId = $clientId;
    }
}
