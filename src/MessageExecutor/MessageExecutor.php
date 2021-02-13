<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 0);

namespace ServiceBus\Common\MessageExecutor;

use Amp\Promise;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Common\EntryPoint\Retry\RetryStrategy;

/**
 * Message (event/command) handler.
 */
interface MessageExecutor
{
    /**
     * Receive handler identifier.
     */
    public function id(): string;

    /**
     * Receive retry processor.
     */
    public function retryStrategy(): ?RetryStrategy;

    /**
     * Handle message.
     *
     * @return Promise<void>
     *
     * @throws \ServiceBus\Common\MessageExecutor\Exceptions\MessageExecutionFailed
     */
    public function __invoke(object $message, ServiceBusContext $context): Promise;
}
