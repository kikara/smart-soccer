<?php

namespace App\Ratchet;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class HttpClient
{
    private const HOST = 'http://172.21.0.1';

    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendToSaveGame(array $state): bool
    {
        $response = $this->client->post(
            $this->query('/api/games'),
            [
                'form_params' => $state,
                'headers' => ['Accept' => 'application/json']
            ]
        );

        return $response->getStatusCode() === 201;
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function setOccupation(Game $game, $fromUserId): void
    {
        $response = $this->client->post(
            $this->query('/api/bot/occupations'),
            [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'form_params' => [
                    'user_id' => $fromUserId,
                ]
            ]
        );
        if ($response->getStatusCode() === 200) {
            $data = $this->decodeContent($response->getBody()->getContents());
            $game->setTableOccupationId($data['table_occupation_id']);
            return;
        }
        throw new \RuntimeException('Table Occupation Request Error!');
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getUserIdByTelegramChatId(int $chatId): int
    {
        $response = $this->client->get(
            $this->query("/api/bot/telegrams/$chatId/user"),
            ['headers' => ['Accept' => 'application/json']]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('User Get Error!');
        }

        $data = $this->decodeContent($response->getBody()->getContents());

        return (int) $data['id'];
    }

    /**
     * @throws GuzzleException|JsonException
     */
    public function getSettings(int $id): array
    {
        $response = $this->client->get(
            $this->query("/api/bot/settings/$id"),
            [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(
                'Game template error! Status code = ' . $response->getStatusCode()
            );
        }

        return $this->decodeContent($response->getBody()->getContents());
    }


    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    protected function sendRequest(string $url, array $params = []): array
    {
        $request = $this->client->post($this->query($url), [
            'form_params' => $params
        ]);

        if ($request->getStatusCode() !== 200) {
            throw new \RuntimeException('request has an error ' . $request->getStatusCode());
        }

        $response = $request->getBody()->getContents();
        $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            throw new \RuntimeException('response cant decoded');
        }
        return $decoded;
    }

    /**
     * @param string $content
     * @return array
     * @throws JsonException
     */
    protected function decodeContent(string $content): array
    {
        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($decoded)) {
            throw new \RuntimeException('Response Data Decode Error!');
        }
        return $decoded;
    }

    protected function query(string $uri): string
    {
        return self::HOST . $uri;
    }
}
