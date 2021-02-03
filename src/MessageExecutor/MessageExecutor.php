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

/**
 * Message (event/command) handler.
 */
interface MessageExecutor
{
    /**
     * Handle message.
     *
     * @return Promise<void>
     *
     * @throws \Throwable
     * @throws \ServiceBus\Common\MessageExecutor\Exceptions\MessageExecutionFailed
     */
    public function __invoke(object $message, ServiceBusContext $context): Promise;
}
