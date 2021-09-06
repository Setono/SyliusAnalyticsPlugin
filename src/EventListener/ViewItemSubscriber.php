<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\GoogleAnalyticsMeasurementProtocol\DTO\ProductData;
use Setono\GoogleAnalyticsMeasurementProtocol\Hit\HitBuilder;
use Setono\SyliusAnalyticsPlugin\Event\GenericDataEvent;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;

final class ViewItemSubscriber extends PageviewSubscriber
{
    private ProductVariantResolverInterface $productVariantResolver;

    private ChannelContextInterface $channelContext;

    public function __construct(
        HitBuilder $pageviewHitBuilder,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FirewallMap $firewallMap,
        ProductVariantResolverInterface $productVariantResolver,
        ChannelContextInterface $channelContext
    ) {
        parent::__construct($pageviewHitBuilder, $eventDispatcher, $requestStack, $firewallMap);

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

        $data = ProductData::createAsProductType((string) $product->getCode(), (string) $product->getName());

        $channelPricings = $variant->getChannelPricingForChannel($channel);
        if (null !== $channelPricings) {
            $price = $channelPricings->getPrice();
            if (null !== $price) {
                $data->price = self::formatAmount($price);
            }
        }

        $this->eventDispatcher->dispatch(new GenericDataEvent($data, $product));

        $this->pageviewHitBuilder->setProductAction('detail');
        $data->applyTo($this->pageviewHitBuilder);
    }
}
