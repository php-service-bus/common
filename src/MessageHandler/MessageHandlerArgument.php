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
        if ($this->isObject)
        {
            /** @var \ReflectionType $reflectionType */
            $reflectionType = $this->reflectionParameter->getType();

            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            return \is_a($reflectionType->getName(), $expectedClass, true);
        }

        return false;
    }

    /**
     * If the argument is an object, returns its type.
     */
    private function getTypeClassName(): ?string
    {
        if ($this->isObject)
        {
            /** @var \ReflectionType $reflectionType */
            $reflectionType = $this->reflectionParameter->getType();

            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            return $reflectionType->getName();
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
        if ($this->hasType)
        {
            /** @var \ReflectionNamedType|\ReflectionType $type */
            $type = $this->reflectionParameter->getType();

            if (($type instanceof \ReflectionNamedType) === false)
            {
                throw new \LogicException(
                    \sprintf('Incorrect parameter `%s` type', $this->reflectionParameter->name)
                );
            }

            if (\class_exists($type->getName()) || \interface_exists($type->getName()))
            {
                return  $expectedType === 'object';
            }

            return $expectedType === $type->getName();
        }

        return false;
    }
}
