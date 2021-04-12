<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilder;
use Setono\SyliusAnalyticsPlugin\Event\ViewItemEvent;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;

final class ViewItemSubscriber extends AnalyticsEventSubscriber
{
    private ProductVariantResolverInterface $productVariantResolver;

    private ChannelContextInterface $channelContext;

    public function __construct(
        HitBuilder $hitBuilder,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FirewallMap $firewallMap,
        ProductVariantResolverInterface $productVariantResolver,
        ChannelContextInterface $channelContext
    ) {
        parent::__construct($hitBuilder, $eventDispatcher, $requestStack, $firewallMap);

        $this->productVariantResolver = $productVariantResolver;
        $this->channelContext = $channelContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.show' => 'view',
        ];
    }

    public function view(ResourceControllerEvent $resourceControllerEvent): void
    {
        $product = $resourceControllerEvent->getSubject();

        if (!$product instanceof ProductInterface || !$this->isShopContext()) {
            return;
        }

        $variant = $this->productVariantResolver->getVariant($product);
        if (!$variant instanceof ProductVariantInterface) {
            return;
        }

        $channel = $this->channelContext->getChannel();
        if (!$channel instanceof ChannelInterface) {
            return;
        }

        $event = ViewItemEvent::createFromProduct($product, $variant, $channel);

        $this->eventDispatcher->dispatch($event);

        $this->hitBuilder->setProductAction('detail');
        $event->productData->applyTo($this->hitBuilder);
    }
}
