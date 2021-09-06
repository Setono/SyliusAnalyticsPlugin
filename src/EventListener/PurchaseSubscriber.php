<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilder;
use Setono\SyliusAnalyticsPlugin\Event\PurchaseEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class PurchaseSubscriber extends PageviewSubscriber
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        HitBuilder $pageviewHitBuilder,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FirewallMap $firewallMap,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($pageviewHitBuilder, $eventDispatcher, $requestStack, $firewallMap);

        $this->orderRepository = $orderRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'track',
        ];
    }

    public function track(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->getRequest();

        if (!$requestEvent->isMasterRequest() || !$this->isShopContext($request)) {
            return;
        }

        if (!$request->attributes->has('_route')) {
            return;
        }

        $route = $request->attributes->get('_route');
        if ('sylius_shop_order_thank_you' !== $route) {
            return;
        }

        /** @var mixed $orderId */
        $orderId = $request->getSession()->get('sylius_order_id');

        if (!is_scalar($orderId)) {
            return;
        }

        $order = $this->orderRepository->find($orderId);
        if (!$order instanceof OrderInterface) {
            return;
        }

        $event = PurchaseEvent::createFromOrder($order);
        $this->eventDispatcher->dispatch($event);

        $event->purchaseEventData->applyTo($this->pageviewHitBuilder);
    }
}
