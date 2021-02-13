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
 * Outcome message metadata.
 */
interface OutcomeMessageMetadata
{
    /**
     * @psalm-param array<string, string|int|float|bool|null> $variables
     */
    public static function create(array $variables): self;

    /**
     * Add a new key.
     */
    public function with(string $key, string|int|float|bool|null $value): self;

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
