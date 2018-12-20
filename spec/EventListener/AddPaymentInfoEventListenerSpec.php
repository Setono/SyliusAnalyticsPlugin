<?php

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\EventListener\AddPaymentInfoEventListener;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class AddPaymentInfoEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AddPaymentInfoEventListener::class);
    }

    function it_add_payment_info(

        SessionInterface $session
    ): void
    {
        $session->has('google_analytics_events')->willReturn(true);

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events' ,[['name' => 'AddPaymentInfo']])->shouldBeCalled();

        $this->addPaymentInfo();
    }

    function it_cannot_add_payment_info(
        SessionInterface $session
    ): void
    {
        $session->has('google_analytics_events')->willReturn(false);

        $session->set('google_analytics_events', [])->shouldBeCalled();

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events' ,[['name' => 'AddPaymentInfo']])->shouldBeCalled();

        $this->addPaymentInfo();
    }
}
