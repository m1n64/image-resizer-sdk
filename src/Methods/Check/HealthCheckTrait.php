<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Methods\Check;

use GuzzleHttp\Exception\GuzzleException;
use M1n64\ImageResizer\Client;
use M1n64\ImageResizer\Entities\Ping;
use M1n64\ImageResizer\Exceptions\PingException;
use M1n64\ImageResizer\Responses\PingResponse;
use M1n64\ImageResizer\Responses\Support\ResponseFactory;

/**
 * @mixin Client
 */
trait HealthCheckTrait
{

    /**
     * Ping the server to check if it's healthy.
     *
     * @return Ping The server's response
     * @throws GuzzleException
     * @throws PingException If the server didn't respond with a 200 status code
     * @throws \DateMalformedStringException
     */
    public function ping(): Ping
    {
        $response = $this->http->get('/ping');

        if ($response->getStatusCode() !== 200) {
            throw new PingException("Method ping returned {$response->getStatusCode()}");
        }

        $data = json_decode($response->getBody()->getContents());

        return PingResponse::fromObject($data);
    }
}