<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\EventListener\SearchEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SearchEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SearchEventListener::class);
    }

    function it_cannot_add_search_event_if_not_master_request(
        GetResponseEvent $event
    ): void {
        $event->isMasterRequest()->willReturn(false);

        $this->onKernelRequest($event);
    }

    function it_add_search_event(
        SessionInterface $session,
        GetResponseEvent $event,
        Request $request
    ): void {
        $event->isMasterRequest()->willReturn(true);

        $session->has('google_analytics_events')->willReturn(true);

        $event->getRequest()->willReturn($request);

        $request->getUri()->willReturn('search');

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events', [['name' => 'Search']])->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    function it_assign_event_to_session_before_add(
        SessionInterface $session,
        GetResponseEvent $event,
        Request $request
    ): void {
        $event->isMasterRequest()->willReturn(true);

        $session->has('google_analytics_events')->willReturn(false);

        $event->getRequest()->willReturn($request);

        $request->getUri()->willReturn('search');

        $session->set('google_analytics_events', [])->shouldBeCalled();

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events', [['name' => 'Search']])->shouldBeCalled();

        $this->onKernelRequest($event);
    }
}
