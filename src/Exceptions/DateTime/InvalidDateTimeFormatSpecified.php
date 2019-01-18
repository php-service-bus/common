<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Exceptions\DateTime;

/**
 *
 */
final class InvalidDateTimeFormatSpecified extends \InvalidArgumentException implements DateTimeExceptionMarker
{
    /**
     * @param string $format
     */
    public function __construct(string $format)
    {
        parent::__construct(
            \sprintf('Cannot display date in "%s" format', $format)
        );
    }
}
