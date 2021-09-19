<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace ServiceBus\Common\Tests\MessageHandler;

use Amp\Promise;
use Amp\Success;
use PHPUnit\Framework\TestCase;
use ServiceBus\Common\MessageHandler\MessageHandlerOptions;
use ServiceBus\Common\MessageHandler\MessageHandler;

final class MessageHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function withoutReturnDeclaration(): void
    {
        $object = new class()
        {
            public function method()
            {
            }
        };

        $handler = new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        self::assertNotNull($handler->returnDeclaration);
        self::assertTrue($handler->returnDeclaration->isVoid);

        self::assertFalse($handler->returnDeclaration->isGenerator);
        self::assertFalse($handler->returnDeclaration->isPromise);
    }

    /**
     * @test
     */
    public function voidReturnDeclaration(): void
    {
        $object = new class()
        {
            public function method(): void
            {
            }
        };

        $handler = new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        self::assertNotNull($handler->returnDeclaration);
        self::assertTrue($handler->returnDeclaration->isVoid);

        self::assertFalse($handler->returnDeclaration->isGenerator);
        self::assertFalse($handler->returnDeclaration->isPromise);
    }

    /**
     * @test
     */
    public function noneReturnDeclaration(): void
    {
        $object = new class()
        {
            public function method(): void
            {
            }
        };

        $handler = new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        self::assertNotNull($handler->returnDeclaration);
    }

    /**
     * @test
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

        $handler = new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        self::assertTrue($handler->returnDeclaration->isPromise);
        self::assertFalse($handler->returnDeclaration->isGenerator);
        self::assertFalse($handler->returnDeclaration->isVoid);
    }

    /**
     * @test
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

        $handler = new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        self::assertTrue($handler->returnDeclaration->isGenerator);
        self::assertFalse($handler->returnDeclaration->isPromise);
        self::assertFalse($handler->returnDeclaration->isVoid);
    }

    /**
     * @test
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

        $handler = new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        self::assertFalse($handler->returnDeclaration->isGenerator);
        self::assertFalse($handler->returnDeclaration->isPromise);
        self::assertFalse($handler->returnDeclaration->isVoid);
    }

    /**
     * @test
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

        $handler = new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        self::assertTrue($handler->hasArguments);
        self::assertCount(1, $handler->arguments);

        $args = \iterator_to_array($handler->arguments);

        /** @var \ServiceBus\Common\MessageHandler\MessageHandlerArgument $argument */
        $argument = \end($args);

        self::assertSame('argument', $argument->argumentName);
        self::assertTrue($argument->hasType);
        self::assertSame(\stdClass::class, $argument->typeClass);
        self::assertTrue($argument->isObject);
        self::assertTrue($argument->isA(\stdClass::class));
    }

    /**
     * @test
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

        $handler = new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );

        self::assertTrue($handler->hasArguments);
        self::assertCount(1, $handler->arguments);

        $args = \iterator_to_array($handler->arguments);

        /** @var \ServiceBus\Common\MessageHandler\MessageHandlerArgument $argument */
        $argument = \end($args);

        self::assertSame('argument', $argument->argumentName);
        self::assertFalse($argument->hasType);
        self::assertNull($argument->typeClass);
        self::assertFalse($argument->isObject);
        self::assertFalse($argument->isA(\stdClass::class));
        self::assertSame(1, $argument->position);
    }

    /**
     * @test
     */
    public function unionReturnTypeDeclaration(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Union return types are not supported');

        $object = new class()
        {
            public function method($argument): \Generator|Promise
            {
                yield $argument;
            }
        };

        new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );
    }

    /**
     * @test
     */
    public function argumentWitUnionTypeDeclaration(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Union types are not supported');

        $object = new class()
        {
            public function method(MessageHandler|MessageHandlerTest $argument): \Generator
            {
                yield $argument;
            }
        };

        new MessageHandler(
            \get_class($object),
            self::emptyClosure(),
            new \ReflectionMethod($object, 'method'),
            self::emptyOptions()
        );
    }

    private static function emptyOptions(): MessageHandlerOptions
    {
        return new class() implements MessageHandlerOptions
        {
        };
    }

    private static function emptyClosure(): \Closure
    {
        return \Closure::fromCallable(
            static function (): void
            {
            }
        );
    }
}
