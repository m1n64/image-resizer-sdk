<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Responses;

use DateTime;
use M1n64\ImageResizer\Entities\Ping;
use M1n64\ImageResizer\Responses\Contracts\ResponseInterface;

class PingResponse implements ResponseInterface
{
    /**
     * @param object $data
     * @return Ping
     * @throws \DateMalformedStringException
     */
    public static function fromObject(object $data): object
    {
        return new Ping(
            $data->message,
            new DateTime($data->timestamp),
        );
    }
}