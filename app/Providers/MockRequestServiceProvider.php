<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MockService\MockRequestException;

class MockRequestServiceProvider
{
    public function enableOperation(string $rest_method, string $uri): bool
    {
        $client = new Client();

        try {
            $response = $client->request(
                $rest_method,
                $uri
            );
        } catch (GuzzleException $exception) {
            Log::critical('[Mock request error]', [
                'message' => $exception->getMessage()
            ]);

            throw new MockRequestException('Service unavailable.');
        }

        $response = json_decode($response->getBody());

        if (!$response) {
            return false;
        }

        if (!property_exists($response, 'message')) {
            return false;
        }

        return $response->message === 'Autorizado';
    }
}
