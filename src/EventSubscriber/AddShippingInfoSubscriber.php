<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\AddShippingInfoEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Items\ItemsResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class AddShippingInfoSubscriber extends AbstractEventSubscriber
{
    use FormatAmountTrait;

    private EventDispatcherInterface $eventDispatcher;

    private ItemsResolverInterface $itemsResolver;

    public function __construct(EventDispatcherInterface $eventDispatcher, ItemsResolverInterface $itemsResolver)
    {
        parent::__construct();

        $this->eventDispatcher = $eventDispatcher;
        $this->itemsResolver = $itemsResolver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_select_shipping' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        try {
            /** @var OrderInterface|mixed $order */
            $order = $resourceControllerEvent->getSubject();
            Assert::isInstanceOf($order, OrderInterface::class);

            $shippingMethodCode = null;
            foreach ($order->getShipments() as $shipment) {
                $shippingMethod = $shipment->getMethod();
                if (null === $shippingMethod) {
                    continue;
                }

                $shippingMethodCode = $shippingMethod->getCode();
            }
            Assert::notNull($shippingMethodCode);

            $this->eventDispatcher->dispatch(
                new ClientSideEvent(
                    AddShippingInfoEvent::create()
                        ->setValue(self::formatAmount($order->getTotal()))
                        ->setCurrency($order->getCurrencyCode())
                        ->setShippingTier($shippingMethodCode)
                        ->setItems($this->itemsResolver->resolveFromOrder($order)),
                ),
            );
        } catch (\Throwable $e) {
            $this->log(AddShippingInfoEvent::NAME, $e);
        }
    }
}
