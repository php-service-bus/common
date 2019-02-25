<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Exceptions;

/**
 *
 */
final class DateTimeException extends \RuntimeException
{
    /**
     * @param \Throwable $throwable
     *
     * @return self
     */
    public static function fromThrowable(\Throwable $throwable): self
    {
        return new self($throwable->getMessage(), (int) $throwable->getCode(), $throwable);
    }

    /**
     * @param string $format
     *
     * @return self
     */
    public static function wrongFormat(string $format): self
    {
        return new self(\sprintf('Cannot display date in "%s" format', $format));
    }
}
