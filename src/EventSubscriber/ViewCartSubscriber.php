<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\ViewCartEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\ItemsResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webmozart\Assert\Assert;

final class ViewCartSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    use FormatAmountTrait;

    private LoggerInterface $logger;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CartContextInterface $cartContext,
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
            if ('sylius_shop_cart_summary' !== $route) {
                return;
            }

            /** @var OrderInterface $order */
            $order = $this->cartContext->getCart();
            Assert::isInstanceOf($order, OrderInterface::class);

            $this->eventDispatcher->dispatch(
                new ClientSideEvent(
                    ViewCartEvent::create()
                        ->setValue(self::formatAmount($order->getTotal()))
                        ->setCurrency($order->getCurrencyCode())
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
