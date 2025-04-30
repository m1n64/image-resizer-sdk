<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Responses;

use M1n64\ImageResizer\Entities\Image;
use M1n64\ImageResizer\Entities\Thumbnail;
use M1n64\ImageResizer\Enums\ImageStatusEnum;
use M1n64\ImageResizer\Responses\Contracts\ResponseInterface;
use Ramsey\Uuid\Uuid;

class ImageResponse implements ResponseInterface
{
    /**
     * @var string
     */
    private static string $baseUrl;

    /**
     * @param string $baseUrl
     * @return void
     */
    public static function setBaseUrl(string $baseUrl): void
    {
        self::$baseUrl = $baseUrl;
    }

    /**
     * @param object $data
     * @return Image
     */
    public static function fromObject(object $data): object
    {
        return new Image(
            id: Uuid::fromString($data->id),
            originalUrl: self::url($data->original_url),
            compressedUrl: isset($data->compressed_url) && $data->compressed_url !== ''
                ? self::url($data->compressed_url)
                : null,
            size: $data->size,
            mime: $data->mime,
            status: ImageStatusEnum::from($data->status),
            thumbnails: $data->thumbnails !== null
                ? array_map(fn($thumbnail) => new Thumbnail(
                    size: $thumbnail->size,
                    url: self::url($thumbnail->url),
                    type: $thumbnail->type,
                ), $data->thumbnails)
                : [],
        );
    }

    /**
     * @param string $path
     * @return string
     */
    private static function url(string $path): string
    {
        return self::$baseUrl . $path;
    }
}