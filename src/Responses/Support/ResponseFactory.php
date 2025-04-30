<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Responses\Support;

use M1n64\ImageResizer\Responses\Contracts\ResponseInterface;

final class ResponseFactory
{
    /**
     * @template T
     * @param class-string<ResponseInterface> $responseClass
     * @param object $data
     * @return object T
     */
    public static function make(string $responseClass, object $data): object
    {
        return $responseClass::fromObject($data);
    }
}