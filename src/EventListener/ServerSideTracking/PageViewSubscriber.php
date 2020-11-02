<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener\ServerSideTracking;

use function Safe\preg_match;
use function Safe\preg_replace;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

final class PageViewSubscriber implements EventSubscriberInterface
{
    /** @var PageViewBuilder */
    private $pageViewBuilder;

    public function __construct(PageViewBuilder $pageViewBuilder)
    {
        $this->pageViewBuilder = $pageViewBuilder;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => 'populate',
        ];
    }

    public function populate(TerminateEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // todo check for shop context

        $response = $event->getResponse();

        $this->pageViewBuilder->call(static function (Analytics $analytics) use ($response): void {
            $content = $response->getContent();
            if (false === $content) {
                return;
            }

            if (preg_match('#<title>(.*?)</title>#is', $content, $matches) === 0) {
                return;
            }

            if (!isset($matches[1])) {
                return;
            }

            // sanitize the title
            $title = preg_replace('/[\s]+/', ' ', $matches[1]);

            $analytics->setDocumentTitle($title);
        });
    }
}
