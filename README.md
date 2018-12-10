# Sylius Analytics Plugin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

Use Google Analytics to track activity in Sylius.

## Installation


### Step 1: Download the plugin

Open a command console, enter your project directory and execute the following command to download the latest stable version of this plugin:

```bash
$ composer require setono/sylius-analytics-plugin
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.


### Step 2: Enable the plugin

Then, enable the plugin by adding it to the list of registered plugins/bundles
in the `app/AppKernel.php` file of your project:

```php
$bundles = [
   new \Setono\SyliusAnalyticsPlugin\SetonoSyliusAnalyticsPlugin(),
];
```

### Step 3: Import routing

````yaml
setono_analytics_plugin:
    resource: "@SetonoSyliusAnalyticsPlugin/Resources/config/routing.yml"

````

[ico-version]: https://img.shields.io/packagist/v/setono/sylius-pickup-point-plugin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Setono/SyliusPickupPointPlugin/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusPickupPointPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/sylius-pickup-point-plugin
[link-travis]: https://travis-ci.org/Setono/SyliusPickupPointPlugin
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusPickupPointPlugin
