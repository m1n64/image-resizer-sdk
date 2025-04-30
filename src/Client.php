<?php
declare(strict_types=1);

namespace M1n64\ImageResizer;

use GuzzleHttp\Client as Guzzle;
use M1n64\ImageResizer\Methods\Check\HealthCheckTrait;
use M1n64\ImageResizer\Methods\Images\ImagesTrait;

/**
 * Class Client
 *
 * @package M1n64\ImageResizer
 * @see https://github.com/m1n64/image-resizing-shared-service
 */
class Client
{
    use HealthCheckTrait, ImagesTrait;

    /**
     * @var Guzzle
     */
    protected Guzzle $http;


    /**
     * Client constructor.
     *
     * Initializes the HTTP client with the given base URL and optional API key.
     * Sets up default headers including 'Accept: application/json' and 'X-Api-Key' if provided.
     *
     * @param string|null $xApiKey Optional API key for authentication.
     * @param string $baseUrl Base URL for the API requests. Defaults to 'http://localhost:5689'.
     * @see https://github.com/m1n64/image-resizing-shared-service
     */
    public function __construct(
        protected string|null $xApiKey = null,
        protected string $baseUrl = 'http://localhost:5689',
    )
    {
        $headers = ['Accept' => 'application/json'];
        if ($xApiKey !== null) {
            $headers['X-Api-Key'] = $xApiKey;
        }

        $this->http = new Guzzle([
            'base_uri' => rtrim($baseUrl, '/'),
            'headers' => $headers,
            'http_errors' => false,
        ]);
    }
}