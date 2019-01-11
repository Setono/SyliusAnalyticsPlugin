<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\EventListener;

use PhpSpec\ObjectBehavior;
use Setono\SyliusAnalyticsPlugin\Context\AnalyticConfigContextInterface;
use Setono\SyliusAnalyticsPlugin\EventListener\GoogleAnalyticEventListener;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

final class GoogleAnalyticEventListenerSpec extends ObjectBehavior
{
    function let(AnalyticConfigContextInterface $AnalyticConfigContext): void
    {
        $this->beConstructedWith('template', $AnalyticConfigContext);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(GoogleAnalyticEventListener::class);
    }

    function itWorksOnBlockEvent(
        string $template,
        AnalyticConfigContextInterface $AnalyticConfigContext,
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
