<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Resolver;

use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

final class ClientIdResolver implements ClientIdResolverInterface
{
    public const CLIENT_ID_SESSION_KEY = 'setono_sylius_analytics_client_id';

    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function resolve(): string
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            throw new RuntimeException('Cannot resolve a client id if there is not master request');
        }

        $session = $request->getSession();

        if ($session->has(self::CLIENT_ID_SESSION_KEY)) {
            return $session->get(self::CLIENT_ID_SESSION_KEY);
        }

        $clientId = (string) Uuid::v4();

        $session->set(self::CLIENT_ID_SESSION_KEY, $clientId);

        return $clientId;
    }
}
