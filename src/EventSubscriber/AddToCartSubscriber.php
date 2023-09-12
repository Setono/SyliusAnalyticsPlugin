<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsEvents\Event\AddToCartEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Item\ItemResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Webmozart\Assert\Assert;

final class AddToCartSubscriber extends AbstractEventSubscriber
{
    use FormatAmountTrait;

    private EventDispatcherInterface $eventDispatcher;

    private CartContextInterface $cartContext;

    private ItemResolverInterface $itemResolver;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        CartContextInterface $cartContext,
        ItemResolverInterface $itemResolver
    ) {
        parent::__construct();

        $this->eventDispatcher = $eventDispatcher;
        $this->cartContext = $cartContext;
        $this->itemResolver = $itemResolver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        try {
            /** @var OrderItemInterface|mixed $orderItem */
            $orderItem = $resourceControllerEvent->getSubject();
            Assert::isInstanceOf($orderItem, OrderItemInterface::class);

            /**
             * Notice we are getting the order from the cart context and not the order item because of this issue:
             * https://github.com/Sylius/Sylius/issues/9407
             *
             * That issue was fixed in Sylius 1.12, but we can't require the order bundle because Sylius doesn't handle
             * GitHub repository subtree splits the correct way with regard to packagist therefore we are keeping this
             */
            $order = $this->cartContext->getCart();
            if (!$order instanceof OrderInterface) {
                return;
            }

            $this->eventDispatcher->dispatch(
                new ClientSideEvent(
                    AddToCartEvent::create()
                        ->setCurrency($order->getCurrencyCode())
                        ->addItem($this->itemResolver->resolveFromOrderItem($orderItem)),
                ),
            );
        } catch (\Throwable $e) {
            $this->log(AddToCartEvent::getName(), $e);
        }
    }
}
