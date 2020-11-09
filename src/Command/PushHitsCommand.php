<?php

declare(strict_types=1);

namespace Setono\SyliusAnalyticsPlugin\Command;

use Safe\DateTime;
use Setono\SyliusAnalyticsPlugin\Repository\HitRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class PushHitsCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'setono:sylius-analytics:push-hits';

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var HitRepositoryInterface */
    private $hitRepository;

    /** @var int */
    private $delay;

    public function __construct(HttpClientInterface $httpClient, HitRepositoryInterface $hitRepository, int $delay)
    {
        parent::__construct();

        $this->httpClient = $httpClient;
        $this->hitRepository = $hitRepository;
        $this->delay = $delay;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hits = $this->hitRepository->findConsentedWithDelay($this->delay);

        $responses = [];

        foreach ($hits as $hit) {
            $createdAt = $hit->getCreatedAt();
            Assert::notNull($createdAt);

            $queueTime = 1000 * ((new DateTime())->getTimestamp() - $createdAt->getTimestamp());
            $payload = $hit->getPayload() . '&qt=' . $queueTime;
            $responses[] = $this->httpClient->request('POST', 'https://www.google-analytics.com/debug/collect', [
                'body' => $payload,
                'user_data' => $hit->getId(),
            ]);
        }

        foreach ($this->httpClient->stream($responses, 5) as $response => $chunk) {
            if ($chunk->isTimeout()) {
                // todo mark hit as failed
            } elseif ($chunk->isLast()) {
                // todo parse response and remove hit from database on success
            }
        }

        return 0;
    }
}
