<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Event;

use Setono\GoogleAnalyticsMeasurementProtocol\DTO\ProductData;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class ViewItemEvent extends AnalyticsEvent
{
    public ProductData $productData;

    public ProductInterface $product;

    public function __construct(ProductData $productData, ProductInterface $product)
    {
        $this->productData = $productData;
        $this->product = $product;
    }

    public static function createFromProduct(
        ProductInterface $product,
        ProductVariantInterface $productVariant,
        ChannelInterface $channel,
        string $type = ProductData::TYPE_PRODUCT
    ): self {
        $data = new ProductData((string) $product->getCode(), (string) $product->getName(), $type);

        $channelPricings = $productVariant->getChannelPricingForChannel($channel);
        if (null !== $channelPricings) {
            $data->price = self::formatAmount($channelPricings->getPrice());
        }

        return new self($data, $product);
    }
}
