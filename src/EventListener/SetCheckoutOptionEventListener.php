<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SetCheckoutOptionEventListener
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setCheckoutOption(ResourceControllerEvent $resourceControllerEvent): void
    {
        /** @var OrderInterface $order */
        $order = $resourceControllerEvent->getSubject();

        if (!$this->session->has('google_analytics_events')) {
            $this->session->set('google_analytics_events', []);
        }

        $googleAnalyticsEvents = $this->session->get('google_analytics_events');

        $googleAnalyticsEvents[] = [
            'name' => 'SetCheckoutOption',
            'options' => [
                'checkout_state' => $order->getCheckoutState(),
                'value' => $order->getTotal() / 100,
            ], ];

        $this->session->set('google_analytics_events', $googleAnalyticsEvents);
    }
}
