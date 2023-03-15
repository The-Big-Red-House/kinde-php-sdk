<?php

namespace Kinde\KindeSDK;

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

use Exception;
use InvalidArgumentException;
use GuzzleHttp\Client;
use Kinde\KindeSDK\Sdk\Enums\AuthStatus;
use Kinde\KindeSDK\Sdk\OAuth2\PKCE;
use Kinde\KindeSDK\Sdk\Enums\GrantType;
use Kinde\KindeSDK\Sdk\Enums\StorageEnums;
use Kinde\KindeSDK\Sdk\Enums\TokenType;
use Kinde\KindeSDK\Sdk\OAuth2\AuthorizationCode;
use Kinde\KindeSDK\Sdk\OAuth2\ClientCredentials;
use Kinde\KindeSDK\Sdk\Utils\Utils;
use Kinde\KindeSDK\Sdk\Storage\Storage;

class KindeClientSDK
{
    /**
     * @var string A variable that is used to store the domain of the API.
     */
    public string $domain;

    /**
     * @var string This is the redirect URI that you provided when you registered your application.
     */
    public string $redirectUri;

    /**
     * @var string This is the logout redirect URI that you provided when you registered your application.
     */
    public string $logoutRedirectUri;

    /**
     * @var string A variable that is used to store the client ID of the application.
     */
    public string $clientId;

    /**
     * @var string This is the client secret of your application.
     */
    public string $clientSecret;

    /**
     * @var string This is the authorization endpoint of the API.
     */
    public string $authorizationEndpoint;

    /**
     * @var string This is the token endpoint of the API.
     */
    public string $tokenEndpoint;

    /** 
     * @var string Used to store the logout endpoint of the API. 
     */
    public string $logoutEndpoint;

    /* A variable that is used to store the grant type that you want to use. */
    public string $grantType;

    /* This is a variable that is used to store the status of the authorization. */
    public string $authStatus;

    /* This is a additionalParameters data. */
    public array $additionalParameters;

    /**
     * @var string This is a variable that is used to store the scopes that you want to request.
     */
    public string $scopes;

    /* A variable that is used to store the protocol that you want to use when the SDK requests to get a token */
    public string $protocol;

    public $storage;

    function __construct(
        string $domain,
        string $redirectUri,
        string $clientId,
        string $clientSecret,
        string $grantType,
        string $logoutRedirectUri,
        string $scopes = 'openid profile email offline',
        array $additionalParameters = [],
        string $protocol = ""
    ) {
        if (empty($domain)) {
            throw new InvalidArgumentException("Please provide domain");
        }
        if (!Utils::validationURL($domain)) {
            throw new InvalidArgumentException("Please provide valid domain");
        }
        $this->domain = $domain;

        if (empty($redirectUri)) {
            throw new InvalidArgumentException("Please provide redirect_uri");
        }
        if (!Utils::validationURL($redirectUri)) {
            throw new InvalidArgumentException("Please provide valid redirect_uri");
        }
        $this->redirectUri = $redirectUri;

        if (empty($clientSecret)) {
            throw new InvalidArgumentException("Please provide client_secret");
        }
        $this->clientSecret = $clientSecret;

        if (empty($clientId)) {
            throw new InvalidArgumentException("Please provide client_id");
        }
        $this->clientId = $clientId;

        if (empty($grantType)) {
            throw new InvalidArgumentException("Please provide grant_type");
        }
        $this->grantType = $grantType;

        if (empty($logoutRedirectUri)) {
            throw new InvalidArgumentException("Please provide logout_redirect_uri");
        }
        if (!Utils::validationURL($logoutRedirectUri)) {
            throw new InvalidArgumentException("Please provide valid logout_redirect_uri");
        }

        $this->additionalParameters = Utils::checkAdditionalParameters($additionalParameters);

        $this->logoutRedirectUri = $logoutRedirectUri;
        $this->scopes = $scopes;
        $this->protocol = $protocol;
        // Other endpoints
        $this->authorizationEndpoint = $this->domain . '/oauth2/auth';
        $this->tokenEndpoint = $this->domain . '/oauth2/token';
        $this->logoutEndpoint = $this->domain . '/logout';
        $this->authStatus = AuthStatus::UNAUTHENTICATED;

        $this->storage = Storage::getInstance();
    }

    public function __get($key)
    {
        if (!property_exists($this, $key) && $key === 'isAuthenticated') {
            return $this->isAuthenticated();
        }
        return $this->$key;
    }

