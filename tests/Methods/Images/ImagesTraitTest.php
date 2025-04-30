<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Methods\Images;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Response;
use M1n64\ImageResizer\Client;
use M1n64\ImageResizer\Entities\Image;
use M1n64\ImageResizer\Methods\Images\ImagesTrait;
use M1n64\ImageResizer\Responses\ImageResponse;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ImagesTraitTest extends TestCase
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

    public function testUploadBinary()
    {
        $uuid = Uuid::uuid4()->toString();

        $mockBody = json_encode([
            'id' => $uuid,
            'original_url' => "/images/originals/{$uuid}.jpg",
            'compressed_url' => '',
            'size' => 1337,
            'mime' => 'image/jpeg',
            'status' => 'ready',
            'thumbnails' => [],
        ]);

        $this->mockHttp
            ->method('request')
            ->with('POST', '/image/upload/binary')
            ->willReturn(new Response(200, [], $mockBody));

        $tmpFile = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmpFile, file_get_contents(__DIR__ . '/../../fixtures/joe_peach_on_bike.jpg'));

        $result = $this->client->uploadBinary($tmpFile);

        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals($uuid, $result->id->toString());
        $this->assertEquals('image/jpeg', $result->mime);
        $this->assertTrue($result->status->isReady());

        unlink($tmpFile);
    }

    public function testUploadFromBlob()
    {
        $uuid = Uuid::uuid4()->toString();

        $mockBody = json_encode([
            'id' => $uuid,
            'original_url' => "/images/originals/{$uuid}.jpg",
            'compressed_url' => '',
            'size' => 2048,
            'mime' => 'image/png',
            'status' => 'processing',
            'thumbnails' => [],
        ]);

        $this->mockHttp
            ->method('request')
            ->with('POST', '/image/upload/binary')
            ->willReturn(new Response(200, [], $mockBody));

        $fakeImage = file_get_contents(__DIR__ . '/../../fixtures/joe_peach_on_bike.jpg');

        $result = $this->client->uploadFromBlob($fakeImage, 'test.jpg');

        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals('processing', $result->status->value);
    }

    public function testGet()
    {
        $uuid = Uuid::uuid4()->toString();

        $mockBody = json_encode([
            'id' => $uuid,
            'original_url' => "/images/originals/{$uuid}.jpg",
            'compressed_url' => '',
            'size' => 1024,
            'mime' => 'image/jpeg',
            'status' => 'ready',
            'thumbnails' => [],
        ]);

        $this->mockHttp
            ->method('request')
            ->with('GET', "/image/{$uuid}")
            ->willReturn(new Response(200, [], $mockBody));

        $result = $this->client->get($uuid);

        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals($uuid, $result->id->toString());
        $this->assertEquals('image/jpeg', $result->mime);
        $this->assertTrue($result->status->isReady());
    }

    public function testUpload()
    {
        $uuid = Uuid::uuid4()->toString();

        $mockBody = json_encode([
            'id' => $uuid,
            'original_url' => "/images/originals/{$uuid}.jpg",
            'compressed_url' => '',
            'size' => 999,
            'mime' => 'image/jpeg',
            'status' => 'pending',
            'thumbnails' => [],
        ]);

        $this->mockHttp
            ->method('request')
            ->with('POST', '/image/upload')
            ->willReturn(new Response(200, [], $mockBody));

        $tmpFile = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmpFile, file_get_contents(__DIR__ . '/../../fixtures/joe_peach_on_bike.jpg'));

        $result = $this->client->upload($tmpFile);

        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals($uuid, $result->id->toString());
        $this->assertEquals('pending', $result->status->value);

        unlink($tmpFile);
    }
}
