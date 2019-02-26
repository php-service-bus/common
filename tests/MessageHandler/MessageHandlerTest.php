<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Tests\MessageHandler;

use Amp\Promise;
use Amp\Success;
use PHPUnit\Framework\TestCase;
use ServiceBus\Common\MessageExecutor\MessageHandlerOptions;
use ServiceBus\Common\MessageHandler\MessageHandler;

/**
 *
 */
final class MessageHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function voidReturnDeclaration(): void
    {
        $object = new class()
        {
            public function method(): void
            {

            }
        };

        $handler = MessageHandler::create(
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        static::assertNotNull($handler->returnDeclaration);
        static::assertTrue($handler->returnDeclaration->isVoid);

        static::assertFalse($handler->returnDeclaration->isGenerator);
        static::assertFalse($handler->returnDeclaration->isPromise);
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function noneReturnDeclaration(): void
    {
        $object = new class()
        {
            /** @noinspection ReturnTypeCanBeDeclaredInspection */
            public function method()
            {

            }
        };

        $handler = MessageHandler::create(
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        static::assertNotNull($handler->returnDeclaration);
    }


    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function promiseReturnDeclaration(): void
    {
        $object = new class()
        {
            public function method(): Promise
            {
                return new Success();
            }
        };

        $handler = MessageHandler::create(
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        static::assertTrue($handler->returnDeclaration->isPromise);
        static::assertFalse($handler->returnDeclaration->isGenerator);
        static::assertFalse($handler->returnDeclaration->isVoid);
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function generatorReturnDeclaration(): void
    {
        $object = new class()
        {
            public function method(): \Generator
            {
                yield from [];
            }
        };


        $handler = MessageHandler::create(
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        static::assertTrue($handler->returnDeclaration->isGenerator);
        static::assertFalse($handler->returnDeclaration->isPromise);
        static::assertFalse($handler->returnDeclaration->isVoid);
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function scalarReturnDeclaration(): void
    {
        $object = new class()
        {
            public function method(): string
            {
                return '';
            }
        };

        $handler = MessageHandler::create(
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        static::assertFalse($handler->returnDeclaration->isGenerator);
        static::assertFalse($handler->returnDeclaration->isPromise);
        static::assertFalse($handler->returnDeclaration->isVoid);
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function objectArgument(): void
    {
        $object = new class()
        {
            public function method(\stdClass $argument): string
            {
                return (string) $argument->qwerty;
            }
        };

        $handler = MessageHandler::create(
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        static::assertTrue($handler->hasArguments);
        static::assertCount(1, $handler->arguments);

        $args = \iterator_to_array($handler->arguments);

        /** @var \ServiceBus\Common\MessageHandler\MessageHandlerArgument $argument */
        $argument = \end($args);

        static::assertEquals('argument', $argument->argumentName);
        static::assertTrue($argument->hasType);
        static::assertEquals(\stdClass::class, $argument->typeClass);
        static::assertTrue($argument->isObject);
        static::assertTrue($argument->isA(\stdClass::class));
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function argumentWithoutTypeDeclaration(): void
    {
        $object = new class()
        {
            public function method($argument): \Generator
            {
                yield $argument;
            }
        };

        $handler = MessageHandler::create(
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        static::assertTrue($handler->hasArguments);
        static::assertCount(1, $handler->arguments);

        $args = \iterator_to_array($handler->arguments);

        /** @var \ServiceBus\Common\MessageHandler\MessageHandlerArgument $argument */
        $argument = \end($args);

        static::assertEquals('argument', $argument->argumentName);
        static::assertFalse($argument->hasType);
        static::assertNull($argument->typeClass);
        static::assertFalse($argument->isObject);
        static::assertFalse($argument->isA(\stdClass::class));
        static::assertSame(1, $argument->position);
    }

    /**
     * @return MessageHandlerOptions
     */
    private static function emptyOptions(): MessageHandlerOptions
    {
        return new class implements MessageHandlerOptions
        {

        };
    }

    /**
     * @return \Closure
     */
    private static function emptyClosure(): \Closure
    {
        return \Closure::fromCallable(
            static function(): void
            {

            }
        );
    }
}
