<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\MessageExecutor;

use ServiceBus\Common\MessageHandler\MessageHandler;

/**
 * Message handler creation factory.
 */
interface MessageExecutorFactory
{
    public function create(MessageHandler $messageHandler): MessageExecutor;
}
