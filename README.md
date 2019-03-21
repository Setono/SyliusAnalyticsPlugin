# Sylius Analytics Plugin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

Use Google Analytics to track activity in Sylius.

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

```yaml
# config/routes/setono_sylius_analytics.yaml
setono_analytics_plugin:
    resource: "@SetonoSyliusAnalyticsPlugin/Resources/config/routing.yaml"
```

[ico-version]: https://img.shields.io/packagist/v/setono/sylius-analytics-plugin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://travis-ci.com/Setono/SyliusAnalyticsPlugin.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusAnalyticsPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/sylius-analytics-plugin
[link-travis]: https://travis-ci.com/Setono/SyliusAnalyticsPlugin
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusAnalyticsPlugin
