<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Message\Handler;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\ServerSideEvent;
use Setono\GoogleAnalyticsBundle\ValueObject\Property;
use Setono\SyliusAnalyticsPlugin\Message\Command\SendPurchaseEvent;
use Setono\SyliusAnalyticsPlugin\Repository\PropertyRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderCheckoutStates;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Webmozart\Assert\Assert;

/**
 * NOT final to make it easy to decorate and extend this handler
 *
 * todo this only works with gtag enabled and not tag manager
 */
class SendPurchaseEventHandler
{
    private OrderRepositoryInterface $orderRepository;

    private PropertyRepositoryInterface $propertyRepository;

    private EventDispatcherInterface $eventDispatcher;

    private bool $gtagEnabled;

    public function __construct(OrderRepositoryInterface $orderRepository, PropertyRepositoryInterface $propertyRepository, EventDispatcherInterface $eventDispatcher, bool $gtagEnabled)
    {
        $this->orderRepository = $orderRepository;
        $this->propertyRepository = $propertyRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->gtagEnabled = $gtagEnabled;
    }

    public function __invoke(SendPurchaseEvent $message): void
    {
        if (false === $this->gtagEnabled) {
            return;
        }

        $order = $this->orderRepository->find($message->orderId);
        if (!$order instanceof OrderInterface) {
            throw new UnrecoverableMessageHandlingException(sprintf(
                'The order with id %d does not exist or is not an instance of %s',
                $message->orderId,
                OrderInterface::class
            ));
        }

        if (!$this->isEligible($order)) {
            throw new \RuntimeException(sprintf('The order with id %d is not eligible for a Google Analytics purchase event to be sent', $message->orderId));
        }

        $channel = $order->getChannel();
        Assert::notNull($channel);

        $propertyEntities = $this->propertyRepository->findEnabledByChannel($channel);
        if ([] === $propertyEntities) {
            throw new UnrecoverableMessageHandlingException('You have not defined any Google Analytics properties');
        }

        $properties = [];
        foreach ($propertyEntities as $propertyEntity) {
            $measurementId = $propertyEntity->getMeasurementId();
            $apiSecret = $propertyEntity->getApiSecret();

            if (null === $measurementId || null === $apiSecret) {
                continue;
            }

            $properties[] = new Property($measurementId, $apiSecret);
        }

        $this->eventDispatcher->dispatch(new ServerSideEvent($message->event, $message->clientId, $properties));
    }

    protected function isEligible(OrderInterface $order): bool
    {
        return in_array($order->getPaymentState(), [OrderPaymentStates::STATE_AUTHORIZED, OrderPaymentStates::STATE_PAID, OrderPaymentStates::STATE_AWAITING_PAYMENT], true) && $order->getCheckoutState() === OrderCheckoutStates::STATE_COMPLETED;
    }
}
