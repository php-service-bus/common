<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 0);

namespace ServiceBus\Common\MessageHandler;

/**
 * Handler argument information.
 */
final class MessageHandlerArgument
{
    /**
     * @psalm-readonly
     *
     * @var string
     */
    public $argumentName;

    /**
     * @psalm-readonly
     *
     * @var bool
     */
    public $hasType;

    /**
     * If the argument type is an object, then the name of the class. Otherwise null.
     *
     * @psalm-readonly
     *
     * @var string|null
     */
    public $typeClass;

    /**
     * @psalm-readonly
     *
     * @var bool
     */
    public $isObject;

    /**
     * Argument position.
     *
     * @psalm-readonly
     *
     * @var int
     */
    public $position;

    /**
     * @psalm-readonly
     *
     * @var \ReflectionParameter
     */
    private $reflectionParameter;

    /**
     * @throws \LogicException Incorrect parameter type.
     */
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
     *
     * @throws \LogicException Incorrect parameter type.
     */
    public function isA(string $expectedClass): bool
    {
        if ($this->isObject)
        {
            return \is_a($this->reflectionType()->getName(), $expectedClass, true);
        }

        return false;
    }

    /**
     * If the argument is an object, returns its type.
     *
     * @throws \LogicException Incorrect parameter type.
     */
    private function getTypeClassName(): ?string
    {
        if ($this->isObject)
        {
            return $this->reflectionType()->getName();
        }

        return null;
    }

    /**
     * Compare argument types.
     *
     * @throws \LogicException Incorrect parameter type.
     */
    private function assertType(string $expectedType): bool
    {
        if ($this->hasType)
        {
            $type = $this->reflectionType();

            if (\class_exists($type->getName()) || \interface_exists($type->getName()))
            {
                return $expectedType === 'object';
            }

            return $expectedType === $type->getName();
        }

        return false;
    }

    /**
     * @return \ReflectionNamedType Incorrect or unsupported argument type.
     */
    private function reflectionType(): \ReflectionNamedType
    {
        $reflectionType = $this->reflectionParameter->getType();

        if ($reflectionType instanceof \ReflectionUnionType)
        {
            throw new \RuntimeException('Union types are not supported');
        }

        if ($reflectionType instanceof \ReflectionNamedType)
        {
            return $reflectionType;
        }

        throw new \RuntimeException(\sprintf('Incorrect `%s` argument type', $this->reflectionParameter->name));
    }
}
