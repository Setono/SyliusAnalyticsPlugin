<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin;

use Setono\CompositeCompilerPass\CompositeCompilerPass;
use Setono\SyliusAnalyticsPlugin\DependencyInjection\Compiler\OverrideDefaultContainerProviderPass;
use Setono\SyliusAnalyticsPlugin\DependencyInjection\Compiler\OverrideDefaultPropertyProviderPass;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

final class SetonoSyliusAnalyticsPlugin extends AbstractResourceBundle
{
    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideDefaultContainerProviderPass());
        $container->addCompilerPass(new OverrideDefaultPropertyProviderPass());

        $container->addCompilerPass(new CompositeCompilerPass(
            'setono_sylius_analytics.resolver.brand.composite',
            'setono_sylius_analytics.brand_resolver',
        ));

        $container->addCompilerPass(new CompositeCompilerPass(
            'setono_sylius_analytics.resolver.variant.composite',
            'setono_sylius_analytics.variant_resolver',
        ));
    }

    /**
     * Copied from @see \Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait to make the recipe work on symfony/recipes-contrib
     *
     * @psalm-suppress MixedInferredReturnType
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $extension = $this->createContainerExtension();

            if (null !== $extension) {
                if (!$extension instanceof ExtensionInterface) {
                    throw new \LogicException(sprintf('Extension %s must implement %s.', get_class($extension), ExtensionInterface::class));
                }

                // check naming convention for Sylius Plugins
                $basename = preg_replace('/Plugin$/', '', $this->getName());
                $expectedAlias = Container::underscore($basename);

                if ($expectedAlias !== $extension->getAlias()) {
                    throw new \LogicException(sprintf(
                        'Users will expect the alias of the default extension of a plugin to be the underscored version of the plugin name ("%s"). You can override "Bundle::getContainerExtension()" if you want to use "%s" or another alias.',
                        $expectedAlias,
                        $extension->getAlias()
                    ));
                }

                $this->extension = $extension;
            } else {
                $this->extension = false;
            }
        }

        /** @psalm-suppress MixedReturnStatement */
        return $this->extension ?: null;
    }

    protected function getContainerExtensionClass(): string
    {
        $basename = preg_replace('/Plugin$/', '', $this->getName());

        return $this->getNamespace() . '\\DependencyInjection\\' . $basename . 'Extension';
    }
}
