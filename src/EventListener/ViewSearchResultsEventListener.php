<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

final class ViewSearchResultsEventListener
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = Request::createFromGlobals();
        $search = $request->query->get('criteria');
        $search_value = $search['search']['value'];

        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$this->session->has('google_analytics_events')) {
            $this->session->set('google_analytics_events', []);
        }

        if (false !== strpos($event->getRequest()->getUri(), $search_value)) {
            $googleAnalyticsEvents = $this->session->get('google_analytics_events');

            $googleAnalyticsEvents[] = ['name' => 'ViewSearchResults'];

            $this->session->set('google_analytics_events', $googleAnalyticsEvents);
        }
    }
}
