<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\DTO\Event\PurchaseEventData;
use Setono\GoogleAnalyticsMeasurementProtocol\DTO\ProductData;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilder;
use Setono\MainRequestTrait\MainRequestTrait;
use Setono\SyliusAnalyticsPlugin\Event\GenericDataEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webmozart\Assert\Assert;

final class PurchaseSubscriber extends PageviewSubscriber
{
    use MainRequestTrait;

    use FormatAmountTrait;

    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        HitBuilder $pageviewHitBuilder,
        EventDispatcherInterface $eventDispatcher,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($pageviewHitBuilder, $eventDispatcher);

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

        if (!$this->isMainRequest($requestEvent)) {
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

        $channel = $order->getChannel();
        Assert::notNull($channel);

        $items = [];
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            if (null === $product) {
                continue;
            }

            $productData = ProductData::createAsProductType((string) $product->getCode(), (string) $item->getProductName());
            $productData->quantity = $item->getQuantity();
            $productData->price = self::formatAmount($item->getFullDiscountedUnitPrice());

            $this->eventDispatcher->dispatch(new GenericDataEvent($productData, $item));

            $items[] = $productData;
        }

        $data = new PurchaseEventData(
            (string) $order->getNumber(),
            (string) $channel->getName() . ' (' . (string) $order->getLocaleCode() . ')',
            self::formatAmount($order->getTotal()),
            (string) $order->getCurrencyCode(),
            self::formatAmount($order->getTaxTotal()),
            self::formatAmount($order->getShippingTotal()),
            $items
        );

        $this->eventDispatcher->dispatch(new GenericDataEvent($data, $order));

        $data->applyTo($this->pageviewHitBuilder);
    }
}
