<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\Common\Context;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Adds extra additional parameters to extra, depending on the current context.
 */
interface ContextLogger extends LoggerInterface
{
    /**
     * Log Throwable in execution context.
     */
    public function throwable(
        \Throwable $throwable,
        array $extra = [],
        string $level = LogLevel::ERROR
    ): void;
}
