<?php

namespace Devdot\ChurchTools\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class ChurchTools extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected string $baseUrl;

    /**
     * Constructs an OAuth 2.0 service provider.
     *
     * @param array<mixed> $options An array of options to set on this provider.
     *     Options include `url`, `clientId`, `clientSecret`, `redirectUri`, and `state`.
     * @param array<mixed> $collaborators An array of collaborators that may be used to
     *     override this provider's default behavior. Collaborators include
     *     `grantFactory`, `requestFactory`, and `httpClient`.
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->baseUrl = $options['url'];

        parent::__construct($options, $collaborators);
    }

    public function getBaseAuthorizationUrl(): string
    {
        return $this->baseUrl . '/oauth/authorize';
    }

    /**
     * @param string[] $params
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->baseUrl . '/oauth/access_token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->baseUrl . '/oauth/userinfo';
    }

    /**
     * @return string[]
     */
    protected function getDefaultScopes(): array
    {
        return [];
    }

    /**
     * @param array<mixed> $data
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        // @codeCoverageIgnoreStart
        if (empty($data['error'])) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $error = $data['error'];
        $code = $response->getStatusCode();

        throw new IdentityProviderException($error, $code, $data);
    }

    /**
     * @param array<mixed> $response
     */
    protected function createResourceOwner(array $response, AccessToken $token): ChurchToolsUser
    {
        return new ChurchToolsUser($response);
    }

    public function getAccessTokenFromCode(string $code): AccessTokenInterface
    {
        return $this->getAccessToken('authorization_code', [
            'code' => $code,
        ]);
    }
}
