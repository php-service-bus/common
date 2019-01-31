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
 *
 */
final class NonexistentFile extends \RuntimeException
{
    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        parent::__construct(
            \sprintf(
                'The file "%s" does not exist or is not available for reading', $filePath
            )
        );
    }
}
