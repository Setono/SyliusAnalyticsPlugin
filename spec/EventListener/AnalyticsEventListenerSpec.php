<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\Context\AnalyticsConfigContextInterface;
use Setono\SyliusAnalyticsPlugin\EventListener\AnalyticsEventListener;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

final class AnalyticsEventListenerSpec extends ObjectBehavior
{
    function let(AnalyticsConfigContextInterface $AnalyticConfigContext): void
    {
        $this->beConstructedWith('template', $AnalyticConfigContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AnalyticsEventListener::class);
    }

    function itWorksOnBlockEvent(
        string $template,
        AnalyticsConfigContextInterface $AnalyticConfigContext,
        BlockEvent $event,
        Block $block
    ): void {
        $block->setId(uniqid('', true))->shouldBeCalled;
        $block
            ->setSettings(array_replace($event->getSettings(), [
            'template' => $template,
            'attr' => ['config' => $AnalyticConfigContext->getConfig()],
        ]))
        ->willReturn($block)
        ;
        $this->onBlockEvent($event);
    }
}
