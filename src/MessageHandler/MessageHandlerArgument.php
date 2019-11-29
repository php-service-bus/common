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
 * @property string      $argumentName
 * @property bool        $hasType
 * @property string|null $argumentTypeClass
 * @property bool        $isObject
 * @property int         $position
 */
final class MessageHandlerArgument
{
    /**
     * Argument name.
     */
    public string $argumentName;

    /**
     * Does the argument have a type?
     */
    public bool $hasType;

    /**
     * If the argument type is an object, then the name of the class. Otherwise null.
     */
    public ?string $typeClass;

    /**
     * Is the argument an object?
     */
    public bool $isObject;

    /**
     * Argument position.
     */
    public int $position;

    private \ReflectionParameter $reflectionParameter;

    /**
     * @throws \LogicException Incorrect parameter type
     */
    public static function create(int $position, \ReflectionParameter $reflectionParameter): self
    {
        return new self($position, $reflectionParameter);
    }

    /**
     * Checks if the class is of this class or has this class as one of its parents.
     */
    public function isA(string $expectedClass): bool
    {
        if (true === $this->isObject)
        {
            /** @var \ReflectionClass $reflectionClass */
            $reflectionClass = $this->reflectionParameter->getClass();

            return \is_a($reflectionClass->getName(), $expectedClass, true);
        }

        return false;
    }

    private function __construct(int $position, \ReflectionParameter $reflectionParameter)
    {
        $this->reflectionParameter = $reflectionParameter;
        $this->argumentName        = $this->reflectionParameter->getName();
        $this->hasType             = true === \is_object($this->reflectionParameter->getType());
        $this->isObject            = $this->assertType('object');
        $this->position            = $position;
        $this->typeClass           = $this->getTypeClassName();
    }

    /**
     * If the argument is an object, returns its type.
     */
    private function getTypeClassName(): ?string
    {
        if (true === $this->isObject)
        {
            /** @var \ReflectionClass $reflectionClass */
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
        if (true === $this->hasType)
        {
            /** @var \ReflectionNamedType|\ReflectionType $type */
            $type = $this->reflectionParameter->getType();

            if (false === ($type instanceof \ReflectionNamedType))
            {
                throw new \LogicException(
                    \sprintf('Incorrect parameter "%s" type', $this->reflectionParameter->name)
                );
            }

            if (true === \class_exists($type->getName()) || true === \interface_exists($type->getName()))
            {
                return 'object' === $expectedType;
            }

            return $expectedType === $type->getName();
        }

        return false;
    }
}
