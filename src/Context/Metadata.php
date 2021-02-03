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

/**
 * Message metadata.
 */
interface Metadata
{
    /**
     * @psalm-param array<string, string|int|float|bool|null> $variables
     */
    public static function create(string $messageId, string $traceId, array $variables): static;

    /**
     * Receive incoming message id.
     */
    public function messageId(): string;

    /**
     * Receive trace message id.
     */
    public function traceId(): string;

    /**
     * Has metadata key.
     */
    public function has(string $key): bool;

    /**
     * Receive message metadata value.
     */
    public function get(string $key, string|int|float|bool|null $default = null): string|int|float|bool|null;
}
