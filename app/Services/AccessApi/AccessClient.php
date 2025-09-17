<?php

namespace App\Services\AccessApi;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AccessClient
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'application' => config('access.application'),
            'school' => config('access.school'),
            'key' => config('access.key'),
            'hash' => config('access.hash'),
            'url' => config('access.url', 'https://api.accessphp.net/'),
            'systemid' => config('access.systemid'),
            'debug' => config('access.debug', false),
        ], $config);
    }

    public function sendRequest(array $requestData): array
    {
        $url = rtrim($this->config['url'], '/') . '/';
        
        if ($this->config['debug']) {
            Log::info('ACCESS API Request', $requestData);
        }

        try {
            $response = Http::asForm()
                ->withUserAgent('LormaAccessPlus/1.0')
                ->post($url, $requestData);
            
            if ($this->config['debug']) {
                Log::info('ACCESS API Response', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('ACCESS API Error', [
                'message' => $e->getMessage(),
                'request' => $requestData
            ]);
            
            throw new AccessApiException('API request failed: ' . $e->getMessage());
        }
    }

    protected function handleResponse(Response $response): array
    {
        if (!$response->successful()) {
            throw new AccessApiException('API request failed with status: ' . $response->status());
        }

        $data = $response->json();
        
        if (!is_array($data)) {
            // Handle non-JSON responses (like PASS/FAIL)
            return ['result' => $response->body()];
        }

        return $data;
    }

    public function generateSid(): string
    {
        return uniqid('ACCESSPLUS-', true);
    }

    public function generateSecurityHash(string $field, string $sid): string
    {
        return sha1($field . $sid . $this->config['hash']);
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}