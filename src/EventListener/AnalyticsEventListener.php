<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Context\AnalyticsConfigContextInterface;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

final class AnalyticsEventListener
{
    /** @var string */
    private $template;

    /** @var AnalyticsConfigContextInterface */
    private $analyticsConfigContext;

    public function __construct(string $template, AnalyticsConfigContextInterface $analyticsConfigContext)
    {
        $this->template = $template;
        $this->analyticsConfigContext = $analyticsConfigContext;
    }

    /**
     * @param BlockEvent $event
     */
    public function onBlockEvent(BlockEvent $event): void
    {
        $block = new Block();
        $block->setId(uniqid('', true));
        $block->setSettings(array_replace($event->getSettings(), [
            'template' => $this->template,
            'attr' => [
                'config' => $this->analyticsConfigContext->getConfig(),
                ],
        ]));
        $block->setType('sonata.block.service.template');
        $event->addBlock($block);
    }
}
