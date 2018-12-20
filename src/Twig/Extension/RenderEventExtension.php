<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Twig\Extension;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;

final class RenderEventExtension extends AbstractExtension
{
    /** @var SessionInterface */
    private $session;

    /** @var EngineInterface */
    private $templatingEngine;

    public function __construct(SessionInterface $session, EngineInterface $templatingEngine)
    {
        $this->session = $session;
        $this->templatingEngine = $templatingEngine;
    }

    public function getFunctions(): array
    {
        return[
          new \Twig_Function('render_google_analytics_events', [$this, 'renderGoogleAnalyticsEvents'], ['is_safe' => ['html']]),
        ];
    }

    public function renderGoogleAnalyticsEvents(): string
    {
        $googleAnalyticsEvents = $this->session->get('google_analytics_events');

        $this->session->remove('google_analytics_events');

        return $this->templatingEngine->render('SetonoSyliusAnalyticsPlugin::google_analytics_events.html.twig', ['events' => $googleAnalyticsEvents]);
    }
}
