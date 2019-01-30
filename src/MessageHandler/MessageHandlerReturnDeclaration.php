<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\MessageHandler;

use Amp\Promise;

/**
 * Handler return declaration
 *
 * @property-read bool $isVoid
 * @property-read bool $isPromise
 * @property-read bool $isGenerator
 */
final class MessageHandlerReturnDeclaration
{
    /**
     * @var bool
     */
    public $isVoid;

    /**
     * @var bool
     */
    public $isPromise;

    /**
     * @var bool
     */
    public $isGenerator;

    /**
     * @param \ReflectionType $reflectionType
     *
     * @return self
     */
    public static function create(\ReflectionType $reflectionType): self
    {
        return new self($reflectionType);
    }

    /**
     * @return self
     */
    public static function createVoid(): self
    {
        return new self(null);
    }

    /**
     * @param \ReflectionType|null $reflectionType
     */
    private function __construct(?\ReflectionType $reflectionType)
    {
        if(null !== $reflectionType)
        {
            $this->isVoid      = 'void' === $reflectionType->getName();
            $this->isPromise   = Promise::class === $reflectionType->getName();
            $this->isGenerator = \Generator::class === $reflectionType->getName();
        }
        else
        {
            $this->isVoid = true;
        }
    }
}
