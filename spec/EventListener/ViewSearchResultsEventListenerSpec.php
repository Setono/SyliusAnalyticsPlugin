<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\EventListener\ViewSearchResultsEventListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ViewSearchResultsEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ViewSearchResultsEventListener::class);
    }

    function it_cannot_add_view_search_results_event_if_not_master_request(
        GetResponseEvent $event,
        Request $request,
        ParameterBag $parameterBag
    ): void {
        $event->getRequest()->willReturn($request);
        $request->query = $parameterBag;
        $event->isMasterRequest()->willReturn(false);

        $this->onKernelRequest($event);
    }

    function it_cannot_add_view_search_event_if_wrong_uri(
        SessionInterface $session,
        GetResponseEvent $event,
        Request $request,
        ParameterBag $parameterBag
    ): void {
        $event->getRequest()->willReturn($request);
        $request->query = $parameterBag;

        $event->isMasterRequest()->willReturn(true);

        $session->has('google_analytics_events')->willReturn(true);

        $request->getUri()->willReturn('baduri');

        $this->onKernelRequest($event);
    }
}
