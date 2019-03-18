<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Context\PropertyContextInterface;
use Setono\SyliusAnalyticsPlugin\Tag\Tags;
use Setono\TagBagBundle\Tag\TagInterface;
use Setono\TagBagBundle\Tag\TwigTag;
use Setono\TagBagBundle\TagBag\TagBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AddLibrarySubscriber extends TagSubscriber
{
    /**
     * @var PropertyContextInterface
     */
    private $propertyContext;

    public function __construct(TagBagInterface $tagBag, PropertyContextInterface $propertyContext)
    {
        parent::__construct($tagBag);

        $this->propertyContext = $propertyContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                'add',
            ],
        ];
    }

    public function add(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // Only add the library on 'real' page loads, not AJAX requests like add to cart
        if ($event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $properties = $this->propertyContext->getProperties();
        if (\count($properties) === 0) {
            return;
        }

        $this->tagBag->add(new TwigTag(
            '@SetonoSyliusAnalyticsPlugin/Tag/library.html.twig',
            TagInterface::TYPE_HTML,
            Tags::TAG_LIBRARY,
            [
                'properties' => $properties,
            ]
        ), TagBagInterface::SECTION_HEAD);
    }
}
