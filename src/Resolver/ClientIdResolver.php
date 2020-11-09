<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver;

use DateInterval;
use Safe\DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Uid\Uuid;

final class ClientIdResolver implements ClientIdResolverInterface, EventSubscriberInterface
{
    public const CLIENT_ID_COOKIE_KEY = 'setono_sylius_analytics_client_id';

    /** @var string */
    private $clientId;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'save',
        ];
    }

    public function resolve(Request $request): string
    {
        if (null === $this->clientId) {
            $this->clientId = $request->cookies->has(self::CLIENT_ID_COOKIE_KEY) ? $request->cookies->get(self::CLIENT_ID_COOKIE_KEY, '') : (string) Uuid::v4();
        }

        return $this->clientId;
    }

    public function save(ResponseEvent $event): void
    {
        $expire = (new DateTime())->add(new DateInterval('P2Y'));
        $event->getResponse()->headers->setCookie(Cookie::create(self::CLIENT_ID_COOKIE_KEY, $this->clientId, $expire));
    }
}
