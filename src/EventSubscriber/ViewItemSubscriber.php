<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\ViewItemEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\ItemResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

final class ViewItemSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    use FormatAmountTrait;

    private LoggerInterface $logger;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ItemResolverInterface $itemResolver,
    ) {
        $this->logger = new NullLogger();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.show' => 'track',
        ];
    }

    public function track(ResourceControllerEvent $resourceControllerEvent): void
    {
        try {
            /** @var ProductInterface|mixed $product */
            $product = $resourceControllerEvent->getSubject();
            Assert::isInstanceOf($product, ProductInterface::class);

            $item = $this->itemResolver->resolveFromProduct($product);

            $this->eventDispatcher->dispatch(
                new ClientSideEvent(
                    ViewItemEvent::create()
                        ->setValue($item->getPrice())
                        //->setCurrency('USD') todo set the currency. I don't think we can just use the currency context because the currency context is not used when calculating the price (which is also wrong)
                        ->addItem($this->itemResolver->resolveFromProduct($product)),
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
