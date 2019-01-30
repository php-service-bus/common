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
use ServiceBus\Common\Messages\Message;

/**
 * @property-read string                                                                      $methodName
 * @property-read bool                                                                        $hasArguments
 * @property-read \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument> $arguments
 * @property-read MessageHandlerReturnDeclaration                                             $returnDeclaration
 * @property-read MessageHandlerOptions                                                       $options
 * @property-read string|null                                                                 $messageClass
 * @property-read \Closure(\ServiceBus\Common\Messages\Message, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
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
     * @var \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument>
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
     * @var \Closure(\ServiceBus\Common\Messages\Message, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise
     */
    public $closure;

    /**
     * @param \Closure(\ServiceBus\Common\Messages\Message, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
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
     * @param \Closure(\ServiceBus\Common\Messages\Message, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
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
     * @return \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument>
     */
    private function extractArguments(\ReflectionMethod $reflectionMethod): \SplObjectStorage
    {
        $argumentCollection = new \SplObjectStorage();

        foreach($reflectionMethod->getParameters() as $parameter)
        {
            $argumentCollection->attach(MessageHandlerArgument::create($parameter));
        }

        /** @var \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument> $argumentCollection */

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
        /** @var \ServiceBus\Common\MessageHandler\MessageHandlerArgument $argument */
        foreach($this->arguments as $argument)
        {
            if(true === $argument->isA(Message::class))
            {
                return (string) $argument->typeClass;
            }
        }

        return null;
    }
}
