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
 * Validation violation collection.
 *
 * @psalm-immutable
 * @codeCoverageIgnore
 */
final class ValidationViolations implements \IteratorAggregate, \Countable
{
    /**
     * @psalm-readonly
     * @psalm-var array<array-key, ValidationViolation>
     *
     * @var ValidationViolation[]
     */
    public $violations;

    /**
     * @psalm-param array<array-key, ValidationViolation> $violations
     */
    public function __construct(array $violations)
    {
        $this->violations = $violations;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->violations;
    }

    public function count(): int
    {
        return \count($this->violations);
    }
}
