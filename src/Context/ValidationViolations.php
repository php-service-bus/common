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
 * Validation violation collection.
 *
 * @psalm-immutable
 */
final class ValidationViolations implements \IteratorAggregate, \Countable
{
    /**
     * @psalm-readonly
     *
     * @var ValidationViolation[]
     */
    public $violations;

    /**
     * @param ValidationViolation[] $violations
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
