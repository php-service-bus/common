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

/**
 * Received message metadata.
 */
interface IncomingMessageMetadata
{
    /**
     * Receive message id.
     */
    public function messageId(): string;

    /**
     * Receive trace message id.
     */
    public function traceId(): string;

    /**
     * Receive variables.
     *
     * @psalm-return array<string, string|int|float|bool|null>
     */
    public function variables(): array;

    /**
     * Has metadata key.
     */
    public function has(string $key): bool;

    /**
     * Receive message metadata value.
     */
    public function get(string $key, string|int|float|bool|null $default = null): string|int|float|bool|null;
}
