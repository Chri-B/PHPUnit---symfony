<?php

namespace App\Service;

use App\Enum\HealthStatus;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubService
{
    public function __construct(private HttpClientInterface $http_client, private LoggerInterface $logger)
    {
    }

    public function getHealthReport(string $dinoName): HealthStatus
    {
        $health = HealthStatus::HEALTHY;

        $res = $this->http_client->request(
            method: 'GET',
            url: "https://api.github.com/repos/SymfonyCasts/dino-park/issues"
        );

        $this->logger->info('Request Dino Issues', [
            'dino' => $dinoName,
            'responseStatus' => $res->getStatusCode(),
        ]);

        foreach ($res->toArray() as $issue) {
            if(str_contains($issue['title'], $dinoName)) {
                $health = $this->getDinoStatusFromLabels($issue['labels']);
            }
        }

        return $health;
    }

    private function getDinoStatusFromLabels(array $labels): HealthStatus
    {
        $status = null;
        foreach ($labels as $label) {
            $label = $label['name'];
            // We only care about "Status" labels
            if (!str_starts_with($label, 'Status:')) {
                continue;
            }
            // Remove the "Status:" and whitespace from the label
            $status = trim(substr($label, strlen('Status:')));
        }
        return HealthStatus::tryFrom($status);
    }
}