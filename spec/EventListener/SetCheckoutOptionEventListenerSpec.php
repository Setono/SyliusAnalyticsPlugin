<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\EventListener\SetCheckoutOptionEventListener;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SetCheckoutOptionEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SetCheckoutOptionEventListener::class);
    }

    function it_add_set_checkout_option_event(
        ResourceControllerEvent $resourceControllerEvent,
        SessionInterface $session,
        OrderInterface $order
    ): void {
        $session->has('google_analytics_events')->willReturn(true);

        $session->get('google_analytics_events')->shouldBeCalled();

        $resourceControllerEvent->getSubject()->willReturn($order);

        $order->getCheckoutState()->willReturn('payment');
        $order->getTotal()->willReturn(100);

        $session->set('google_analytics_events', [[
            'name' => 'SetCheckoutOption',
            'options' => [
                'checkout_state' => 'payment',
                'value' => 1,
            ],
        ]])->shouldBeCalled();

        $this->setCheckoutOption($resourceControllerEvent);
    }

    function it_cannot_add_set_checkout_option_event(
        ResourceControllerEvent $resourceControllerEvent,
        SessionInterface $session,
        OrderInterface $order
    ): void {
        $session->has('google_analytics_events')->willReturn(false);

        $session->set('google_analytics_events', [])->shouldBeCalled();

        $session->get('google_analytics_events')->shouldBeCalled();

        $resourceControllerEvent->getSubject()->willReturn($order);

        $order->getCheckoutState()->willReturn('payment');
        $order->getTotal()->willReturn(100);

        $session->set('google_analytics_events', [[
            'name' => 'SetCheckoutOption',
            'options' => [
                'checkout_state' => 'payment',
                'value' => 1,
            ],
        ]])->shouldBeCalled();

        $this->setCheckoutOption($resourceControllerEvent);
    }
}
