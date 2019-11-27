# Sylius Analytics Plugin

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Quality Score][ico-code-quality]][link-code-quality]

This plugin adds Google Analytics tracking to your store. It injects the tags directly
and hence does not depend on third party tools like Google Tag Manager.

## Installation

### Step 1: Download the plugin

This plugin uses the [TagBagBundle](https://github.com/Setono/TagBagBundle) to inject scripts onto your page.

Open a command console, enter your project directory and execute the following command to download the latest stable version of this plugin:

```bash
$ composer require setono/sylius-analytics-plugin

# Omit this line if you want to override layout.html.twig as described at https://github.com/Setono/TagBagBundle#usage
$ composer require setono/sylius-tag-bag-plugin

```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.


### Step 2: Enable the plugin

Then, enable the plugin by adding it to the list of registered plugins/bundles
in `config/bundles.php` file of your project before (!) `SyliusGridBundle`:

```php
<?php
$bundles = [
    Setono\TagBagBundle\SetonoTagBagBundle::class => ['all' => true],
    
    // Omit this line if you didn't install the SyliusTagBagPlugin in step 1
    Setono\SyliusTagBagPlugin\SetonoSyliusTagBagPlugin::class => ['all' => true],
    
    Setono\SyliusAnalyticsPlugin\SetonoSyliusAnalyticsPlugin::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
];
```

### Step 3: Configure plugin

```yaml
# config/packages/_sylius.yaml
imports:
    # ...
    - { resource: "@SetonoSyliusAnalyticsPlugin/Resources/config/app/config.yaml" }
    # ...
```

### Step 4: Import routing

```yaml
# config/routes/setono_sylius_analytics.yaml
setono_sylius_analytics:
    resource: "@SetonoSyliusAnalyticsPlugin/Resources/config/routing.yaml"
```

### Step 5: Update your database schema

```bash
$ php bin/console doctrine:migrations:diff
$ php bin/console doctrine:migrations:migrate
```

### Step 6: Create a property
When you create a property in Google Analytics you receive a tracking id which looks something like UA-12345678-1.

Now create a new property in your Sylius shop by navigating to `/admin/properties/new`.
Remember to enable the property and enable the channels you want to track. 

### Step 7: You're ready!
The events that are tracked are located in the [EventListener folder](src/EventListener).
To make your tracking even better you should create event listeners listening to the 
builder events fired in i.e. the [PurchaseSubscriber](src/EventListener/PurchaseSubscriber.php).

Let's say you use the [Brand plugin](https://github.com/loevgaard/SyliusBrandPlugin) and want to enrich the items
tracked with the brand of the product. That would look like this:

```php
<?php

declare(strict_types=1);

namespace App\EventListener;

use Loevgaard\SyliusBrandPlugin\Model\BrandAwareInterface;
use Setono\SyliusAnalyticsPlugin\Builder\ItemBuilder;
use Setono\SyliusAnalyticsPlugin\Event\BuilderEvent;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


final class AddBrandSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BuilderEvent::class => [
                'addBrand',
            ],
        ];
    }

    public function addBrand(BuilderEvent $event): void
    {
        $builder = $event->getBuilder();
        if(!$builder instanceof ItemBuilder) {
            return;
        }
        
        $subject = $event->getSubject();
        
        if(!$subject instanceof OrderItemInterface) {
            return;
        }
        
        $product = $subject->getProduct();
        if(!$product instanceof BrandAwareInterface) {
            return;
        }
        
        $builder->setBrand($product->getBrand()->getName());
    }
}

```

## Contribute
Ways you can contribute:
* Translate [messages](src/Resources/translations/messages.en.yaml) and [validators](src/Resources/translations/validators.en.yaml) to your mother tongue
* Create Behat tests that verifies the scripts are outputted on the respective pages
* Create new event subscribers that handle Analytics events which are not implemented

Thank you!

[ico-version]: https://poser.pugx.org/setono/sylius-analytics-plugin/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/sylius-analytics-plugin/v/unstable
[ico-license]: https://poser.pugx.org/setono/sylius-analytics-plugin/license
[ico-github-actions]: https://github.com/Setono/SyliusAnalyticsPlugin/workflows/build/badge.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusAnalyticsPlugin.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-analytics-plugin
[link-github-actions]: https://github.com/Setono/SyliusAnalyticsPlugin/actions
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusAnalyticsPlugin
