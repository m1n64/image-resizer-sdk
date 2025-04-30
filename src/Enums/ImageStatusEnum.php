<?php
declare(strict_types=1);

namespace M1n64\ImageResizer\Enums;

enum ImageStatusEnum: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Ready = 'ready';
    case Error = 'error';

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    /**
     * @return bool
     */
    public function isProcessing(): bool
    {
        return $this === self::Processing;
    }

    /**
     * @return bool
     */
    public function isReady(): bool
    {
        return $this === self::Ready;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this === self::Error;
    }
}
