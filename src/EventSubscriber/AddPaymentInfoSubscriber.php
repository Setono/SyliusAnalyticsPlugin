<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsEvents\Event\AddPaymentInfoEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Items\ItemsResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class AddPaymentInfoSubscriber extends AbstractEventSubscriber
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
            $this->log(AddPaymentInfoEvent::getName(), $e);
        }
    }
}
