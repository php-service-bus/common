<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Messages;

/**
 *
 */
interface MessageExecutorFactory
{
    /**
     * @param \Closure(\ServiceBus\Common\Messages\Message, \ServiceBus\Common\Context\ServiceBusContext):\Amp\Promise $closure
     * @param array<array-key, \ReflectionParameter> $arguments
     * @param MessageExecutorOptions $options
     *
     * @return MessageExecutor
     */
    public function create(\Closure $closure, array $arguments, MessageExecutorOptions $options): MessageExecutor;
}
