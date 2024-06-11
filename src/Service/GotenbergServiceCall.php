<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GotenbergServiceCall
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private LoggerInterface $logger
    ) {
    }

    public function generatePdfFromUrl(string $url): string
    {
        try {
            $response = $this->client->request('POST', $_ENV['MICROSERVICE_URL'] . '/index.php/convert/pdf', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                ],
                'body' => [
                    'url' => $url,
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $this->logger->info('200');
                $pdfFilePath = $response->getContent();

                return $pdfFilePath;
            } else {
                return 'La requête à l\'API Gotenberg a échoué avec le statut ' . $statusCode;
            }
        } catch (\Exception $e) {
            $this->logger->error('Une erreur s\'est produite lors de la génération du PDF : ' . $e->getMessage());
            return 'Une erreur s\'est produite lors de la génération du PDF : ' . $e->getMessage();
        }
    }
}