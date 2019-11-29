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

use ServiceBus\Common\MessageExecutor\MessageHandlerOptions;

/**
 * Message handler details.
 *
 * @psalm-readonly
 */
final class MessageHandler
{
    /**
     * Method name.
     */
    public string $methodName;

    /**
     * Does the method have arguments?
     */
    public bool $hasArguments;

    /**
     * Collection of arguments to the message handler.
     *
     * @psalm-var \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument, string>
     */
    public \SplObjectStorage $arguments;

    /**
     * Message class for which the handler was created.
     */
    public ?string $messageClass;

    /**
     * Handler return declaration.
     */
    public MessageHandlerReturnDeclaration $returnDeclaration;

    /**
     * Handler options.
     */
    public MessageHandlerOptions $options;

    /**
     * Execution closure.
     *
     * @psalm-var \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise
     */
    public \Closure $closure;

    /**
     * @psalm-param class-string $messageClass
     * @psalm-param \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
     *
     * @param string                $messageClass
     * @param \Closure              $closure
     * @param \ReflectionMethod     $reflectionMethod
     * @param MessageHandlerOptions $options
     *
     * @return self
     */
    public static function create(
        string $messageClass,
        \Closure $closure,
        \ReflectionMethod $reflectionMethod,
        MessageHandlerOptions $options
    ): self {
        return new self($messageClass, $closure, $options, $reflectionMethod);
    }

    /**
     * @psalm-param class-string $messageClass
     * @psalm-param \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
     *
     * @param string                $messageClass
     * @param \Closure              $closure
     * @param MessageHandlerOptions $options
     * @param \ReflectionMethod     $reflectionMethod
     */
    private function __construct(
        string $messageClass,
        \Closure $closure,
        MessageHandlerOptions $options,
        \ReflectionMethod $reflectionMethod
    ) {
        $this->closure           = $closure;
        $this->options           = $options;
        $this->methodName        = $reflectionMethod->getName();
        $this->messageClass      = $messageClass;
        $this->arguments         = $this->extractArguments($reflectionMethod);
        $this->hasArguments      = 0 !== \count($this->arguments);
        $this->returnDeclaration = $this->extractReturnDeclaration($reflectionMethod);
    }

    /**
     * Retrieves a collection of method arguments.
     *
     * @psalm-return \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument>
     */
    private function extractArguments(\ReflectionMethod $reflectionMethod): \SplObjectStorage
    {
        $argumentCollection = new \SplObjectStorage();

        $position = 1;

        foreach ($reflectionMethod->getParameters() as $parameter)
        {
            $argumentCollection->attach(MessageHandlerArgument::create($position, $parameter));

            ++$position;
        }

        /** @psalm-var \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument> $argumentCollection */

        return $argumentCollection;
    }

    /**
     * Retrieves a method return declaration.
     */
    private function extractReturnDeclaration(\ReflectionMethod $reflectionMethod): MessageHandlerReturnDeclaration
    {
        $returnDeclaration = $reflectionMethod->getReturnType();

        if ($returnDeclaration instanceof \ReflectionNamedType)
        {
            return MessageHandlerReturnDeclaration::create($returnDeclaration);
        }

        return MessageHandlerReturnDeclaration::createVoid();
    }
}
