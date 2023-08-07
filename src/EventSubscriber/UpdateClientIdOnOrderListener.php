<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Setono\GoogleAnalyticsBundle\Context\ClientIdContextInterface;
use Setono\SyliusAnalyticsPlugin\Model\OrderInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UpdateClientIdOnOrderListener implements EventSubscriberInterface
{
    private ClientIdContextInterface $clientIdContext;

    public function __construct(ClientIdContextInterface $clientIdContext)
    {
        $this->clientIdContext = $clientIdContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.pre_complete' => 'update',
        ];
    }

    public function update(ResourceControllerEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $order->setGoogleAnalyticsClientId($this->clientIdContext->getClientId());
    }
}
