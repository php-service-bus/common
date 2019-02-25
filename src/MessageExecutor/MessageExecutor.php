<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\MessageExecutor;

use Amp\Promise;
use ServiceBus\Common\Context\ServiceBusContext;

/**
 *
 */
interface MessageExecutor
{
    /**
     * Handle message
     *
     * @param object            $message
     * @param ServiceBusContext $context
     *
     * @return Promise It does not return any result
     *
     * @throws \Throwable
     */
    public function __invoke(object $message, ServiceBusContext $context): Promise;
}
