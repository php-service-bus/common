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
 * Outcome message metadata.
 */
interface OutcomeMessageMetadata
{
    /**
     * @psalm-param non-empty-string $traceId
     * @psalm-param array<non-empty-string, string|int|float|bool|null> $variables
     */
    public static function create(string $traceId, array $variables = []): self;

    /**
     * Add a new key.
     *
     * @psalm-param non-empty-string $key
     */
    public function with(string $key, string|int|float|bool|null $value): self;

    /**
     * Receive trace id.
     *
     * @psalm-return non-empty-string
     */
    public function traceId(): string;

    /**
     * Receive variables.
     *
     * @psalm-return array<non-empty-string, string|int|float|bool|null>
     */
    public function variables(): array;

    /**
     * Has metadata key.
     *
     * @psalm-param non-empty-string $key
     */
    public function has(string $key): bool;

    /**
     * Receive message metadata value.
     *
     * @psalm-param non-empty-string $key
     */
    public function get(string $key, string|int|float|bool|null $default = null): string|int|float|bool|null;
}
