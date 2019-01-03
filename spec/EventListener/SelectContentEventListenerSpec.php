<?php

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\EventListener\SelectContentEventListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Request;

class SelectContentEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SelectContentEventListener::class);
    }

    function it_cannot_add_select_content_event_if_not_master_request(
        GetResponseEvent $event
    ): void
    {
        $event->isMasterRequest()->willReturn(false);

        $this->onKernelRequest($event);
    }

    function it_add_select_content_event(
        SessionInterface $session,
        GetResponseEvent $event,
        Request $request
    ): void
    {
        $event->isMasterRequest()->willReturn(true);

        $session->has('google_analytics_events')->willReturn(true);

        $event->getRequest()->willReturn($request);

        $request->getUri()->willReturn('taxons');

        $session->get('google_analytics_events')->shouldBeCalled();
        $session->set('google_analytics_events' ,[['name' => 'SelectContent']])->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    function it_cannot_add_select_content_event(
        SessionInterface $session,
        GetResponseEvent $event,
        Request $request
    ): void
    {
        $event->isMasterRequest()->willReturn(true);

        $session->has('google_analytics_events')->willReturn(false);

        $event->getRequest()->willReturn($request);

        $request->getUri()->willReturn('taxons');

        $session->set('google_analytics_events', [])->shouldBeCalled();
        $session->get('google_analytics_events')->shouldBeCalled();
        $session->set('google_analytics_events' ,[['name' => 'SelectContent']])->shouldBeCalled();

        $this->onKernelRequest($event);
    }
}
