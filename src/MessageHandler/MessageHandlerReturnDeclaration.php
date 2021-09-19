<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\Common\MessageHandler;

use Amp\Promise;

/**
 * Handler return declaration.
 */
final class MessageHandlerReturnDeclaration
{
    /**
     * @psalm-readonly
     *
     * @var bool
     */
    public $isVoid;

    /**
     * @psalm-readonly
     *
     * @var bool
     */
    public $isPromise;

    /**
     * @psalm-readonly
     *
     * @var bool
     */
    public $isGenerator;

    public static function create(\ReflectionNamedType $reflectionType): self
    {
        $typeName = $reflectionType->getName();

        return new self(
            isVoid: $typeName === 'void',
            isPromise: $typeName === Promise::class,
            isGenerator: $typeName === \Generator::class
        );
    }

    public static function createVoid(): self
    {
        return new self(
            isVoid: true,
            isPromise: false,
            isGenerator: false
        );
    }

    private function __construct(bool $isVoid, bool $isPromise, bool $isGenerator)
    {
        $this->isVoid      = $isVoid;
        $this->isPromise   = $isPromise;
        $this->isGenerator = $isGenerator;
    }
}
