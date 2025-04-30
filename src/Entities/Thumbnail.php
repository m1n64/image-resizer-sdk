<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Entities;

final class Thumbnail
{

    /**
     * @param string $size The size of the thumbnail (e.g. "256x256").
     * @param string $url The URL of the thumbnail.
     * @param string $type The MIME type of the thumbnail (e.g. "large").
     */
    public function __construct(
        public string $size,
        public string $url,
        public string $type,
    )
    {
    }
}