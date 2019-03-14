<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\EventListener\CheckoutProgressEventListener;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CheckoutProgressEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CheckoutProgressEventListener::class);
    }

    function it_add_checkout_progress(
        SessionInterface $session
    ): void {
        $session->has('google_analytics_events')->willReturn(true);

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events', [['name' => 'CheckoutProgress']])->shouldBeCalled();

        $this->checkoutProgress();
    }

    function it_cannot_add_checkout_progress(
        SessionInterface $session
    ): void {
        $session->has('google_analytics_events')->willReturn(false);

        $session->set('google_analytics_events', [])->shouldBeCalled();

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events', [['name' => 'CheckoutProgress']])->shouldBeCalled();

        $this->checkoutProgress();
    }
}
