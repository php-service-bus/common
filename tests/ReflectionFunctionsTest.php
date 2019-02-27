<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace ServiceBus\Common\Tests;

use function ServiceBus\Common\createWithoutConstructor;
use function ServiceBus\Common\invokeReflectionMethod;
use function ServiceBus\Common\readReflectionPropertyValue;
use function ServiceBus\Common\writeReflectionPropertyValue;
use PHPUnit\Framework\TestCase;
use ServiceBus\Common\Exceptions\ReflectionApiException;

final class ReflectionFunctionsTest extends TestCase
{
    /**
     * @test
     *
     * @throws \Throwable
     */
    public function readPublicProperty(): void
    {
        static::assertSame(
            'abube',
            readReflectionPropertyValue(
                new SecondClass(),
                'secondClassPublicValue'
            )
        );
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function readUnknownProperty(): void
    {
        $this->expectException(ReflectionApiException::class);

        readReflectionPropertyValue(new SecondClass(), 'qwerty');
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function readAllProperties(): void
    {
        $object = new SecondClass();

        static::assertSame(
            'abube',
            readReflectionPropertyValue($object, 'secondClassPublicValue')
        );

        static::assertSame(
            'root',
            readReflectionPropertyValue($object, 'secondClassValue')
        );

        static::assertSame(
            'qwerty',
            readReflectionPropertyValue($object, 'firstClassValue')
        );
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function invokeReflectionMethod(): void
    {
        /** @var string $result */
        $result = invokeReflectionMethod(new SecondClass(), 'privateMethod', __METHOD__);

        static::assertSame(__METHOD__, $result);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function invokeUnknownReflectionMethod(): void
    {
        $this->expectException(ReflectionApiException::class);

        invokeReflectionMethod(new SecondClass(), 'abube');
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function createWithoutConstructor(): void
    {
        $object = createWithoutConstructor(WithClosedConstructor::class);

        static::assertInstanceOf(WithClosedConstructor::class, $object);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function createWithUnknownClass(): void
    {
        $this->expectException(ReflectionApiException::class);

        createWithoutConstructor(__METHOD__);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function writeReflectionPropertyValue(): void
    {
        $object = new SecondClass();

        writeReflectionPropertyValue($object, 'secondClassValue', __METHOD__);

        static::assertSame(
            __METHOD__,
            readReflectionPropertyValue($object, 'secondClassValue')
        );
    }
}
