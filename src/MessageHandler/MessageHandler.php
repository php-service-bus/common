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

use ServiceBus\Common\MessageExecutor\MessageHandlerOptions;

/**
 * @property-read string                          $methodName
 * @property-read bool                            $hasArguments
 * @property-read \SplObjectStorage               $arguments
 * @property-read MessageHandlerReturnDeclaration $returnDeclaration
 * @property-read MessageHandlerOptions           $options
 * @property-read string|null                     $messageClass
 * @property-read \Closure                        $closure
 */
final class MessageHandler
{
    /**
     * Method name
     *
     * @var string
     */
    public $methodName;

    /**
     * Does the method have arguments?
     *
     * @var bool
     */
    public $hasArguments;

    /**
     * Collection of arguments to the message handler
     *
     * @psalm-var \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument, string>
     * @var \SplObjectStorage
     */
    public $arguments;

    /**
     * Message class for which the handler was created
     *
     * @var string|null
     */
    public $messageClass;

    /**
     * Handler return declaration
     *
     * @var MessageHandlerReturnDeclaration
     */
    public $returnDeclaration;

    /**
     * Handler options
     *
     * @var MessageHandlerOptions
     */
    public $options;

    /**
     * Execution closure
     *
     * @psalm-var \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise
     * @var \Closure
     */
    public $closure;

    /**
     * @psalm-param \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
     *
     * @param \Closure              $closure
     * @param \ReflectionMethod     $reflectionMethod
     * @param MessageHandlerOptions $options
     *
     * @return self
     */
    public static function create(
        \Closure $closure,
        \ReflectionMethod $reflectionMethod,
        MessageHandlerOptions $options
    ): self
    {
        return new self($closure, $options, $reflectionMethod);
    }

    /**
     * @psalm-param \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
     *
     * @param \Closure              $closure
     * @param MessageHandlerOptions $options
     * @param \ReflectionMethod     $reflectionMethod
     */
    private function __construct(\Closure $closure, MessageHandlerOptions $options, \ReflectionMethod $reflectionMethod)
    {
        $this->closure           = $closure;
        $this->options           = $options;
        $this->methodName        = $reflectionMethod->getName();
        $this->arguments         = $this->extractArguments($reflectionMethod);
        $this->hasArguments      = 0 !== \count($this->arguments);
        $this->returnDeclaration = $this->extractReturnDeclaration($reflectionMethod);
        $this->messageClass      = $this->extractMessageClass();
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     *
     * @psalm-return \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument>
     * @return \SplObjectStorage
     */
    private function extractArguments(\ReflectionMethod $reflectionMethod): \SplObjectStorage
    {
        $argumentCollection = new \SplObjectStorage();

        $position = 1;

        foreach($reflectionMethod->getParameters() as $parameter)
        {
            $argumentCollection->attach(MessageHandlerArgument::create($position, $parameter));

            $position++;
        }

        /** @psalm-var \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument> $argumentCollection */

        return $argumentCollection;
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return MessageHandlerReturnDeclaration
     */
    private function extractReturnDeclaration(\ReflectionMethod $reflectionMethod): MessageHandlerReturnDeclaration
    {
        if(null !== $reflectionMethod->getReturnType())
        {
            /** @var \ReflectionType $returnDeclaration */
            $returnDeclaration = $reflectionMethod->getReturnType();

            return MessageHandlerReturnDeclaration::create($returnDeclaration);
        }

        return MessageHandlerReturnDeclaration::createVoid();
    }

    /**
     * @return string|null
     */
    private function extractMessageClass(): ?string
    {
        $this->arguments->rewind();

        /** @var \ServiceBus\Common\MessageHandler\MessageHandlerArgument|null $firstArgument */
        $firstArgument = $this->arguments->current();

        if(null !== $firstArgument && true === $firstArgument->isObject)
        {
            return (string) $firstArgument->typeClass;
        }

        return null;
    }
}
