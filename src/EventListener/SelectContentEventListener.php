<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

final class SelectContentEventListener
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$this->session->has('google_analytics_events')) {
            $this->session->set('google_analytics_events', []);
        }
        if (preg_match('/taxons/', $event->getRequest()->getUri())) {
            $googleAnalyticsEvents = $this->session->get('google_analytics_events');

            $googleAnalyticsEvents[] = ['name' => 'SelectContent'];

            $this->session->set('google_analytics_events', $googleAnalyticsEvents);
        }
    }
}