    /**
     * A function that is used to login to the API.
     *
     * @param array additionalParameters The array includes params to pass api.
     * @param string scopes The scopes you want to request.
     * 
     * @return The login method returns an array with the following keys:
     */
    public function login(
        array $additionalParameters = []
    ) {
        $this->cleanStorage();
        try {
            $this->updateAuthStatus(AuthStatus::AUTHENTICATING);
            switch ($this->grantType) {
                case GrantType::clientCredentials:
                    $auth = new ClientCredentials();
                    return $auth->login($this, $additionalParameters);
                case GrantType::authorizationCode:
                    $auth = new AuthorizationCode();
                    return $auth->login($this, $additionalParameters);
                case GrantType::PKCE:
                    $auth = new PKCE();
                    return $auth->login($this, 'login', $additionalParameters);
                default:
                    $this->updateAuthStatus(AuthStatus::UNAUTHENTICATED);
                    throw new InvalidArgumentException("Please provide correct grant_type");
                    break;
            }
        } catch (\Throwable $th) {
            $this->updateAuthStatus(AuthStatus::UNAUTHENTICATED);
            throw $th;
        }
    }

    /**
     * It redirects the user to the authorization endpoint with the client id, redirect uri, a random
     * state, and the start page set to registration
     *
     * @param array additionalParameters The array includes params to pass api.
     */
    public function register(array $additionalParameters = [])
    {
        $this->updateAuthStatus(AuthStatus::AUTHENTICATING);
        $this->grantType = 'authorization_code';
        $auth = new PKCE();
        return $auth->login($this, 'registration', $additionalParameters);
    }

    /**
     * It redirects the user to the authorization endpoint with the client id, redirect uri, a random
     * state, and the start page set to registration and allow an organization to be created
     *
     *  @param array additionalParameters The array includes params to pass api.
     */
    public function createOrg(array $additionalParameters = [])
    {
        $additionalParameters['is_create_org'] = 'true';
        return $this->register($additionalParameters);
    }

