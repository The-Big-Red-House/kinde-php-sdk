<?php
/**
 * ConnectedAppsApi
 * PHP version 7.4
 *
 * @category Class
 * @package  Kinde\KindeSDK
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Kinde Management API
 *
 * Provides endpoints to manage your Kinde Businesses
 *
 * The version of the OpenAPI document: 1
 * Contact: support@kinde.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.1.0-SNAPSHOT
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Kinde\KindeSDK\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Kinde\KindeSDK\ApiException;
use Kinde\KindeSDK\Configuration;
use Kinde\KindeSDK\HeaderSelector;
use Kinde\KindeSDK\ObjectSerializer;
use Kinde\KindeSDK\Sdk\Storage\Storage;

/**
 * ConnectedAppsApi Class Doc Comment
 *
 * @category Class
 * @package  Kinde\KindeSDK
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class ConnectedAppsApi
{
    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /**
     * @param Configuration   $config
     * @param ClientInterface $client
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        Configuration $config,
        ClientInterface $client = null,
        HeaderSelector $selector = null,
        $hostIndex = 0
    ) {
        $this->config = $config;
        $this->client = $client ?: new Client();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
        
        $this->storage = Storage::getInstance();
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation getConnectedAppAuthUrl
     *
     * Get Connected App URL
     *
     * @param  string $key_code_ref The unique key code reference of the connected app to authenticate against. (required)
     * @param  int $user_id The id of the user that needs to authenticate to the third-party connected app. (required)
     *
     * @throws \Kinde\KindeSDK\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \Kinde\KindeSDK\Model\ConnectedAppsAuthUrl
     */
    public function getConnectedAppAuthUrl($key_code_ref, $user_id)
    {
        list($response) = $this->getConnectedAppAuthUrlWithHttpInfo($key_code_ref, $user_id);
        return $response;
    }

    /**
     * Operation getConnectedAppAuthUrlWithHttpInfo
     *
     * Get Connected App URL
     *
     * @param  string $key_code_ref The unique key code reference of the connected app to authenticate against. (required)
     * @param  int $user_id The id of the user that needs to authenticate to the third-party connected app. (required)
     *
     * @throws \Kinde\KindeSDK\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \Kinde\KindeSDK\Model\ConnectedAppsAuthUrl, HTTP status code, HTTP response headers (array of strings)
     */
    public function getConnectedAppAuthUrlWithHttpInfo($key_code_ref, $user_id)
    {
        $request = $this->getConnectedAppAuthUrlRequest($key_code_ref, $user_id);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch ($statusCode) {
                case 200:
                    if ('\Kinde\KindeSDK\Model\ConnectedAppsAuthUrl' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Kinde\KindeSDK\Model\ConnectedAppsAuthUrl' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Kinde\KindeSDK\Model\ConnectedAppsAuthUrl', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\Kinde\KindeSDK\Model\ConnectedAppsAuthUrl';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Kinde\KindeSDK\Model\ConnectedAppsAuthUrl',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation getConnectedAppAuthUrlAsync
     *
     * Get Connected App URL
     *
     * @param  string $key_code_ref The unique key code reference of the connected app to authenticate against. (required)
     * @param  int $user_id The id of the user that needs to authenticate to the third-party connected app. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getConnectedAppAuthUrlAsync($key_code_ref, $user_id)
    {
        return $this->getConnectedAppAuthUrlAsyncWithHttpInfo($key_code_ref, $user_id)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getConnectedAppAuthUrlAsyncWithHttpInfo
     *
     * Get Connected App URL
     *
     * @param  string $key_code_ref The unique key code reference of the connected app to authenticate against. (required)
     * @param  int $user_id The id of the user that needs to authenticate to the third-party connected app. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getConnectedAppAuthUrlAsyncWithHttpInfo($key_code_ref, $user_id)
    {
        $returnType = '\Kinde\KindeSDK\Model\ConnectedAppsAuthUrl';
        $request = $this->getConnectedAppAuthUrlRequest($key_code_ref, $user_id);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getConnectedAppAuthUrl'
     *
     * @param  string $key_code_ref The unique key code reference of the connected app to authenticate against. (required)
     * @param  int $user_id The id of the user that needs to authenticate to the third-party connected app. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getConnectedAppAuthUrlRequest($key_code_ref, $user_id)
    {
        // verify the required parameter 'key_code_ref' is set
        if ($key_code_ref === null || (is_array($key_code_ref) && count($key_code_ref) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $key_code_ref when calling getConnectedAppAuthUrl'
            );
        }
        // verify the required parameter 'user_id' is set
        if ($user_id === null || (is_array($user_id) && count($user_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $user_id when calling getConnectedAppAuthUrl'
            );
        }

        $resourcePath = '/api/v1/connected_apps/auth_url';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $key_code_ref,
            'key_code_ref', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $user_id,
            'user_id', // param base name
            'integer', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);




        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/json'],
                []
            );
        }

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires Bearer (JWT) authentication (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        } else {
            $token = $this->storage->getAccessToken();
            if (!empty($token)) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getConnectedAppToken
     *
     * Get Connected App Token
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \Kinde\KindeSDK\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \Kinde\KindeSDK\Model\ConnectedAppsAccessToken
     */
    public function getConnectedAppToken($session_id)
    {
        list($response) = $this->getConnectedAppTokenWithHttpInfo($session_id);
        return $response;
    }

    /**
     * Operation getConnectedAppTokenWithHttpInfo
     *
     * Get Connected App Token
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \Kinde\KindeSDK\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \Kinde\KindeSDK\Model\ConnectedAppsAccessToken, HTTP status code, HTTP response headers (array of strings)
     */
    public function getConnectedAppTokenWithHttpInfo($session_id)
    {
        $request = $this->getConnectedAppTokenRequest($session_id);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch ($statusCode) {
                case 200:
                    if ('\Kinde\KindeSDK\Model\ConnectedAppsAccessToken' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Kinde\KindeSDK\Model\ConnectedAppsAccessToken' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Kinde\KindeSDK\Model\ConnectedAppsAccessToken', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\Kinde\KindeSDK\Model\ConnectedAppsAccessToken';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Kinde\KindeSDK\Model\ConnectedAppsAccessToken',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation getConnectedAppTokenAsync
     *
     * Get Connected App Token
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getConnectedAppTokenAsync($session_id)
    {
        return $this->getConnectedAppTokenAsyncWithHttpInfo($session_id)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getConnectedAppTokenAsyncWithHttpInfo
     *
     * Get Connected App Token
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getConnectedAppTokenAsyncWithHttpInfo($session_id)
    {
        $returnType = '\Kinde\KindeSDK\Model\ConnectedAppsAccessToken';
        $request = $this->getConnectedAppTokenRequest($session_id);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getConnectedAppToken'
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getConnectedAppTokenRequest($session_id)
    {
        // verify the required parameter 'session_id' is set
        if ($session_id === null || (is_array($session_id) && count($session_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $session_id when calling getConnectedAppToken'
            );
        }

        $resourcePath = '/api/v1/connected_apps/token';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $session_id,
            'session_id', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);




        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/json'],
                []
            );
        }

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires Bearer (JWT) authentication (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        } else {
            $token = $this->storage->getAccessToken();
            if (!empty($token)) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation revokeConnectedAppToken
     *
     * Revoke Connected App Token
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \Kinde\KindeSDK\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \Kinde\KindeSDK\Model\ApiResult|\Kinde\KindeSDK\Model\ApiResult|\Kinde\KindeSDK\Model\ApiResult|\Kinde\KindeSDK\Model\ApiResult
     */
    public function revokeConnectedAppToken($session_id)
    {
        list($response) = $this->revokeConnectedAppTokenWithHttpInfo($session_id);
        return $response;
    }

    /**
     * Operation revokeConnectedAppTokenWithHttpInfo
     *
     * Revoke Connected App Token
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \Kinde\KindeSDK\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \Kinde\KindeSDK\Model\ApiResult|\Kinde\KindeSDK\Model\ApiResult|\Kinde\KindeSDK\Model\ApiResult|\Kinde\KindeSDK\Model\ApiResult, HTTP status code, HTTP response headers (array of strings)
     */
    public function revokeConnectedAppTokenWithHttpInfo($session_id)
    {
        $request = $this->revokeConnectedAppTokenRequest($session_id);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            switch ($statusCode) {
                case 200:
                    if ('\Kinde\KindeSDK\Model\ApiResult' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Kinde\KindeSDK\Model\ApiResult' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Kinde\KindeSDK\Model\ApiResult', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 400:
                    if ('\Kinde\KindeSDK\Model\ApiResult' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Kinde\KindeSDK\Model\ApiResult' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Kinde\KindeSDK\Model\ApiResult', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 403:
                    if ('\Kinde\KindeSDK\Model\ApiResult' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Kinde\KindeSDK\Model\ApiResult' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Kinde\KindeSDK\Model\ApiResult', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                case 405:
                    if ('\Kinde\KindeSDK\Model\ApiResult' === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ('\Kinde\KindeSDK\Model\ApiResult' !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, '\Kinde\KindeSDK\Model\ApiResult', []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
            }

            $returnType = '\Kinde\KindeSDK\Model\ApiResult';
            if ($returnType === '\SplFileObject') {
                $content = $response->getBody(); //stream goes to serializer
            } else {
                $content = (string) $response->getBody();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Kinde\KindeSDK\Model\ApiResult',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Kinde\KindeSDK\Model\ApiResult',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Kinde\KindeSDK\Model\ApiResult',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Kinde\KindeSDK\Model\ApiResult',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation revokeConnectedAppTokenAsync
     *
     * Revoke Connected App Token
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function revokeConnectedAppTokenAsync($session_id)
    {
        return $this->revokeConnectedAppTokenAsyncWithHttpInfo($session_id)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation revokeConnectedAppTokenAsyncWithHttpInfo
     *
     * Revoke Connected App Token
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function revokeConnectedAppTokenAsyncWithHttpInfo($session_id)
    {
        $returnType = '\Kinde\KindeSDK\Model\ApiResult';
        $request = $this->revokeConnectedAppTokenRequest($session_id);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'revokeConnectedAppToken'
     *
     * @param  string $session_id The unique sesssion id reprensenting the login session of a user. (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function revokeConnectedAppTokenRequest($session_id)
    {
        // verify the required parameter 'session_id' is set
        if ($session_id === null || (is_array($session_id) && count($session_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $session_id when calling revokeConnectedAppToken'
            );
        }

        $resourcePath = '/api/v1/connected_apps/revoke';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $session_id,
            'session_id', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);




        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/json'],
                []
            );
        }

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires Bearer (JWT) authentication (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        } else {
            $token = $this->storage->getAccessToken();
            if (!empty($token)) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}
