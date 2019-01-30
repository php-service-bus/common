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
 * @property-read string                                                                      $methodName
 * @property-read bool                                                                        $hasArguments
 * @property-read \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument> $arguments
 * @property-read MessageHandlerReturnDeclaration                                             $returnDeclaration
 * @property-read MessageHandlerOptions                                                       $options
 * @property-read string|null                                                                 $messageClass
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
     * Handler reflection method
     *
     * @var \ReflectionMethod
     */
    private $reflectionMethod;

    /**
     * @param \ReflectionMethod     $reflectionMethod
     * @param MessageHandlerOptions $options
     *
     * @return self
     */
    public static function create(\ReflectionMethod $reflectionMethod, MessageHandlerOptions $options): self
    {
        return new self($options, $reflectionMethod);
    }

    /**
     * Receive method as closure
     *
     * @param object|callable $objectOrCallable
     *
     * @return \Closure(\ServiceBus\Common\Messages\Message, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
     *
     * @throws \LogicException The argument must be either an object or a callback function
     */
    public function toClosure($objectOrCallable): \Closure
    {
        $isClosure = $objectOrCallable instanceof \Closure;

        if(true === \is_object($objectOrCallable) && false === $isClosure)
        {
            /** @var \Closure $closure */
            $closure = $this->reflectionMethod->getClosure($objectOrCallable);

            return $closure;
        }

        if(true === \is_callable($objectOrCallable))
        {
            return \Closure::fromCallable($objectOrCallable);
        }

        throw new \LogicException('The argument must be either an object or a callback function');
    }

    /**
     * @param MessageHandlerOptions $options
     * @param \ReflectionMethod     $reflectionMethod
     */
    private function __construct(MessageHandlerOptions $options, \ReflectionMethod $reflectionMethod)
    {
        $this->options           = $options;
        $this->reflectionMethod  = $reflectionMethod;
        $this->methodName        = $reflectionMethod->getName();
        $this->arguments         = self::extractArguments($reflectionMethod);
        $this->hasArguments      = 0 !== \count($this->arguments);
        $this->returnDeclaration = self::extractReturnDeclaration($reflectionMethod);
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return \SplObjectStorage<\ServiceBus\Common\MessageHandler\MessageHandlerArgument>
     */
    private static function extractArguments(\ReflectionMethod $reflectionMethod): \SplObjectStorage
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
    private static function extractReturnDeclaration(\ReflectionMethod $reflectionMethod): MessageHandlerReturnDeclaration
    {
        if(null !== $reflectionMethod->getReturnType())
        {
            /** @var \ReflectionType $returnDeclaration */
            $returnDeclaration = $reflectionMethod->getReturnType();

            return MessageHandlerReturnDeclaration::create($returnDeclaration);
        }

        return MessageHandlerReturnDeclaration::createVoid();
    }
}