    /**
     * It takes the grant type as parameter, and returns the token
     * 
     * @param array authServerParams The call back params from auth server.
     */
    public function getToken()
    {
        if ($this->grantType == GrantType::clientCredentials) {
            return $this->login();
        }
        // Check authenticated
        if ($this->isAuthenticated) {
            $token = $this->storage->getToken();
            if (!empty($token)) {
                return $token;
            }
        }

        try {
            $refreshToken = $this->storage->getRefreshToken();
            if (!empty($refreshToken)) {
                $formParams = [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken
                ];
                return $this->fetchToken($formParams);
            }
        } catch (\Throwable $th) {
            // Refresh token is invalid, continue fetch token
            // throw $th;
        }

        $newGrantType = $this->getGrantType($this->grantType);
        $formParams = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $newGrantType,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code'
        ];
        $url = $this->getProtocol() . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlComponents = parse_url($url);
        parse_str($urlComponents['query'] ?? "", $params);
        $stateServer = $params['state'] ?? null;
        $this->checkStateAuthentication($stateServer);
        $error = $params['error'] ?? '';
        if (!empty($error)) {
            $errorDescription = $params['error_description'] ?? '';
            $msg = !empty($errorDescription) ? $errorDescription : $error;
            throw new OAuthException($msg);
        }
        $authorizationCode = $params['code'] ?? '';
        if (empty($authorizationCode)) {
            throw new InvalidArgumentException('Not found code param');
        }
        $formParams['code'] = $authorizationCode;
        $codeVerifier = $this->storage->getCodeVerifier();
        if (!empty($codeVerifier)) {
            $formParams['code_verifier'] = $codeVerifier;
        } else if ($this->grantType == GrantType::PKCE) {
            throw new InvalidArgumentException('Not found code_verifier');
        }
        return $this->fetchToken($formParams);
    }

    private function fetchToken($formParams)
    {
        $client = new Client();
        $response =
            $client->request('POST', $this->tokenEndpoint, [
                'form_params' => $formParams
            ]);
        $token = $response->getBody()->getContents();
        $this->storage->setToken($token);
        $tokenDecode = json_decode($token);
        $this->updateAuthStatus(AuthStatus::AUTHENTICATED);

        // Cleaning
        $this->storage->removeItem(StorageEnums::CODE_VERIFIER);
        $this->storage->removeItem(StorageEnums::STATE);
        return $tokenDecode;
    }

    /**
     * It returns user's information after successful authentication
     *
     * @return array The response is a array containing id, given_name, family_name and email.
     */
    public function getUserDetails()
    {
        return $this->storage->getUserProfile();
    }

    /**
     * It unset's the token from the storage and redirects the user to the logout endpoint
     */
    public function logout()
    {
        $this->cleanStorage();
        $this->updateAuthStatus(AuthStatus::UNAUTHENTICATED);
        $searchParams = [
            'redirect' => $this->logoutRedirectUri
        ];
        header('Location: ' . $this->logoutEndpoint . '?' . http_build_query($searchParams));
        exit();
    }

    /**
     * This function takes a grant type and returns the grant type in the format that the API expects
     * 
     * @param string grantType The type of grant you want to use.
     * 
     * @return The grant type is being returned.
     */
    public function getGrantType(string $grantType)
    {
        switch ($grantType) {
            case GrantType::authorizationCode:
            case GrantType::PKCE:
                return 'authorization_code';
            case GrantType::clientCredentials:
                return 'client_credentials';
            default:
                throw new InvalidArgumentException("Please provide correct grant_type");
                break;
        }
    }

    /**
     * It checks user is logged.
     *
     * @return bool The response is a bool, which check user logged or not
     */
    public function isAuthenticated()
    {
        $timeExpired = $this->storage->getExpiredAt();
        if (empty($timeExpired)) {
            return false;
        }
        return $timeExpired > time();
    }

    private function getClaims(string $tokenType = TokenType::ACCESS_TOKEN)
    {
        if (!in_array($tokenType, [TokenType::ACCESS_TOKEN, TokenType::ID_TOKEN])) {
            throw new InvalidArgumentException('Please provide valid token (access_token or id_token) to get claim');
        }
        $token = $this->storage->getItem($tokenType);
        if (empty($token)) {
            throw new Exception('Request is missing required authentication credential');
        }
        return Utils::parseJWT($token);
    }

    /**
     * Accept a key for a token and returns the claim value.
     * Optional argument to define which token to check - defaults to Access token  - e.g.
     *
     * @param string keyName Accept a key for a token.
     * @param string tokenType Optional argument to define which token to check.
     *
     * @return any The response is a data in token.
     */
    public function getClaim(string $keyName, string $tokenType = TokenType::ACCESS_TOKEN)
    {
        $data = self::getClaims($tokenType);
        return $data[$keyName] ?? null;
    }

    /**
     * Get an array of permissions (from the permissions claim in access token)
     * And also the relevant org code (org_code claim in access token). e.g
     *
     * @return array The response includes orgCode and permissions.
     */
    public function getPermissions()
    {
        $claims = self::getClaims();
        return [
            'orgCode' => $claims['org_code'],
            'permissions' => $claims['permissions']
        ];
    }

    /**
     * Given a permission value, returns if it is granted or not (checks if permission key exists in the permissions claim array)
     * And relevant org code (checking against claim org_code) e.g
     *
     * @return array The response includes orgCode and isGranted.
     */
    public function getPermission(string $permission)
    {
        $allClaims = self::getClaims();
        $permissions = $allClaims['permissions'];
        return [
            'orgCode' => $allClaims['org_code'],
            'isGranted' => in_array($permission, $permissions)
        ];
    }

    /**
     * Gets the org code (and later other org info) (checking against claim org_code)
     *
     * @return array The response is a orgCode.
     */
    public function getOrganization()
    {
        return [
            'orgCode' => self::getClaim('org_code')
        ];
    }
    /**
     * Gets all org code
     *
     * @return array The response is a orgCodes.
     */
    public function getUserOrganizations()
    {
        return [
            'orgCodes' => self::getClaim('org_codes', TokenType::ID_TOKEN)
        ];
    }

    public function getAuthStatus()
    {
        return $this->storage->getAuthStatus();
    }

    private function updateAuthStatus(string $_authStatus)
    {
        $this->storage->setAuthStatus($_authStatus);
        $this->authStatus = $_authStatus;
    }

    private function cleanStorage()
    {
        $this->storage->clear();
    }

    private function getProtocol()
    {
        if (!empty($this->protocol)) {
            return $this->protocol;
        }
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    }

    private function checkStateAuthentication(string $stateServer)
    {
        $storageOAuthState = $this->storage->getState();
        if (empty($storageOAuthState) || $stateServer != $storageOAuthState) {
            throw new OAuthException("Authentication failed because it tries to validate state");
        }
    }
}
