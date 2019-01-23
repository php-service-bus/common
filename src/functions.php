<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common;

use ServiceBus\Common\Exceptions\Reflection\ReflectionClassNotFound;
use ServiceBus\Common\Exceptions\DateTime\CreateDateTimeFailed;
use ServiceBus\Common\Exceptions\DateTime\InvalidDateTimeFormatSpecified;
use ServiceBus\Common\Exceptions\Reflection\InvokeReflectionMethodFailed;
use ServiceBus\Common\Exceptions\Reflection\UnknownReflectionProperty;
use Ramsey\Uuid\Uuid;

/**
 * @noinspection PhpDocMissingThrowsInspection
 *
 * Generate a version 4 (random) UUID.
 *
 * @return string
 */
function uuid(): string
{
    /** @noinspection PhpUnhandledExceptionInspection */
    return Uuid::uuid4()->toString();
}

/**
 * Create datetime object from valid string
 *
 * @param string|null               $datetimeString
 * @param \DateTimeZone|string|null $timezone
 *
 * @return \DateTimeImmutable|null
 *
 * @throws \ServiceBus\Common\Exceptions\DateTime\CreateDateTimeFailed
 */
function datetimeInstantiator(?string $datetimeString, $timezone = null): ?\DateTimeImmutable
{
    if(null !== $datetimeString && '' !== $datetimeString)
    {
        try
        {
            if(true === \is_string($timezone) && '' !== $timezone)
            {
                $timezone = new \DateTimeZone($timezone);
            }

            /** @var \DateTimeZone|null $timezone */
            return new \DateTimeImmutable($datetimeString, $timezone);
        }
        catch(\Throwable $throwable)
        {
            throw new CreateDateTimeFailed($throwable->getMessage(), (int) $throwable->getCode(), $throwable);
        }
    }

    return null;
}

/**
 * Receive datetime as string representation (or null if not specified)
 *
 * @param \DateTimeInterface|null $dateTime
 * @param string                  $format
 *
 * @return string|null
 *
 * @throws \ServiceBus\Common\Exceptions\DateTime\InvalidDateTimeFormatSpecified
 */
function datetimeToString(?\DateTimeInterface $dateTime, string $format = 'Y-m-d H:i:s'): ?string
{
    if(null !== $dateTime)
    {
        /** @var string|false $result */
        $result = $dateTime->format($format);

        if(false !== $result && false !== \strtotime($result))
        {
            return $result;
        }

        throw new InvalidDateTimeFormatSpecified($format);
    }

    return null;
}

/**
 * @param object $object
 * @param string $methodName
 * @param mixed  ...$parameters
 *
 * @return mixed
 *
 * @throws \ServiceBus\Common\Exceptions\Reflection\InvokeReflectionMethodFailed
 */
function invokeReflectionMethod(object $object, string $methodName, ...$parameters)
{
    try
    {
        $reflectionMethod = new \ReflectionMethod($object, $methodName);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke($object, ...$parameters);
    }
    catch(\ReflectionException $exception)
    {
        throw new InvokeReflectionMethodFailed($exception->getMessage(), (int) $exception->getCode(), $exception);
    }
}

/**
 * Write value to property
 *
 * @param object $object
 * @param string $propertyName
 * @param mixed  $value
 *
 * @return void
 *
 * @throws \ServiceBus\Common\Exceptions\Reflection\UnknownReflectionProperty
 */
function writeReflectionPropertyValue(object $object, string $propertyName, $value): void
{
    $attribute = extractReflectionProperty($object, $propertyName);

    $attribute->setAccessible(true);
    $attribute->setValue($object, $value);
}

/**
 * Read property value
 *
 * @psalm-suppress MixedAssignment Mixed return data type
 *
 * @param object $object
 * @param string $propertyName
 *
 * @return mixed
 *
 * @throws \ServiceBus\Common\Exceptions\Reflection\UnknownReflectionProperty
 */
function readReflectionPropertyValue(object $object, string $propertyName)
{
    $attribute = extractReflectionProperty($object, $propertyName);

    $attribute->setAccessible(true);

    return $attribute->getValue($object);
}

/**
 * Extract property
 *
 * @internal
 *
 * @param object $object
 * @param string $propertyName
 *
 * @return \ReflectionProperty
 *
 * @throws \ServiceBus\Common\Exceptions\Reflection\UnknownReflectionProperty
 */
function extractReflectionProperty(object $object, string $propertyName): \ReflectionProperty
{
    try
    {
        return new \ReflectionProperty($object, $propertyName);
    }
    catch(\ReflectionException $e)
    {
        $reflector = new \ReflectionObject($object);

        /** @noinspection LoopWhichDoesNotLoopInspection */
        while($reflector = $reflector->getParentClass())
        {
            try
            {
                return $reflector->getProperty($propertyName);
            }
            catch(\Throwable $throwable)
            {
                /** Not interested */
            }
        }

        throw new UnknownReflectionProperty(
            \sprintf('Property "%s" not exists in "%s"', $propertyName, \get_class($object))
        );
    }
}

/**
 * @param string $class
 *
 * @return object
 *
 * @throws \ServiceBus\Common\Exceptions\Reflection\ReflectionClassNotFound
 */
function createWithoutConstructor(string $class): object
{
    try
    {
        return (new \ReflectionClass($class))->newInstanceWithoutConstructor();
    }
    catch(\Throwable $throwable)
    {
        throw new ReflectionClassNotFound($throwable->getMessage());
    }
}
