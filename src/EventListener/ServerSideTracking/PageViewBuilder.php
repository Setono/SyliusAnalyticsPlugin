<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener\ServerSideTracking;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Setono\SyliusAnalyticsPlugin\Context\PropertyContextInterface;
use Setono\SyliusAnalyticsPlugin\Factory\HitFactoryInterface;
use Setono\SyliusAnalyticsPlugin\Model\Hit;
use Setono\SyliusAnalyticsPlugin\Resolver\ClientIdResolverInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

final class PageViewBuilder implements EventSubscriberInterface
{
    /** @var Analytics[] */
    private $hits = [];

    /** @var HitFactoryInterface */
    private $hitFactory;

    /** @var PropertyContextInterface */
    private $propertyContext;

    /** @var FirewallMap */
    private $firewallMap;

    /** @var ClientIdResolverInterface */
    private $clientIdResolver;

    /** @var ManagerRegistry */
    private $managerRegistry;

    public function __construct(
        HitFactoryInterface $hitFactory,
        PropertyContextInterface $propertyContext,
        FirewallMap $firewallMap,
        ClientIdResolverInterface $clientIdResolver,
        ManagerRegistry $managerRegistry
    ) {
        $this->hitFactory = $hitFactory;
        $this->propertyContext = $propertyContext;
        $this->firewallMap = $firewallMap;
        $this->clientIdResolver = $clientIdResolver;
        $this->managerRegistry = $managerRegistry;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            /*
             * We set the priority to 1 so that people doesn't have to worry about
             * if it was initialized before they are populating the builder with data
             */
            KernelEvents::REQUEST => ['init', 1],

            /**
             * We set the priority to -1 again so that people doesn't have to worry about
             * if the data was already persisted before they populate the builder
             */
            KernelEvents::TERMINATE => ['persist', -1],
        ];
    }

    public function init(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if($request->isXmlHttpRequest()) {
            return;
        }

        if (!$this->isShopContext($request)) {
            return;
        }

        $properties = $this->propertyContext->getProperties();
        foreach ($properties as $property) {
            $analytics = new Analytics(true);
            $analytics
                ->setDebug(true)
                ->setProtocolVersion('1')
                ->setTrackingId($property->getTrackingId())
                ->setClientId($this->clientIdResolver->resolve($request))
                ->setAnonymizeIp(true)
                ->setIpOverride($request->getClientIp())
                ->setUserAgentOverride($request->headers->get('user-agent'))
                ->setHitType('pageview')
                ->setDocumentLocationUrl($request->getUri())
                // todo add more parameters, i.e. utm_source etc
            ;

            if ($request->headers->has('referer')) {
                $analytics->setDocumentReferrer($request->headers->get('referer'));
            }

            $this->hits[] = $analytics;
        }
    }

    public function persist(TerminateEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!$this->isShopContext($request)) {
            return;
        }

        $manager = $this->getManager();

        foreach ($this->hits as $hit) {
            $obj = $this->hitFactory->createWithData($hit->getUrl(true), $request->getSession()->getId());
            $manager->persist($obj);
        }

        $manager->flush();
    }

    public function call(callable $callable): void
    {
        foreach ($this->hits as $hit) {
            $callable($hit);
        }
    }

    private function isShopContext(Request $request): bool
    {
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);
        if (null === $firewallConfig) {
            return true;
        }

        return $firewallConfig->getName() === 'shop';
    }

    private function getManager(): EntityManagerInterface
    {
        /** @var EntityManagerInterface $manager */
        $manager = $this->managerRegistry->getManagerForClass(Hit::class);

        return $manager;
    }
}
