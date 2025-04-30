<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Entities;

use DateTime;

final class Ping
{
    /**
     * @param string $message
     * @param DateTime $timestamp
     */
    public function __construct(
        public string   $message,
        public DateTime $timestamp,
    )
    {
    }
}