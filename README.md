# Sylius Analytics Plugin

[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]

This plugin adds Google Analytics tracking to your store. You can choose between a gtag and tag manager integration.
The gtag integration will output the traditional `gtag()` functions when tracking events, while the tag manager integration
will populate the `dataLayer` with event data.

## Installation

### Step 1: Download the plugin

This plugin uses the [TagBagBundle](https://github.com/Setono/TagBagBundle) to inject scripts onto your page.
Please install that bundle before installing this plugin.

```bash
composer require setono/sylius-analytics-plugin
```

### Step 2: Enable the plugin

Then, enable the plugin by adding it to the list of registered plugins/bundles
in `config/bundles.php` file of your project before (!) `SyliusGridBundle`:

```php
<?php
$bundles = [
    Setono\SyliusAnalyticsPlugin\SetonoSyliusAnalyticsPlugin::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
];
```

### Step 3: Configure plugin

```yaml
# config/packages/setono_sylius_analytics.yaml
imports:
    - { resource: "@SetonoSyliusAnalyticsPlugin/Resources/config/app/config.yaml" }

setono_google_analytics:
    gtag: ~
    # If you want to use tag manager instead of gtag, just comment the line above and remove the comment below
    # tag_manager: ~
```

### Step 4: Import routing

```yaml
# config/routes/setono_sylius_analytics.yaml
setono_sylius_analytics:
    resource: "@SetonoSyliusAnalyticsPlugin/Resources/config/routes.yaml"
```

### Step 5: Update your database schema

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

### Step 6: Create a property / container
Click the Google Analytics link in your backend and create a new property/container.

### Step 7: You're ready!
Your Sylius store will start tracking now!

The events that are available are:
- add_payment_info
- add_shipping_info
- add_to_cart
- begin_checkout
- purchase
- view_cart
- view_item_list
- view_item

and can be found in the [EventSubscriber folder](src/EventSubscriber).

Read on if you want to enrich events with more data.

## Enrich events with more data
When we want to track an event inside the Sylius application the `Setono\GoogleAnalyticsBundle\Event\ClientSideEvent` is fired.

That event holds the actual Google Analytics event, e.g. `Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\PurchaseEvent`.
This way, if you subscribe to the `ClientSideEvent`, you can manipulate everything about the event before it's rendered (and sent to Google).

But there are other ways to change the data enrichment. This can be done via resolvers. The plugin uses resolvers to
resolve a brand from a product, a category from a product etc. Read on to figure out how to use this functionality.

### Brand resolver
An item has a brand property (`item_brand`), but the plugin doesn't know how you have chosen to
add brand data in your application. Therefore, you need to implement your own `BrandResolver`. Here is an example:

```php
<?php
use Setono\SyliusAnalyticsPlugin\Resolver\Brand\BrandResolverInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class BrandResolver implements BrandResolverInterface
{
    public function resolveFromProduct(ProductInterface $product): ?string
    {
        return $product->getBrand(); // here we assume the getBrand() method will return a brand name or null (if not set)
    }

    public function resolveFromProductVariant(ProductVariantInterface $productVariant): ?string
    {
        return $this->resolveFromProduct($productVariant->getProduct());
    }
}
```

When you implement the [`BrandResolverInterface`](src/Resolver/Brand/BrandResolverInterface.php) and register your class as a service it will automatically
be tagged with `setono_sylius_analytics.brand_resolver` and used when tracking.

### Category resolver
An item has category properties (`item_category`, `item_category2`, etc.) and by default the plugin will resolve these
properties based on either a product's main taxon or (if no main taxon is set) the first taxon in the collection of taxons.

You can see the implementation inside the
[`Setono\SyliusAnalyticsPlugin\Resolver\Category\CategoryResolver`](src/Resolver/Category/CategoryResolver.php) class.

### Item resolver
The job of the item resolver is to return an `Setono\GoogleAnalyticsMeasurementProtocol\Request\Body\Event\Item\Item`
either from an order item or a product. This resolver also has a default implementation which you can see in the class
[`Setono\SyliusAnalyticsPlugin\Resolver\Item\ItemResolver`](src/Resolver/Item/ItemResolver.php).

### Items resolver
The items resolver must return an array of items given an order as input. The default implementation is found in the class
[`Setono\SyliusAnalyticsPlugin\Resolver\Items\ItemsResolver`](src/Resolver/Items/ItemsResolver.php).

### Variant resolver
An item has a variant property (`item_variant`). How you want to view your variant data inside the Analytics
user interface is up to you, however, the plugin provides two default resolvers, namely the
[`Setono\SyliusAnalyticsPlugin\Resolver\Variant\NameBasedVariantResolver`](src/Resolver/Variant/NameBasedVariantResolver.php)
and the [`Setono\SyliusAnalyticsPlugin\Resolver\Variant\OptionBasedVariantResolver`](src/Resolver/Variant/OptionBasedVariantResolver.php).

The option based version has the highest priority of the two and will therefore be tried first. It tries to create a
variant string based on the options on the variant (if any).

The name based version returns the `\Sylius\Component\Product\Model\ProductVariantInterface::getName()`.

To implement your own resolver just implement the `Setono\SyliusAnalyticsPlugin\Resolver\Variant\VariantResolverInterface`.
Here is an example:

```php
<?php
use Setono\SyliusAnalyticsPlugin\Resolver\Variant\VariantResolverInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class ProductNameBasedVariantResolver implements VariantResolverInterface
{
    public function resolve(ProductVariantInterface $productVariant): ?string
    {
        return $productVariant->getProduct()->getName();
    }
}
```

## Contribute
Ways you can contribute:
* Translate [messages](src/Resources/translations/messages.en.yaml) and [validators](src/Resources/translations/validators.en.yaml) into your mother tongue
* Create new event subscribers that handle Analytics events which are not implemented

Thank you!

[ico-version]: https://poser.pugx.org/setono/sylius-analytics-plugin/v/stable
[ico-license]: https://poser.pugx.org/setono/sylius-analytics-plugin/license
[ico-github-actions]: https://github.com/Setono/SyliusAnalyticsPlugin/workflows/build/badge.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-analytics-plugin
[link-github-actions]: https://github.com/Setono/SyliusAnalyticsPlugin/actions
