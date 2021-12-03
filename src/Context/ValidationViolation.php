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
 * Validation violation.
 *
 * @psalm-immutable
 * @codeCoverageIgnore
 */
final class ValidationViolation
{
    /**
     * Property name.
     *
     * @psalm-readonly
     * @psalm-var non-empty-string
     *
     * @var string
     */
    public $property;

    /**
     * Violations message.
     *
     * @psalm-readonly
     * @psalm-var non-empty-string
     *
     * @var string
     */
    public $message;

    /**
     * @psalm-param non-empty-string $property
     * @psalm-param non-empty-string $message
     */
    public function __construct(string $property, string $message)
    {
        $this->property = $property;
        $this->message  = $message;
    }
}
