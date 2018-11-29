<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\EventListener;

use Setono\SyliusAnalyticsPlugin\Context\AnalyticConfigContextInterface;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

final class GoogleAnalyticEventListener
{
    /**
     * @var string
     */
    private $template;

    /** @var AnalyticConfigContextInterface */
    private $AnalyticConfigContext;

    public function __construct(string $template, AnalyticConfigContextInterface $AnalyticConfigContext)
    {
        $this->template = $template;
        $this->AnalyticConfigContext = $AnalyticConfigContext;
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
                'config' => $this->AnalyticConfigContext->getConfig(),
                ],
        ]));
        $block->setType('sonata.block.service.template');
        $event->addBlock($block);
    }
}
