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
    public const SERVICE_BUS_ACTOR_EXTERNAL  = 'external';
    public const SERVICE_BUS_ACTOR_HANDLER   = 'internalHandler';
    public const SERVICE_BUS_ACTOR_AGGREGATE = 'internalAggregate';
    public const SERVICE_BUS_ACTOR_SAGA      = 'internalSaga';

    public const SERVICE_BUS_TRACE_ID            = 'X-SERVICE-BUS-TRACE-ID';
    public const SERVICE_BUS_SERIALIZER_TYPE     = 'X-SERVICE-BUS-ENCODER';
    public const SERVICE_BUS_MESSAGE_TYPE        = 'X-SERVICE-BUS-MESSAGE-TYPE';
    public const SERVICE_BUS_MESSAGE_RETRY_COUNT = 'X-SERVICE-BUS-RETRY-COUNT';
    public const SERVICE_BUS_MESSAGE_ACTOR       = 'X-SERVICE-BUS-ACTOR_TYPE';

    /**
     * @psalm-param array<string, string|int|float|bool|null> $variables
     */
    public static function create(array $variables): static;

    /**
     * Receive variables
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
