<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 0);

namespace ServiceBus\Common\Endpoint;

/**
 * Message delivery options.
 */
interface DeliveryOptions
{
    /**
     * Create options instance.
     */
    public static function create(): self;

    /**
     * Apply headers.
     */
    public function withHeader(string $key, int|float|string|null $value): self;

    /**
     * Receive headers.
     *
     * @psalm-return array<string, int|float|string|null>
     */
    public function headers(): array;

    /**
     * Should the message be saved?
     */
    public function isPersistent(): bool;

    /**
     * Should the message be processed with the highest priority?
     */
    public function isHighestPriority(): bool;

    /**
     * After how many seconds the message will be marked as expired.
     */
    public function expirationAfter(): ?int;
}
