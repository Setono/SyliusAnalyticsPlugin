<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\EventListener\LoginEventListener;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginEventListenerSpec extends ObjectBehavior
{
    function let(SessionInterface $session): void
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(LoginEventListener::class);
    }

    function it_add_login(
        SessionInterface $session
    ): void {
        $session->has('google_analytics_events')->willReturn(true);

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events', [['name' => 'Login']])->shouldBeCalled();

        $this->login();
    }

    function it_cannot_add_login(
        SessionInterface $session
    ): void {
        $session->has('google_analytics_events')->willReturn(false);

        $session->set('google_analytics_events', [])->shouldBeCalled();

        $session->get('google_analytics_events')->shouldBeCalled();

        $session->set('google_analytics_events', [['name' => 'Login']])->shouldBeCalled();

        $this->login();
    }
}
