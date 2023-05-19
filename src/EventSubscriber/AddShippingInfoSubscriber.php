<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\AddShippingInfoEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Items\ItemsResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

final class AddShippingInfoSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    use FormatAmountTrait;

    private LoggerInterface $logger;

    private EventDispatcherInterface $eventDispatcher;

    private ItemsResolverInterface $itemsResolver;

    public function __construct(EventDispatcherInterface $eventDispatcher, ItemsResolverInterface $itemsResolver)
    {
        $this->logger = new NullLogger();
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
            $this->logger->error($e->getMessage());
        }
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
