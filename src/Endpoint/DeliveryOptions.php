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
     * @param string                          $key
     * @param array<string, string|int|float> $value
     *
     * @return void
     */
    public function withHeader(string $key, $value): void;

    /**
     * Receive trace id
     *
     * @return string|int|float
     */
    public function traceId();

    /**
     * Receive headers
     *
     * @return array<string, string|int|float>
     */
    public function headers(): array;
}
