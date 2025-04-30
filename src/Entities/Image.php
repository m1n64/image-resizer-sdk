<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Entities;

use M1n64\ImageResizer\Enums\ImageStatusEnum;
use Ramsey\Uuid\UuidInterface;

final class Image
{

    /**
     * Constructs a new Image entity.
     *
     * @param UuidInterface $id Unique identifier for the image.
     * @param string $originalUrl URL of the original image.
     * @param string|null $compressedUrl URL of the compressed image (WebP), if available.
     * @param int $size Size of the original image in bytes.
     * @param string $mime MIME type of the original image.
     * @param ImageStatusEnum $status Current status of the image.
     * @param Thumbnail[] $thumbnails An array of thumbnails associated with the image.
     */
    public function __construct(
        public UuidInterface $id,
        public string $originalUrl,
        public string|null $compressedUrl,
        public int $size,
        public string $mime,
        public ImageStatusEnum $status,
        public array $thumbnails = [],
    )
    {
    }
}