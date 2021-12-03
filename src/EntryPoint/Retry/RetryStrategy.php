<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\Common\EntryPoint\Retry;

use Amp\Promise;
use ServiceBus\Common\Context\ServiceBusContext;

interface RetryStrategy
{
    /**
     * @return Promise<void>
     */
    public function retry(object $message, ServiceBusContext $context, FailureContext $details): Promise;

    /**
     * @return Promise<void>
     */
    public function backoff(object $message, ServiceBusContext $context, FailureContext $details): Promise;
}
