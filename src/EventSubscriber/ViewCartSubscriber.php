<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\ViewCartEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Items\ItemsResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webmozart\Assert\Assert;

final class ViewCartSubscriber extends AbstractEventSubscriber
{
    use FormatAmountTrait;

    private EventDispatcherInterface $eventDispatcher;

    private CartContextInterface $cartContext;

    private ItemsResolverInterface $itemsResolver;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        CartContextInterface $cartContext,
        ItemsResolverInterface $itemsResolver
    ) {
        parent::__construct();

        $this->eventDispatcher = $eventDispatcher;
        $this->cartContext = $cartContext;
        $this->itemsResolver = $itemsResolver;
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
            $this->log(ViewCartEvent::NAME, $e);
        }
    }
}
