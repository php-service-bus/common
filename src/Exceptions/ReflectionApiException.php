<?php

/**
 * PHP Service Bus common component.
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
final class ReflectionApiException extends \RuntimeException
{
    public static function fromThrowable(\Throwable $throwable): self
    {
        return new self($throwable->getMessage(), (int) $throwable->getCode(), $throwable);
    }

    public static function propertyNotExists(string $property, object $object): self
    {
        return new self(\sprintf('Property "%s" not exists in "%s"', $property, \get_class($object)));
    }

    public static function classNotExists(string $class): self
    {
        return new self(\sprintf('Class "%s" not exists', $class));
    }
}
