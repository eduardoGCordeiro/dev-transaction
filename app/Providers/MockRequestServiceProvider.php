<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MockRequestException;

class MockRequestServiceProvider
{
    public function enableOperation(string $rest_method, string $uri)
    {
        $client = new Client();

        try {
            $response = $client->request(
                $rest_method,
                $uri
            );
        } catch (GuzzleException $exception) {
            throw new MockRequestException('Unavailable service!');

            Log::critical('[Mock request error]', [
                'message' => $exception->getMessage()
            ]);
        }

        $response = json_decode($response->getBody());
        return $response->message === 'Autorizado';
    }
}
