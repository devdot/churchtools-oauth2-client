devdot/churchtools-oauth2-client
================================

*OAuth2 Client for ChurchTools*

# Basic Usage

```php
use Devdot\ChurchTools\OAuth2\Client\Provider\ChurchTools;

$provider = new ChurchTools([
    'url' => 'https://YOUR-INSTANCE.church.tools',
    'clientId' => 'Client-ID-From-OAUTH2-Setup',
    'redirectUri' => 'The Redirect Uri you provided to CT',
]);

$code = $_GET['code'] ?? null;

if ($code === null) {
    // redirect to OAuth Server
    $redirect = $provider->getAuthorizationUrl();

    header('Location: ' . $redirect);
    exit;
}
else {
    try {
        // attempt to get access tokens
        $tokens = $provider->getAccessTokenFromCode($code);

        // get the user that was authenticated
        $oauthUser = $provider->getResourceOwner($tokens);

        // store to session or use for further validation
        // ...
    }
    catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        if ($e->getMessage() === 'invalid_grant') {
            // code is not valid anymore, try again
            header('Location: ' . $provider->getAuthorizationUrl());
            exit;
        }
        
        throw $e;
    }
}
```
