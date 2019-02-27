<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace ServiceBus\Common\Exceptions;

final class FileSystemException extends \RuntimeException
{
    /**
     * @codeCoverageIgnore
     *
     * @param string $filePath
     *
     * @return self
     */
    public static function getContentFailed(string $filePath): self
    {
        return new self(\sprintf('Failed to get the content of the file "%s"', $filePath));
    }

    /**
     * @param string $filePath
     *
     * @return self
     */
    public static function nonExistentFile(string $filePath): self
    {
        return new self(\sprintf('The file "%s" does not exist or is not available for reading', $filePath));
    }
}
