<?php

namespace App\Ratchet;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class HttpClient
{
    private const HOST = 'http://localhost';

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
            $this->query('/api/occupations'),
            [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'form_params' => [
                    'user_id' => $fromUserId,
                ]
            ]
        );
        
        $status = $response->getStatusCode();

        if ($status >= 200 && $status < 300) {
            $data = $this->decodeContent($response->getBody()->getContents());
            if ($id = (int) $data['data']['id']) {
                $game->setTableOccupationId($id);
                return;
            }
        }
        throw new \RuntimeException('Table Occupation Request Error!');
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getUserIdByTelegramChatId($chatId): int
    {
        $response = $this->client->get(
            $this->query('/api/users/find'),
            [
                'headers' => ['Accept' => 'application/json'],
                'query' => ['chat_id' => $chatId]
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('User Get Error!');
        }

        $data = $this->decodeContent($response->getBody()->getContents());

        $user = $data['data'];

        return (int) $user['id'];
    }

    /**
     * @throws GuzzleException|JsonException
     */
    public function getSettings($id): array
    {
        $response = $this->client->get(
            $this->query("/api/settings/$id"),
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

        $data = $this->decodeContent($response->getBody()->getContents());

        return $data['data'];
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
