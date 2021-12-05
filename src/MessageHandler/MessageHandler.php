<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\Common\MessageHandler;

/**
 * Message handler details.
 */
final class MessageHandler
{
    /**
     * @psalm-readonly
     * @psalm-var non-empty-string
     *
     * @var string
     */
    public $methodName;

    /**
     * @psalm-readonly
     *
     * @var bool
     */
    public $hasArguments;

    /**
     * Collection of arguments to the message handler.
     *
     * @psalm-readonly
     *
     * @see MessageHandlerArgument
     *
     * @var \SplObjectStorage
     */
    public $arguments;

    /**
     * Message class for which the handler was created.
     *
     * @psalm-readonly
     * @psalm-var class-string
     *
     * @var string
     */
    public $messageClass;

    /**
     * @psalm-readonly
     *
     * @var MessageHandlerReturnDeclaration
     */
    public $returnDeclaration;

    /**
     * @psalm-readonly
     *
     * @var MessageHandlerOptions
     */
    public $options;

    /**
     * Execution closure.
     *
     * @psalm-readonly
     * @psalm-var \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise<void>
     *
     * @var \Closure
     */
    public $closure;

    /**
     * Message handler description.
     *
     * @psalm-readonly
     *
     * @var string|null
     */
    public $description;

    /**
     * @psalm-param class-string $messageClass
     * @psalm-param \Closure(object, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise<void> $closure
     *
     * @throws \RuntimeException Incorrect return type declaration.
     */
    public function __construct(
        string                $messageClass,
        \Closure              $closure,
        \ReflectionMethod     $reflectionMethod,
        MessageHandlerOptions $options,
        ?string               $description = null
    ) {
        $arguments = self::extractArguments($reflectionMethod);

        $this->closure           = $closure;
        $this->options           = $options;
        $this->methodName        = self::methodName($reflectionMethod);
        $this->messageClass      = $messageClass;
        $this->arguments         = $arguments;
        $this->hasArguments      = $arguments->count() !== 0;
        $this->returnDeclaration = self::extractReturnDeclaration($reflectionMethod);
        $this->description       = $description;
    }

    /**
     * @psalm-return non-empty-string
     */
    private static function methodName(\ReflectionMethod $reflectionMethod): string
    {
        $methodName = $reflectionMethod->getName();

        if ($methodName !== '')
        {
            return $methodName;
        }

        /** This cannot happen, but stubs do not support generic types. */
        throw new \LogicException('Incorrect argument name');
    }

    /**
     * Retrieves a collection of method arguments.
     */
    private static function extractArguments(\ReflectionMethod $reflectionMethod): \SplObjectStorage
    {
        /** @psalm-var \SplObjectStorage<MessageHandlerArgument, mixed> $argumentCollection */
        $argumentCollection = new \SplObjectStorage();

        /** @psalm-var positive-int $position */
        $position = 1;

        foreach ($reflectionMethod->getParameters() as $parameter)
        {
            $argumentCollection->attach(new MessageHandlerArgument($position, $parameter));

            ++$position;
        }

        return $argumentCollection;
    }

    /**
     * Retrieves a method return declaration.
     *
     * @throws \RuntimeException
     */
    private static function extractReturnDeclaration(\ReflectionMethod $reflectionMethod): MessageHandlerReturnDeclaration
    {
        $returnDeclaration = $reflectionMethod->getReturnType();

        if ($returnDeclaration instanceof \ReflectionUnionType)
        {
            throw new \RuntimeException('Union return types are not supported');
        }

        if ($returnDeclaration instanceof \ReflectionNamedType)
        {
            return MessageHandlerReturnDeclaration::create($returnDeclaration);
        }

        return MessageHandlerReturnDeclaration::createVoid();
    }
}
