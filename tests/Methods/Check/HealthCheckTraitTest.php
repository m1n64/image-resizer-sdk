<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Methods\Check;

use GuzzleHttp\Psr7\Response;
use M1n64\ImageResizer\Client;
use M1n64\ImageResizer\Entities\Ping;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as Guzzle;

class HealthCheckTraitTest extends TestCase
{
    private Client $client;
    private Guzzle $mockHttp;

    protected function setUp(): void
    {
        $this->mockHttp = $this->createMock(Guzzle::class);
        $this->client = new Client();

        $ref = new \ReflectionProperty(Client::class, 'http');
        $ref->setAccessible(true);
        $ref->setValue($this->client, $this->mockHttp);
    }

    public function testPing()
    {
        $timestamp = (new \DateTimeImmutable())->format(DATE_ATOM);

        $this->mockHttp
            ->method('get')
            ->with('/ping')
            ->willReturn(new Response(200, [], json_encode([
                'message' => 'pong',
                'timestamp' => $timestamp,
            ])));

        $result = $this->client->ping();

        $this->assertInstanceOf(Ping::class, $result);
        $this->assertEquals('pong', $result->message);
        $this->assertEquals($timestamp, $result->timestamp->format(DATE_ATOM));
    }
}
