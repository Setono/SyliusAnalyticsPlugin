<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Setono\GoogleAnalyticsBundle\Context\ClientIdContextInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\PurchaseEvent;
use Setono\SyliusAnalyticsPlugin\Message\Command\SendPurchaseEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Items\ItemsResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Webmozart\Assert\Assert;

final class DispatchSendPurchaseRequestSubscriber implements EventSubscriberInterface
{
    use FormatAmountTrait;

    private ClientIdContextInterface $clientIdContext;

    private MessageBusInterface $commandBus;

    private ItemsResolverInterface $itemsResolver;

    private int $delay;

    public function __construct(ClientIdContextInterface $clientIdContext, MessageBusInterface $commandBus, ItemsResolverInterface $itemsResolver, int $delay = 43_200)
    {
        $this->clientIdContext = $clientIdContext;
        $this->commandBus = $commandBus;
        $this->itemsResolver = $itemsResolver;
        $this->delay = $delay;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_complete' => 'dispatch',
        ];
    }

    public function dispatch(ResourceControllerEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $clientId = $this->clientIdContext->getClientId();
        if (null === $clientId) {
            return;
        }

        $channel = $order->getChannel();
        Assert::notNull($channel);

        $this->commandBus->dispatch(new Envelope(
            new SendPurchaseEvent(
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
                (int) $order->getId(),
                $clientId
            ),
            [DelayStamp::delayFor(new \DateInterval(sprintf('PT%dS', $this->delay)))]
        ));
    }
}
