<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\DTO\Event\AddToCartEventData;
use Setono\GoogleAnalyticsMeasurementProtocol\DTO\ProductData;
use Setono\GoogleAnalyticsServerSideTrackingBundle\Factory\HitBuilderFactoryInterface;
use Setono\SyliusAnalyticsPlugin\Event\GenericDataEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

final class AddToCartSubscriber extends UpdateCartSubscriber
{
    use FormatAmountTrait;

    private CartContextInterface $cartContext;

    public function __construct(
        HitBuilderFactoryInterface $hitBuilderFactory,
        EventDispatcherInterface $eventDispatcher,
        CartContextInterface $cartContext
    ) {
        parent::__construct($hitBuilderFactory, $eventDispatcher);

        $this->cartContext = $cartContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => 'track',
        ];
    }

    public function _track(OrderItemInterface $orderItem): void
    {
        $order = $this->cartContext->getCart();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $product = $orderItem->getProduct();
        if (null === $product) {
            return;
        }

        $variant = $orderItem->getVariant();
        if (null === $variant) {
            return;
        }

        $variantStr = implode(
            '-',
            $variant
                ->getOptionValues()
                ->map(static fn (ProductOptionValueInterface $productOptionValue) => $productOptionValue->getValue())
                ->toArray()
        );

        $productData = ProductData::createAsProductType((string) $product->getCode(), (string) $product->getName());
        $productData->price = self::formatAmount($orderItem->getFullDiscountedUnitPrice());
        $productData->quantity = $orderItem->getQuantity();

        if ('' !== $variantStr) {
            $productData->variant = $variantStr;
        }

        $this->eventDispatcher->dispatch(new GenericDataEvent($productData, $orderItem));

        $data = new AddToCartEventData();
        $data->currency = $order->getCurrencyCode();
        $data->products[] = $productData;

        $this->eventDispatcher->dispatch(new GenericDataEvent($data, $orderItem));

        $hitBuilder = $this->hitBuilderFactory->createEventHitBuilder();
        $data->applyTo($hitBuilder);
    }
}
