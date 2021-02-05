<?php

declare(strict_types = 0);

namespace ServiceBus\Common\Context;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
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
