<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\Common\MessageExecutor;

use ServiceBus\Common\MessageHandler\MessageHandler;

/**
 * Message handler creation factory.
 */
interface MessageExecutorFactory
{
    public function create(MessageHandler $messageHandler): MessageExecutor;
}
