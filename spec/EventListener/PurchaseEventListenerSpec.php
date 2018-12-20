<?php

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\EventListener\PurchaseEventListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PurchaseEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(PurchaseEventListener::class);
    }

    function it_purchase(
        ResourceControllerEvent $resourceControllerEvent,
        SessionInterface $session,
        OrderInterface $order
    ): void {
        $session->has('google_analytics_events')->willReturn(true);

        $session->get('google_analytics_events')->shouldBeCalled();

        $resourceControllerEvent->getSubject()->willReturn($order);

        $order->getId()->willReturn('1');
        $order->getTotal()->willReturn(100);
        $order->getCurrencyCode()->willReturn('USD');

        $session->set('google_analytics_events', [[
            'name' => 'Purchase',
            'options' => [
                'transaction_id' => 1,
                'value' => 1,
                'currency' => 'USD',
            ]
        ]])->shouldBeCalled();


        $this->purchase($resourceControllerEvent);
    }

    function it_cannot_purchase(
        ResourceControllerEvent $resourceControllerEvent,
        SessionInterface $session,
        OrderInterface $order
    ): void {
        $session->has('google_analytics_events')->willReturn(false);

        $session->set('google_analytics_events', [])->shouldBeCalled();

        $session->get('google_analytics_events')->shouldBeCalled();

        $resourceControllerEvent->getSubject()->willReturn($order);

        $order->getId()->willReturn('1');
        $order->getTotal()->willReturn(100);
        $order->getCurrencyCode()->willReturn('USD');

        $session->set('google_analytics_events', [[
            'name' => 'Purchase',
            'options' => [
                'transaction_id' => 1,
                'value' => 1,
                'currency' => 'USD',
            ]
        ]])->shouldBeCalled();

        $this->purchase($resourceControllerEvent);
    }

}
