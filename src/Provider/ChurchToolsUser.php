<?php

namespace Devdot\ChurchTools\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ChurchToolsUser implements ResourceOwnerInterface
{
    /**
     * @var array<mixed>
     */
    protected array $response;

    /**
     * @param array<mixed> $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId(): null|int|string
    {
        return $this->response['id'] ?? null;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return $this->response;
    }

    public function getFirstName(): string
    {
        return (string) ($this->response['firstName'] ?? '');
    }

    public function getLastName(): string
    {
        return (string) ($this->response['lastName'] ?? '');
    }

    public function getDisplayName(): string
    {
        return (string) ($this->response['displayName'] ?? '');
    }

    public function getEmail(): string
    {
        return (string) ($this->response['email'] ?? '');
    }

    public function getImageUrl(): string
    {
        return (string) ($this->response['imageUrl'] ?? '');
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->response['groups'] ?? [];
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->response['roles'] ?? [];
    }
}
