<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusAnalyticsPlugin\Resolver\Category;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\SyliusAnalyticsPlugin\Resolver\Category\CategoryResolver;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;

/**
 * @covers \Setono\SyliusAnalyticsPlugin\Resolver\Category\CategoryResolver
 */
final class CategoryResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_resolves_category_list(): void
    {
        $ancestors = new ArrayCollection();
        for ($i = 3; $i >= 1; --$i) {
            $taxon = $this->prophesize(TaxonInterface::class);
            $taxon->getName()->willReturn(sprintf('Level %d', $i));

            $ancestors->add($taxon->reveal());
        }

        $mainTaxon = $this->prophesize(TaxonInterface::class);
        $mainTaxon->getAncestors()->willReturn($ancestors);
        $mainTaxon->getName()->willReturn('Level 4');

        $product = $this->prophesize(ProductInterface::class);
        $product->getMainTaxon()->willReturn($mainTaxon->reveal());

        $resolver = new CategoryResolver();
        self::assertSame(['Level 1', 'Level 2', 'Level 3', 'Level 4'], $resolver->resolveFromProduct($product->reveal()));
    }
}
