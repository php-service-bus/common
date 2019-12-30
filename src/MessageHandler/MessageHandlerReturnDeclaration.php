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
 * @psalm-immutable
 */
final class MessageHandlerReturnDeclaration
{
    /** @var bool */
    public $isVoid;

    /** @var bool */
    public $isPromise;

    /** @var bool */
    public $isGenerator;

    public static function create(\ReflectionNamedType $reflectionType): self
    {
        $typeName = $reflectionType->getName();

        $self = new self();

        $self->isVoid      = $typeName === 'void';
        $self->isPromise   = $typeName === Promise::class;
        $self->isGenerator = $typeName === \Generator::class;

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
