<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 0);

namespace ServiceBus\Common\Context;

use Amp\Promise;
use ServiceBus\Common\Endpoint\DeliveryOptions;

/**
 * Message execution context.
 */
interface ServiceBusContext
{
    /**
     * If received message is incorrect, returns a list of violations (In case validation check is enabled).
     */
    public function violations(): ?ValidationViolations;

    /**
     * Enqueue message.
     *
     * @return Promise<void>
     *
     * @throws \ServiceBus\Common\Context\Exceptions\MessageDeliveryFailed
     */
    public function delivery(
        object $message,
        ?DeliveryOptions $deliveryOptions = null,
        ?Metadata $withMetadata = null
    ): Promise;

    /**
     * Return current message back to the queue.
     *
     * @return Promise<void>
     *
     * @throws \ServiceBus\Common\Context\Exceptions\MessageDeliveryFailed
     */
    public function return(int $secondsDelay = 3, ?Metadata $withMetadata = null): Promise;

    /**
     * Receive context logger.
     */
    public function logger(): ContextLogger;

    /**
     * Receive incoming message headers.
     *
     * @psalm-return array<string, int|float|string|null>
     */
    public function headers(): array;

    /**
     * Receive message metadata.
     */
    public function metadata(): Metadata;
}
