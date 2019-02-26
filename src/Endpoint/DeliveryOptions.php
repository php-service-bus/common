<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Endpoint;

/**
 *
 */
interface DeliveryOptions
{
    /**
     * Create options instance
     *
     * @return static
     */
    public static function create(): self;

    /**
     * Apply trace ID
     *
     * @param string|int|null $traceId
     *
     * @return void
     */
    public function withTraceId($traceId): void;

    /**
     * Apply headers
     *
     * @param string           $key
     * @param string|int|float $value
     *
     * @return void
     */
    public function withHeader(string $key, $value): void;

    /**
     * Receive trace id
     *
     * @return string|int|null
     */
    public function traceId();

    /**
     * Receive headers
     *
     * @psalm-return array<string, string|int|float>
     *
     * @return array
     */
    public function headers(): array;

    /**
     * Should the message be saved?
     *
     * @return bool
     */
    public function isPersistent(): bool;

    /**
     * Should the message be processed with the highest priority?
     *
     * @return bool
     */
    public function isHighestPriority(): bool;

    /**
     * After how many seconds the message will be marked as expired
     *
     * @return int|null
     */
    public function expirationAfter(): ?int;
}
