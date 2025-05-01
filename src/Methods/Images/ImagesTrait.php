<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Methods\Images;

use finfo;
use GuzzleHttp\Exception\GuzzleException;
use M1n64\ImageResizer\Client;
use M1n64\ImageResizer\Entities\Image;
use M1n64\ImageResizer\Responses\ImageResponse;
use Ramsey\Uuid\UuidInterface;

/**
 * @mixin Client
 */
trait ImagesTrait
{
    /** @var string */
    private static string $urlPath= '/image';

    /**
     * Uploads an image file to the ImageResizer server.
     *
     * @param string $filePath The path to the image file to upload.
     * @return Image The uploaded image.
     * @throws GuzzleException
     */
    public function upload(string $filePath): Image
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File not found: {$filePath}");
        }

        $response = $this->http->request('POST', self::$urlPath . '/upload', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath),
                ],
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        return $this->sendResponse($data);
    }

    /**
     * Uploads an image file to the ImageResizer server as a binary request.
     *
     * @param string $filePath The path to the image file to upload.
     * @return Image The uploaded image.
     * @throws GuzzleException
     */
    public function uploadBinary(string $filePath): Image
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File not found: {$filePath}");
        }

        $mime = mime_content_type($filePath);

        $response = $this->http->request('POST', self::$urlPath . '/upload/binary', [
            'headers' => [
                'Content-Type' => $mime,
            ],
            'body' => fopen($filePath, 'rb'),
        ]);

        $data = json_decode($response->getBody()->getContents());

        return $this->sendResponse($data);
    }

    /**
     * Uploads an image from a binary data blob to the ImageResizer server.
     *
     * @param string $data The binary data of the image to upload.
     * @param string|null $filename Optional filename for the uploaded image.
     * @return Image The uploaded image.
     * @throws \InvalidArgumentException If the MIME type of the data is not an image.
     * @throws GuzzleException If the HTTP request fails.
     */
    public function uploadFromBlob(string $data, string|null $filename = null): Image
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($data);

        if (!str_starts_with($mime, 'image/')) {
            throw new \InvalidArgumentException("Invalid image MIME type: {$mime}");
        }

        $headers = [
            'Content-Type' => $mime,
        ];

        if ($filename) {
            $headers['X-File-Name'] = $filename;
        }

        $response = $this->http->request('POST', self::$urlPath . '/upload/binary', [
            'headers' => $headers,
            'body' => $data,
        ]);

        $data = json_decode($response->getBody()->getContents());

        return $this->sendResponse($data);
    }

    /**
     * Gets an image by ID.
     *
     * @param string|UuidInterface $id The ID of the image to get.
     * @return Image The image with the specified ID.
     * @throws GuzzleException If the HTTP request fails.
     */
    public function get(string|UuidInterface $id): Image
    {
        $id = is_string($id) ? $id : $id->toString();
        $response = $this->http->request('GET', self::$urlPath . "/{$id}");

        $data = json_decode($response->getBody()->getContents());

        return $this->sendResponse($data);
    }

    /**
     * @param object $data
     * @return Image
     */
    private function sendResponse(object $data): Image
    {
        ImageResponse::setBaseUrl($this->publicBaseUrl);
        return ImageResponse::fromObject($data);
    }
}