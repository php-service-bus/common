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

use ServiceBus\Common\MessageExecutor\MessageHandlerOptions;

/**
 * @property string                          $methodName
 * @property bool                            $hasArguments
 * @property \SplObjectStorage               $arguments
 * @property MessageHandlerReturnDeclaration $returnDeclaration
 * @property MessageHandlerOptions           $options
 * @property string|null                     $messageClass
 * @property \Closure                        $closure
 */
final class MessageHandler
{
    /**
     * Method name.
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
     * Collection of arguments to the message handler.
     *
     * @psalm-var \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument, string>
     *
     * @var \SplObjectStorage
     */
    public $arguments;

    /**
     * Message class for which the handler was created.
     *
     * @var string|null
     */
    public $messageClass;

    /**
     * Handler return declaration.
     *
     * @var MessageHandlerReturnDeclaration
     */
    public $returnDeclaration;

    /**
     * Handler options.
     *
     * @var MessageHandlerOptions
     */
    public $options;

    /**
     * Execution closure.
     *
     * @psalm-var \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise
     *
     * @var \Closure
     */
    public $closure;

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
        $this->closure = $closure;
        $this->options = $options;
        $this->methodName = $reflectionMethod->getName();
        $this->messageClass = $messageClass;
        $this->arguments = $this->extractArguments($reflectionMethod);
        $this->hasArguments = 0 !== \count($this->arguments);
        $this->returnDeclaration = $this->extractReturnDeclaration($reflectionMethod);
    }

    /**
     * @psalm-return \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument>
     *
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return \SplObjectStorage
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
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return MessageHandlerReturnDeclaration
     */
    private function extractReturnDeclaration(\ReflectionMethod $reflectionMethod): MessageHandlerReturnDeclaration
    {
        $returnDeclaration = $reflectionMethod->getReturnType();

        if (null !== $returnDeclaration)
        {
            return MessageHandlerReturnDeclaration::create($returnDeclaration);
        }

        return MessageHandlerReturnDeclaration::createVoid();
    }
}
