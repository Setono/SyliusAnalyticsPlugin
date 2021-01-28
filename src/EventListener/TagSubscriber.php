<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use function count;
use Psr\EventDispatcher\EventDispatcherInterface;
use Setono\SyliusAnalyticsPlugin\Context\PropertyContextInterface;
use Setono\SyliusAnalyticsPlugin\Formatter\MoneyFormatter;
use Setono\TagBag\TagBagInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class TagSubscriber implements EventSubscriberInterface
{
    protected TagBagInterface $tagBag;

    private PropertyContextInterface $propertyContext;

    protected EventDispatcherInterface $eventDispatcher;

    protected MoneyFormatter $moneyFormatter;

    private RequestStack $requestStack;

    private FirewallMap $firewallMap;

    public function __construct(
        TagBagInterface $tagBag,
        PropertyContextInterface $propertyContext,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        $this->tagBag = $tagBag;
        $this->propertyContext = $propertyContext;
        $this->eventDispatcher = $eventDispatcher;
        $this->moneyFormatter = new MoneyFormatter();
        $this->requestStack = $requestStack;
        $this->firewallMap = $firewallMap;
    }

    protected function hasProperties(): bool
    {
        return count($this->propertyContext->getProperties()) > 0;
    }

    protected function getProperties(): array
    {
        return $this->propertyContext->getProperties();
    }

    protected function isShopContext(Request $request = null): bool
    {
        if (null === $request) {
            $request = $this->requestStack->getCurrentRequest();
            if (null === $request) {
                return true;
            }
        }

        $firewallConfig = $this->firewallMap->getFirewallConfig($request);
        if (null === $firewallConfig) {
            return true;
        }

        return $firewallConfig->getName() === 'shop';
    }
}
