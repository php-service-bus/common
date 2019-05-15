<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace ServiceBus\Common\Context;

use Amp\Promise;
use Psr\Log\LogLevel;
use ServiceBus\Common\Endpoint\DeliveryOptions;

/**
 * Base context interface.
 */
interface ServiceBusContext
{
    /**
     * Is the received message correct?
     * If validation is not enabled in the handler parameters, it always returns true.
     *
     * @return bool
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
     *
     * @return array
     */
    public function violations(): array;

    /**
     * Enqueue message.
     *
     * @param object               $message
     * @param DeliveryOptions|null $deliveryOptions
     *
     * @return Promise It does not return any result
     */
    public function delivery(object $message, ?DeliveryOptions $deliveryOptions = null): Promise;

    /**
     * Log message with context details.
     *
     * @param string $logMessage
     * @param array  $extra
     * @param string $level
     *
     * @return void
     */
    public function logContextMessage(
        string $logMessage,
        array $extra = [],
        string $level = LogLevel::INFO
    ): void;

    /**
     * Log Throwable in execution context.
     *
     * @param \Throwable $throwable
     * @param array      $extra
     * @param string     $level
     *
     * @return void
     */
    public function logContextThrowable(
        \Throwable $throwable,
        string $level = LogLevel::ERROR,
        array $extra = []
    ): void;

    /**
     * Receive incoming operation id.
     *
     * @return string
     */
    public function operationId(): string;

    /**
     * Receive trace message id.
     *
     * @return string
     */
    public function traceId(): string;

    /**
     * Receive incoming message headers.
     *
     * @psalm-return array<string, string|float|int>
     *
     * @return array
     */
    public function headers(): array;
}
