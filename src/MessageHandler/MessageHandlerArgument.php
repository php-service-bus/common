<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace ServiceBus\Common\MessageHandler;

/**
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
     *
     * @var string
     */
    public $argumentName;

    /**
     * Does the argument have a type?
     *
     * @var bool
     */
    public $hasType;

    /**
     * If the argument type is an object, then the name of the class. Otherwise null.
     *
     * @var string|null
     */
    public $typeClass;

    /**
     * Is the argument an object?
     *
     * @var bool
     */
    public $isObject;

    /**
     * Argument position.
     *
     * @var int
     */
    public $position;

    /**
     * @var \ReflectionParameter
     */
    private $reflectionParameter;

    /**
     * @param int                  $position
     * @param \ReflectionParameter $reflectionParameter
     *
     * @return self
     */
    public static function create(int $position, \ReflectionParameter $reflectionParameter): self
    {
        return new self($position, $reflectionParameter);
    }

    /**
     * Checks if the class is of this class or has this class as one of its parents.
     *
     * @param string $expectedClass
     *
     * @return bool
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

    /**
     * @param int                  $position
     * @param \ReflectionParameter $reflectionParameter
     */
    private function __construct(int $position, \ReflectionParameter $reflectionParameter)
    {
        $this->reflectionParameter = $reflectionParameter;
        $this->argumentName = $this->reflectionParameter->getName();
        $this->hasType = true === \is_object($this->reflectionParameter->getType());
        $this->isObject = $this->assertType('object');
        $this->position = $position;
        $this->typeClass = $this->getTypeClassName();
    }

    /**
     * @return string|null
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
     * @param string $expectedType
     *
     * @return bool
     */
    private function assertType(string $expectedType): bool
    {
        if (true === $this->hasType)
        {
            /** @var \ReflectionType $type */
            $type = $this->reflectionParameter->getType();

            if (true === \class_exists($type->getName()) || true === \interface_exists($type->getName()))
            {
                return 'object' === $expectedType;
            }

            return $expectedType === $type->getName();
        }

        return false;
    }
}
