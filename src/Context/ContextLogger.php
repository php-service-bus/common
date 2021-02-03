<?php

declare(strict_types = 1);

namespace ServiceBus\Common\Context;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 */
interface ContextLogger extends LoggerInterface
{
    /**
     * Log message with context details.
     */
    public function contextMessage(
        string $logMessage,
        array $extra = [],
        string $level = LogLevel::INFO
    ): void;

    /**
     * Log Throwable in execution context.
     */
    public function contextThrowable(
        \Throwable $throwable,
        array $extra = [],
        string $level = LogLevel::ERROR
    ): void;
}
