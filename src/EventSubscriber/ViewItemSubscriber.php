<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventSubscriber;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsBundle\Event\ClientSideEvent;
use Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\ViewItemEvent;
use Setono\SyliusAnalyticsPlugin\Resolver\Item\ItemResolverInterface;
use Setono\SyliusAnalyticsPlugin\Util\FormatAmountTrait;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class ViewItemSubscriber extends AbstractEventSubscriber
{
    use FormatAmountTrait;

    private EventDispatcherInterface $eventDispatcher;

    private ItemResolverInterface $itemResolver;

    public function __construct(EventDispatcherInterface $eventDispatcher, ItemResolverInterface $itemResolver)
    {
        parent::__construct();

        $this->eventDispatcher = $eventDispatcher;
        $this->itemResolver = $itemResolver;
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
                        ->addItem($item),
                ),
            );
        } catch (\Throwable $e) {
            $this->log(ViewItemEvent::NAME, $e);
        }
    }
}
