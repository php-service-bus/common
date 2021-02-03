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
    /** @test */
    public function readPublicProperty(): void
    {
        self::assertSame(
            'abube',
            readReflectionPropertyValue(
                new SecondClass(),
                'secondClassPublicValue'
            )
        );
    }

    /** @test */
    public function readUnknownProperty(): void
    {
        $this->expectException(ReflectionApiException::class);

        readReflectionPropertyValue(new SecondClass(), 'qwerty');
    }

    /** @test */
    public function readAllProperties(): void
    {
        $object = new SecondClass();

        self::assertSame(
            'abube',
            readReflectionPropertyValue($object, 'secondClassPublicValue')
        );

        self::assertSame(
            'root',
            readReflectionPropertyValue($object, 'secondClassValue')
        );

        self::assertSame(
            'qwerty',
            readReflectionPropertyValue($object, 'firstClassValue')
        );
    }

    /** @test */
    public function invokeReflectionMethod(): void
    {
        /** @var string $result */
        $result = invokeReflectionMethod(new SecondClass(), 'privateMethod', __METHOD__);

        self::assertSame(__METHOD__, $result);
    }

    /** @test */
    public function invokeUnknownReflectionMethod(): void
    {
        $this->expectException(ReflectionApiException::class);

        invokeReflectionMethod(new SecondClass(), 'abube');
    }

    /** @test */
    public function createWithoutConstructor(): void
    {
        $object = createWithoutConstructor(WithClosedConstructor::class);

        self::assertInstanceOf(WithClosedConstructor::class, $object);
    }

    /** @test */
    public function createWithUnknownClass(): void
    {
        $this->expectException(ReflectionApiException::class);

        createWithoutConstructor(__METHOD__);
    }

    /** @test */
    public function writeReflectionPropertyValue(): void
    {
        $object = new SecondClass();

        writeReflectionPropertyValue($object, 'secondClassValue', __METHOD__);

        self::assertSame(
            __METHOD__,
            readReflectionPropertyValue($object, 'secondClassValue')
        );
    }
}
