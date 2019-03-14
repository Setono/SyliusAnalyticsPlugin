<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\EventListener\RemoveFromCartEventListener;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RemoveFromCartEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RemoveFromCartEventListener::class);
    }

    function it_add_remove_from_cart_eventn(
        SessionInterface $session
    ): void {
        $session->has('google_analytics_events')->willReturn(true);

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events', [['name' => 'RemoveFromCart']])->shouldBeCalled();

        $this->removeFromCart();
    }

    function it_cannot_add_remove_from_cart_event(
        SessionInterface $session
    ): void {
        $session->has('google_analytics_events')->willReturn(false);

        $session->set('google_analytics_events', [])->shouldBeCalled();

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events', [['name' => 'RemoveFromCart']])->shouldBeCalled();

        $this->removeFromCart();
    }
}
