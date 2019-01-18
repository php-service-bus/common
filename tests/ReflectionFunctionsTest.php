<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Tests;

use function ServiceBus\Common\createWithoutConstructor;
use function ServiceBus\Common\invokeReflectionMethod;
use function ServiceBus\Common\readReflectionPropertyValue;
use function ServiceBus\Common\writeReflectionPropertyValue;
use PHPUnit\Framework\TestCase;

/**
 *
 */
final class ReflectionFunctionsTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function readPublicProperty(): void
    {
        static::assertEquals(
            'abube',
            readReflectionPropertyValue(
                new SecondClass(),
                'secondClassPublicValue'
            )
        );
    }

    /**
     * @test
     * @expectedException \ServiceBus\Common\Exceptions\Reflection\UnknownReflectionProperty
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function readUnknownProperty(): void
    {
        readReflectionPropertyValue(new SecondClass(), 'qwerty');
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function readAllProperties(): void
    {
        $object = new SecondClass();

        static::assertEquals(
            'abube',
            readReflectionPropertyValue($object, 'secondClassPublicValue')
        );

        static::assertEquals(
            'root',
            readReflectionPropertyValue($object, 'secondClassValue')
        );

        static::assertEquals(
            'qwerty',
            readReflectionPropertyValue($object, 'firstClassValue')
        );
    }

    /**
     * @test
     *
     * @return void
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
     * @expectedException \ServiceBus\Common\Exceptions\Reflection\InvokeReflectionMethodFailed
     *
     * @return void
     */
    public function invokeUnknownReflectionMethod(): void
    {
        invokeReflectionMethod(new SecondClass(), 'abube');
    }

    /**
     * @test
     *
     * @return void
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
     * @expectedException \ServiceBus\Common\Exceptions\Reflection\ReflectionClassNotFound
     *
     * @return void
     */
    public function createWithUnknownClass(): void
    {
        createWithoutConstructor(__METHOD__);
    }

    /**
     * @test
     *
     * @return void
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
