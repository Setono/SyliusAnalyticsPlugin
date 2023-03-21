# Sylius Analytics Plugin

[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]

This plugin adds Google Analytics tracking to your store. It injects the tags directly
and hence does not depend on third party tools like Google Tag Manager.

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
    # ...
    - { resource: "@SetonoSyliusAnalyticsPlugin/Resources/config/app/config.yaml" }
    # ...
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

### Step 6: Create a property
When you create a property in Google Analytics you receive a measurement id which looks something like G-12345678.

Now create a new property in your Sylius shop by navigating to `/admin/google-analytics/properties/new`.
Remember to enable the property and enable the channels you want to track. 

### Step 7: You're ready!
The events that are tracked are located in the [EventSubscriber folder](src/EventSubscriber).

## Contribute
Ways you can contribute:
* Translate [messages](src/Resources/translations/messages.en.yaml) and [validators](src/Resources/translations/validators.en.yaml) into your mother tongue
* Create Behat tests that verifies the scripts are outputted on the respective pages
* Create new event subscribers that handle Analytics events which are not implemented

Thank you!

[ico-version]: https://poser.pugx.org/setono/sylius-analytics-plugin/v/stable
[ico-license]: https://poser.pugx.org/setono/sylius-analytics-plugin/license
[ico-github-actions]: https://github.com/Setono/SyliusAnalyticsPlugin/workflows/build/badge.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-analytics-plugin
[link-github-actions]: https://github.com/Setono/SyliusAnalyticsPlugin/actions
