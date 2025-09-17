<?php

namespace App\Services\AccessApi;

class Student
{
    protected AccessClient $client;
    protected string $studentId;

    public function __construct(string $studentId, ?AccessClient $client = null)
    {
        $this->studentId = $studentId;
        $this->client = $client ?? new AccessClient();
    }

    public function getInfo(array $params = []): array
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'student',
            'call' => 'info',
            'sec' => $this->client->generateSecurityHash($this->studentId, $sid),
            'field' => 'studid',
            'studid' => $this->studentId,
            'sid' => $sid,
        ];

        if (!empty($params)) {
            $request['params'] = json_encode($params, JSON_FORCE_OBJECT);
        }

        return $this->client->sendRequest($request);
    }

    public function authenticate(string $password): bool
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'student',
            'call' => 'auth',
            'sec' => $this->client->generateSecurityHash($this->studentId, $sid),
            'field' => 'studid',
            'studid' => $this->studentId,
            'sid' => $sid,
            'params' => json_encode(['pass' => $password], JSON_FORCE_OBJECT)
        ];

        $response = $this->client->sendRequest($request);
        
        return isset($response['data']) && $response['data'] === 'PASS';
    }

    public function getCurriculum(): array
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'student',
            'call' => 'curriculum',
            'sec' => $this->client->generateSecurityHash($this->studentId, $sid),
            'field' => 'studid',
            'studid' => $this->studentId,
            'sid' => $sid,
        ];

        return $this->client->sendRequest($request);
    }

    public function getGrades(): array
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'student',
            'call' => 'grades',
            'sec' => $this->client->generateSecurityHash($this->studentId, $sid),
            'field' => 'studid',
            'studid' => $this->studentId,
            'sid' => $sid,
        ];

        return $this->client->sendRequest($request);
    }

    public function getAssessment(): array
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'student',
            'call' => 'assessment',
            'sec' => $this->client->generateSecurityHash($this->studentId, $sid),
            'field' => 'studid',
            'studid' => $this->studentId,
            'sid' => $sid,
        ];

        return $this->client->sendRequest($request);
    }

    public function getBalance(): array
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'student',
            'call' => 'balance',
            'sec' => $this->client->generateSecurityHash($this->studentId, $sid),
            'field' => 'studid',
            'studid' => $this->studentId,
            'sid' => $sid,
        ];

        return $this->client->sendRequest($request);
    }

    public function getLedgerHistory(): array
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'student',
            'call' => 'ledgerHistory',
            'sec' => $this->client->generateSecurityHash($this->studentId, $sid),
            'field' => 'studid',
            'studid' => $this->studentId,
            'sid' => $sid,
        ];

        return $this->client->sendRequest($request);
    }

    public function assess(): array
    {
        $sid = $this->client->generateSid();
        
        $request = [
            'key' => $this->client->getConfig()['key'],
            'action' => 'student',
            'call' => 'assess',
            'sec' => $this->client->generateSecurityHash($this->studentId, $sid),
            'field' => 'studid',
            'studid' => $this->studentId,
            'sid' => $sid,
        ];

        return $this->client->sendRequest($request);
    }
}