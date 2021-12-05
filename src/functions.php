<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\Common;

use ServiceBus\Common\Exceptions\DateTimeException;
use ServiceBus\Common\Exceptions\FileSystemException;
use ServiceBus\Common\Exceptions\JsonSerializationFailed;
use ServiceBus\Common\Exceptions\ReflectionApiException;
use Symfony\Component\Uid\Uuid;

/**
 * Generate a version 4 (random) UUID.
 *
 * @psalm-return non-empty-string
 *
 * @noinspection PhpUnhandledExceptionInspection
 */
function uuid(): string
{
    $uuid    = \random_bytes(16);
    $uuid[6] = $uuid[6] & "\x0F" | "\x4F";
    $uuid[8] = $uuid[8] & "\x3F" | "\x80";
    $uuid    = \bin2hex($uuid);

    return \substr($uuid, 0, 8) .
        '-' .
        \substr($uuid, 8, 4) . '-' .
        \substr($uuid, 12, 4) . '-' .
        \substr($uuid, 16, 4) . '-' .
        \substr($uuid, 20, 12);
}

/**
 * Is the string a valid UUID.
 */
function isUuid(string $string): bool
{
    return Uuid::isValid($string);
}

/**
 * Create datetime object from valid string.
 *
 * @throws \ServiceBus\Common\Exceptions\DateTimeException
 */
function datetimeInstantiator(?string $datetimeString, \DateTimeZone|string $timezone = null): ?\DateTimeImmutable
{
    if ($datetimeString !== null && $datetimeString !== '')
    {
        try
        {
            $timezone = timezoneFactory($timezone);

            return new \DateTimeImmutable($datetimeString, $timezone);
        }
        catch (\Throwable $throwable)
        {
            throw DateTimeException::fromThrowable($throwable);
        }
    }

    return null;
}

/**
 * Create current datetime.
 */
function now(\DateTimeZone|string $timezone = null): \DateTimeImmutable
{
    $timezone = timezoneFactory($timezone);

    /** @var \DateTimeImmutable $datetime */
    $datetime = \DateTimeImmutable::createFromFormat('0.u00 U', \microtime());

    if ($timezone !== null)
    {
        /** @var \DateTimeImmutable $datetime */
        $datetime = $datetime->setTimezone($timezone);
    }

    return $datetime;
}

/**
 * @internal
 */
function timezoneFactory(\DateTimeZone|string $timezone = null): ?\DateTimeZone
{
    if (\is_string($timezone) && $timezone !== '')
    {
        $timezone = new \DateTimeZone($timezone);
    }

    /** @var \DateTimeZone|null $timezone */

    return $timezone;
}

/**
 * Receive datetime as string representation (or null if not specified).
 *
 * @throws \ServiceBus\Common\Exceptions\DateTimeException
 */
function datetimeToString(?\DateTimeInterface $dateTime, ?string $format = null): ?string
{
    $format = $format ?? 'Y-m-d H:i:s';

    if ($dateTime !== null)
    {
        /** @var false|string $result */
        $result = $dateTime->format($format);

        if ($result !== false && \strtotime($result) !== false)
        {
            return $result;
        }

        throw DateTimeException::wrongFormat($format);
    }

    return null;
}

/**
 * @param mixed ...$parameters
 *
 * @throws \ServiceBus\Common\Exceptions\ReflectionApiException
 */
function invokeReflectionMethod(object $object, string $methodName, ...$parameters): mixed
{
    try
    {
        $reflectionMethod = new \ReflectionMethod($object, $methodName);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke($object, ...$parameters);
    }
    catch (\ReflectionException $exception)
    {
        throw ReflectionApiException::fromThrowable($exception);
    }
}

/**
 * Write value to property.
 *
 * @psalm-param non-empty-string $propertyName
 *
 * @throws \ServiceBus\Common\Exceptions\ReflectionApiException
 */
function writeReflectionPropertyValue(object $object, string $propertyName, mixed $value): void
{
    $attribute = extractReflectionProperty($object, $propertyName);

    $attribute->setAccessible(true);
    $attribute->setValue($object, $value);
}

/**
 * Read property value.
 *
 * @psalm-param non-empty-string $propertyName
 *
 * @throws \ServiceBus\Common\Exceptions\ReflectionApiException
 */
function readReflectionPropertyValue(object $object, string $propertyName): mixed
{
    $attribute = extractReflectionProperty($object, $propertyName);

    $attribute->setAccessible(true);

    return $attribute->getValue($object);
}

/**
 * Extract property.
 *
 * @psalm-param non-empty-string $propertyName
 *
 * @throws \ServiceBus\Common\Exceptions\ReflectionApiException
 */
function extractReflectionProperty(object $object, string $propertyName): \ReflectionProperty
{
    try
    {
        return new \ReflectionProperty($object, $propertyName);
    }
    catch (\ReflectionException)
    {
        $reflector = new \ReflectionObject($object);

        // @noinspection LoopWhichDoesNotLoopInspection
        while ($reflector = $reflector->getParentClass())
        {
            try
            {
                return $reflector->getProperty($propertyName);
            }
            catch (\Throwable)
            {
                // Not interested
            }
        }

        throw ReflectionApiException::propertyNotExists($propertyName, $object);
    }
}

/**
 * @template T of object
 * @psalm-param class-string<T> $class
 * @psalm-return T
 *
 * @throws \ServiceBus\Common\Exceptions\ReflectionApiException
 */
