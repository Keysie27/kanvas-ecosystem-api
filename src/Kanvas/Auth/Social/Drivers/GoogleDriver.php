<?php

declare(strict_types=1);

namespace Kanvas\Auth\Social\Drivers;

use Google_Client;
use Kanvas\Auth\Exceptions\AuthenticationException;
use Kanvas\Auth\Social\Contracts\DriverInterface;
use Kanvas\Auth\Social\DataTransferObject\User;

class GoogleDriver implements DriverInterface
{
    protected Google_Client $client;

    public function __construct(protected array $config)
    {
        $this->client = new Google_Client();
        $this->client->setClientId($this->config['client_id']);
        $this->client->setClientSecret($this->config['client_secret']);
        $this->client->setRedirectUri($this->config['redirect_uri']);
    }

    public function getUserFromToken(string $token): User
    {
        $payload = $this->client->verifyIdToken($token);
        if (! $payload) {
            throw new AuthenticationException('Invalid token');
        }
        $this->client->setAccessToken(
            $token
        );

        return User::from([
            'id' => $payload['sub'],
            'email' => $payload['email'],
            'nickname' => $payload['name'],
            'name' => $payload['name'],
            'token' => $token,
        ]);
    }
}
