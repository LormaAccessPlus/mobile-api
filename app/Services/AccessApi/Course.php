<?php

namespace App\Services\AccessApi;

class Course
{
    protected AccessClient $client;
    protected string $courseId;

    public function __construct(string $courseId, ?AccessClient $client = null)
    {
        $this->courseId = $courseId;
        $this->client = $client ?? new AccessClient();
    }

    public function getInfo(): array
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'course',
            'call' => 'info',
            'sec' => $this->client->generateSecurityHash($this->courseId, $sid),
            'field' => 'id',
            'id' => $this->courseId,
            'sid' => $sid,
        ];

        return $this->client->sendRequest($request);
    }

    
}