function createWithoutConstructor(string $class): object
{
    try
    {
        return (new \ReflectionClass($class))->newInstanceWithoutConstructor();
    }
    catch (\Throwable)
    {
        throw ReflectionApiException::classNotExists($class);
    }
}

/**
 * Reads entire file into a string.
 *
 * @psalm-param non-empty-string $filePath
 *
 * @throws \ServiceBus\Common\Exceptions\FileSystemException
 */
function fileGetContents(string $filePath): string
{
    if (\file_exists($filePath) === false || \is_readable($filePath) === false)
    {
        throw FileSystemException::nonExistentFile($filePath);
    }

    $fileContents = \file_get_contents($filePath);

    // @codeCoverageIgnoreStart
    if ($fileContents === false)
    {
        throw FileSystemException::getContentFailed($filePath);
    }

    // @codeCoverageIgnoreEnd

    return $fileContents;
}

/**
 * Extract namespace from file content.
 *
 * @psalm-param non-empty-string $filePath
 *
 * @throws \ServiceBus\Common\Exceptions\FileSystemException
 */
function extractNamespaceFromFile(string $filePath): ?string
{
    $fileContents = fileGetContents($filePath);

    if (\preg_match('#^namespace\s+(.+?);$#sm', $fileContents, $matches) !== false && isset($matches[1]))
    {
        $fileName = \pathinfo($filePath)['filename'];

        return \sprintf('%s\\%s', $matches[1], $fileName);
    }

    return null;
}

/**
 * Recursive search of all files in the directory.
 * Search for files matching the specified regular expression.
 *
 * @psalm-param array<array-key, non-empty-string> $directories
 *
 * @psalm-return \Generator<\SplFileInfo>
 */
function searchFiles(array $directories, string $regExp): \Generator
{
    foreach ($directories as $directory)
    {
        $iterator = new \RegexIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory)
            ),
            $regExp
        );

        /** @var \SplFileInfo $fileInfo */
        foreach ($iterator as $fileInfo)
        {
            yield $fileInfo;
        }
    }
}

/**
 * Casting paths to canonical form.
 *
 * @psalm-param  array<array-key, non-empty-string> $paths
 *
 * @psalm-return array<int, string>
 */
function canonicalizeFilesPath(array $paths): array
{
    $result = [];

    foreach ($paths as $path)
    {
        $result[] = (string) (new \SplFileInfo($path))->getRealPath();
    }

    return $result;
}

/**
 * Formats bytes into a human readable string.
 *
 * @psalm-return non-empty-string
 */
function formatBytes(int $bytes): string
{
    if (1024 * 1024 < $bytes)
    {
        /** @psalm-suppress InvalidOperand */
        return \sprintf('%.2f mb', $bytes / 1024 / 1024);
    }

    if (1024 < $bytes)
    {
        return \sprintf('%.2f kb', $bytes / 1024);
    }

    return \sprintf('%d b', $bytes);
}

/**
 * Collect all throwable information (include previous).
 *
 * @psalm-return array{
 *     throwableMessage:string,
 *     throwablePoint:non-empty-string,
 *     throwablePrevious:array<array-key, array{throwableMessage:string, throwablePoint:non-empty-string}>
 * }
 */
function throwableDetails(\Throwable $throwable): array
{
    $throwableFormatter = static function (\Throwable $throwable): array
    {
        return [
            'throwableMessage' => $throwable->getMessage(),
            'throwablePoint'   => \sprintf('%s:%d', $throwable->getFile(), $throwable->getLine()),
        ];
    };

    $result = $throwableFormatter($throwable);

    $result['throwablePrevious'] = [];

    if ($previous = $throwable->getPrevious())
    {
        do
        {
            $result['throwablePrevious'][] = $throwableFormatter($previous);
        }
        while ($previous = $previous->getPrevious());
    }

    return $result;
}

/**
 * Receives the exception message (including nested exceptions).
 */
function throwableMessage(\Throwable $throwable): string
{
    $message = $throwable->getMessage();

    if ($previous = $throwable->getPrevious())
    {
        $messages = [];

        do
        {
            $messages[] = $previous->getMessage();
        }
        while ($previous = $previous->getPrevious());

        $message .= \sprintf(' (Previous: %s)', \implode('; ', $messages));
    }

    return $message;
}

/**
 * @throws \ServiceBus\Common\Exceptions\JsonSerializationFailed
 *
 * @psalm-return non-empty-string
 */
function jsonEncode(array $data): string
{
    try
    {
        /** @psalm-var non-empty-string $result */
        $result = \json_encode(
            $data,
            \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE | \JSON_THROW_ON_ERROR | \JSON_PRESERVE_ZERO_FRACTION
        );

        return $result;
    }
    catch (\Throwable $throwable)
    {
        throw new JsonSerializationFailed($throwable->getMessage(), (int) $throwable->getCode(), $throwable);
    }
}

/**
 * @psalm-param non-empty-string $json
 *
 * @throws \ServiceBus\Common\Exceptions\JsonSerializationFailed
 */
function jsonDecode(string $json): array
{
    try
    {
        /** @var array $data */
        $data = \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);

        return $data;
    }
    catch (\Throwable $throwable)
    {
        throw new JsonSerializationFailed($throwable->getMessage(), (int) $throwable->getCode(), $throwable);
    }
}
