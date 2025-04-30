<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Responses\Contracts;

interface ResponseInterface
{

    /**
     * @param object $data
     * @return object
     */
    public static function fromObject(object $data): object;
}