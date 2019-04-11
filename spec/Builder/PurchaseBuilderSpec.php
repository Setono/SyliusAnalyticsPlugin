<?php

declare(strict_types=1);

namespace spec\Setono\SyliusAnalyticsPlugin\Builder;

use Setono\SyliusAnalyticsPlugin\Builder\PurchaseBuilder;

class PurchaseBuilderSpec extends BuilderSpec
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PurchaseBuilder::class);
    }

    public function it_builds(): void
    {
        $this->setTransactionId('trans-1234')
            ->setAffiliation('affiliation-1234')
            ->setValue(1234.56)
            ->setCurrency('USD')
            ->setTax(12.34)
            ->setShipping(5.5)
            ->addItem([
                'id' => 'id-1234',
            ])
        ;

        $this->getData()->shouldEqual([
            'transaction_id' => 'trans-1234',
            'affiliation' => 'affiliation-1234',
            'value' => 1234.56,
            'currency' => 'USD',
            'tax' => 12.34,
            'shipping' => 5.5,
            'items' => [
                ['id' => 'id-1234'],
            ],
        ]);
    }
}
