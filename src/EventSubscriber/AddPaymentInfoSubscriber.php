<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\AddPaymentInfoEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Items\ItemsResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

final class AddPaymentInfoSubscriber implements EventSubscriberInterface, LoggerAwareInterface
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
            'sylius.order.post_payment' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        try {
            /** @var OrderInterface|mixed $order */
            $order = $resourceControllerEvent->getSubject();
            Assert::isInstanceOf($order, OrderInterface::class);

            $lastPayment = $order->getLastPayment();
            Assert::notNull($lastPayment);

            $paymentMethod = $lastPayment->getMethod();
            Assert::notNull($paymentMethod);

            $paymentMethodCode = $paymentMethod->getCode();
            Assert::notNull($paymentMethodCode);

            $this->eventDispatcher->dispatch(
                new ClientSideEvent(
                    AddPaymentInfoEvent::create()
                        ->setValue(self::formatAmount($order->getTotal()))
                        ->setCurrency($order->getCurrencyCode())
                        ->setPaymentType($paymentMethodCode)
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
