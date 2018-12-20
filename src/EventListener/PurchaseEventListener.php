<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class PurchaseEventListener
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function purchase(ResourceControllerEvent $resourceControllerEvent): void
    {
        /** @var OrderInterface $order */
        $order = $resourceControllerEvent->getSubject();

        if (!$this->session->has('google_analytics_events')) {
            $this->session->set('google_analytics_events', []);
        }

        $googleAnalyticsEvents = $this->session->get('google_analytics_events');

        $googleAnalyticsEvents[] = [
            'name' => 'Purchase',
            'options' => [
                'transaction_id' => $order->getId(),
                'value' => $order->getTotal() / 100,
                'currency' => $order->getCurrencyCode(),
            ], ];

        $this->session->set('google_analytics_events', $googleAnalyticsEvents);
    }
}
