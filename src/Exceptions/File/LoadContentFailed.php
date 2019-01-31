<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Exceptions\File;

/**
 * @codeCoverageIgnore
 */
final class LoadContentFailed extends \RuntimeException
{
    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        parent::__construct(
            \sprintf(
                'Failed to get the content of the file "%s"', $filePath
            )
        );
    }
}
