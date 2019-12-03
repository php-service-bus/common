<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Context;

use Amp\Promise;
use Psr\Log\LogLevel;
use ServiceBus\Common\Endpoint\DeliveryOptions;

/**
 * Message execution context.
 */
interface ServiceBusContext
{
    /**
     * Is the received message correct?
     * If validation is not enabled in the handler parameters, it always returns true.
     */
    public function isValid(): bool;

    /**
     * If received message is incorrect, returns a list of violations.
     *
     * @psalm-return array<string, array<int, string>>
     *
     * [
     *    'propertyPath' => [
     *        0 => 'some message',
     *        ....
     *    ]
     * ]
     */
    public function violations(): array;

    /**
     * Enqueue message.
     *
     * @throws \ServiceBus\Common\Context\Exceptions\MessageDeliveryFailed
     */
    public function delivery(object $message, ?DeliveryOptions $deliveryOptions = null): Promise;

    /**
     * Return current message back to the queue.
     *
     * @throws \ServiceBus\Common\Context\Exceptions\MessageDeliveryFailed
     */
    public function return(int $secondsDelay = 3): Promise;

    /**
     * Log message with context details.
     */
    public function logContextMessage(
        string $logMessage,
        array $extra = [],
        string $level = LogLevel::INFO
    ): void;

    /**
     * Log Throwable in execution context.
     */
    public function logContextThrowable(
        \Throwable $throwable,
        array $extra = [],
        string $level = LogLevel::ERROR
    ): void;

    /**
     * Receive incoming operation id.
     */
    public function operationId(): string;

    /**
     * Receive trace message id.
     */
    public function traceId(): string;

    /**
     * Receive incoming message headers.
     *
     * @psalm-return array<string, string|float|int>
     */
    public function headers(): array;
}
