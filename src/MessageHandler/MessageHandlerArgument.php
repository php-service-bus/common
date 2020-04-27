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

/**
 * Handler argument information.
 *
 * @psalm-readonly
 */
final class MessageHandlerArgument
{
    /** @var string */
    public $argumentName;

    /** @var bool */
    public $hasType;

    /**
     * If the argument type is an object, then the name of the class. Otherwise null.
     *
     * @var string|null
     */
    public $typeClass = null;

    /** @var bool */
    public $isObject;

    /**
     * Argument position.
     *
     * @var int
     */
    public $position;

    /** @var \ReflectionParameter */
    private $reflectionParameter;

    public function __construct(int $position, \ReflectionParameter $reflectionParameter)
    {
        $this->reflectionParameter = $reflectionParameter;
        $this->argumentName        = $this->reflectionParameter->getName();
        $this->hasType             = \is_object($this->reflectionParameter->getType());
        $this->isObject            = $this->assertType('object');
        $this->position            = $position;
        $this->typeClass           = $this->getTypeClassName();
    }

    /**
     * Checks if the class is of this class or has this class as one of its parents.
     */
    public function isA(string $expectedClass): bool
    {
        if (true === $this->isObject)
        {
            /** @var \ReflectionObject $reflectionClass */
            $reflectionClass = $this->reflectionParameter->getClass();

            return \is_a($reflectionClass->getName(), $expectedClass, true);
        }

        return false;
    }

    /**
     * If the argument is an object, returns its type.
     */
    private function getTypeClassName(): ?string
    {
        if ($this->isObject === true)
        {
            /** @var \ReflectionObject $reflectionClass */
            $reflectionClass = $this->reflectionParameter->getClass();

            return $reflectionClass->getName();
        }

        return null;
    }

    /**
     * Compare argument types.
     *
     * @throws \LogicException Incorrect parameter type
     */
    private function assertType(string $expectedType): bool
    {
        if ($this->hasType === true)
        {
            /** @var \ReflectionNamedType|\ReflectionType $type */
            $type = $this->reflectionParameter->getType();

            if (($type instanceof \ReflectionNamedType) === false)
            {
                throw new \LogicException(
                    \sprintf('Incorrect parameter "%s" type', $this->reflectionParameter->name)
                );
            }

            if (\class_exists($type->getName()) === true || \interface_exists($type->getName()) === true)
            {
                return 'object' === $expectedType;
            }

            return $expectedType === $type->getName();
        }

        return false;
    }
}
