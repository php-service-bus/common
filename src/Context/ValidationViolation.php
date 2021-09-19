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
     *
     * @var string
     */
    public $property;

    /**
     * Violations message.
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $message;

    public function __construct(string $property, string $message)
    {
        $this->property = $property;
        $this->message  = $message;
    }
}
