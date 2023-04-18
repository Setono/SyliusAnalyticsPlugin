<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\PurchaseEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Items\ItemsResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webmozart\Assert\Assert;

final class PurchaseSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    use FormatAmountTrait;

    private LoggerInterface $logger;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ItemsResolverInterface $itemsResolver,
    ) {
        $this->logger = new NullLogger();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'track',
        ];
    }

    public function track(RequestEvent $requestEvent): void
    {
        try {
            if (!$requestEvent->isMainRequest()) {
                return;
            }
            $request = $requestEvent->getRequest();

            $route = $request->attributes->get('_route');
            if ('sylius_shop_order_thank_you' !== $route) {
                return;
            }

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

            $this->eventDispatcher->dispatch(
                new ClientSideEvent(
                    PurchaseEvent::create((string) $order->getNumber())
                        ->setAffiliation(sprintf(
                            '%s (%s)',
                            (string) $channel->getName(),
                            (string) $order->getLocaleCode(),
                        ))
                        ->setValue(self::formatAmount($order->getTotal()))
                        ->setCurrency($order->getCurrencyCode())
                        ->setTax(self::formatAmount($order->getTaxTotal()))
                        ->setShipping(self::formatAmount($order->getShippingTotal()))
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
