<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\MessageHandler;

use Amp\Promise;

/**
 * Handler return declaration.
 *
 * @psalm-readonly
 */
final class MessageHandlerReturnDeclaration
{
    public bool $isVoid;

    public bool $isPromise;

    public bool $isGenerator;

    public static function create(\ReflectionNamedType $reflectionType): self
    {
        $typeName = $reflectionType->getName();

        $self = new self();

        $self->isVoid      = 'void' === $typeName;
        $self->isPromise   = Promise::class === $typeName;
        $self->isGenerator = \Generator::class === $typeName;

        return $self;
    }

    public static function createVoid(): self
    {
        $self = new self();

        $self->isVoid = true;

        return $self;
    }

    private function __construct()
    {
    }
}
