<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\SyliusAnalyticsPlugin\Builder\ItemBuilder;
use Setono\SyliusAnalyticsPlugin\Builder\PurchaseBuilder;
use Setono\SyliusAnalyticsPlugin\Context\PropertyContextInterface;
use Setono\SyliusAnalyticsPlugin\Event\BuilderEvent;
use Setono\TagBag\Tag\GtagEvent;
use Setono\TagBag\Tag\GtagEventInterface;
use Setono\TagBag\Tag\GtagLibrary;
use Setono\TagBag\TagBagInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class PurchaseSubscriber extends TagSubscriber
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        TagBagInterface $tagBag,
        PropertyContextInterface $propertyContext,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FirewallMap $firewallMap,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($tagBag, $propertyContext, $eventDispatcher, $requestStack, $firewallMap);

        $this->orderRepository = $orderRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'track',
        ];
    }

    public function track(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isMainRequest($event) || !$this->isShopContext($request)) {
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
        if (null === $order) {
            return;
        }

        if (!$order instanceof OrderInterface) {
            return;
        }

        $channel = $order->getChannel();
        if (null === $channel) {
            return;
        }

        if (!$this->hasProperties()) {
            return;
        }

        $builder = PurchaseBuilder::create()
            ->setTransactionId((string) $order->getNumber())
            ->setAffiliation((string) $channel->getName() . ' (' . (string) $order->getLocaleCode() . ')')
            ->setValue((float) $this->moneyFormatter->format($order->getTotal()))
            ->setCurrency((string) $order->getCurrencyCode())
            ->setTax((float) $this->moneyFormatter->format($order->getTaxTotal()))
            ->setShipping((float) $this->moneyFormatter->format($order->getShippingTotal()))
        ;

        foreach ($order->getItems() as $orderItem) {
            $variant = $orderItem->getVariant();
            if (null === $variant) {
                continue;
            }

            $itemBuilder = ItemBuilder::create()
                ->setId((string) $variant->getCode())
                ->setName((string) $orderItem->getVariantName())
                ->setQuantity($orderItem->getQuantity())
                ->setPrice((float) $this->moneyFormatter->format($orderItem->getDiscountedUnitPrice()))
            ;

            $this->eventDispatcher->dispatch(new BuilderEvent($itemBuilder, $orderItem));

            $builder->addItem($itemBuilder);
        }

        $this->eventDispatcher->dispatch(new BuilderEvent($builder, $order));

        $this->tagBag->addTag(
            (new GtagEvent(GtagEventInterface::EVENT_PURCHASE, $builder->getData()))
                ->addDependency(GtagLibrary::NAME)
        );
    }
}
