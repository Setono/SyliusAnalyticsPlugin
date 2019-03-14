<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class AddToCartEventListener
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function addToCart(): void
    {
        if (!$this->session->has('google_analytics_events')) {
            $this->session->set('google_analytics_events', []);
        }

        $googleAnalyticsEvents = $this->session->get('google_analytics_events');

        $googleAnalyticsEvents[] = ['name' => 'AddToCart'];

        $this->session->set('google_analytics_events', $googleAnalyticsEvents);
    }
}
