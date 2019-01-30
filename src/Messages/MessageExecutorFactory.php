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
     * Create message executor
     *
     * @return MessageExecutor
     */
    public function create(): MessageExecutor;
}
