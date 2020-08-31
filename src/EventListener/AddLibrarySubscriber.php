<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\TagBag\Tag\GtagConfig;
use Setono\TagBag\Tag\GtagLibrary;
use Setono\TagBag\Tag\TagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AddLibrarySubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'add',
        ];
    }

    public function add(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest() || !$this->isShopContext($request)) {
            return;
        }

        // Only add the library on 'real' page loads, not AJAX requests like add to cart
        if ($request->isXmlHttpRequest()) {
            return;
        }

        if (!$this->hasProperties()) {
            return;
        }

        $this->tagBag->addTag(new GtagLibrary((string) $this->getProperties()[0]->getTrackingId()));

        foreach ($this->getProperties() as $property) {
            $this->tagBag->addTag(
                (new GtagConfig((string) $property->getTrackingId()))
                    ->setSection(TagInterface::SECTION_HEAD)
                    ->addDependency(GtagLibrary::NAME)
            );
        }
    }
}
