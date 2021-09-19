<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\Common\EntryPoint\Retry;

/**
 * @psalm-immutable
 * @codeCoverageIgnore
 */
final class FailureContext
{
    /**
     * Key => value, where:
     *
     *   key: executor ID
     *   value: error message
     *
     * @psalm-readonly
     * @psalm-var array<string, string>
     *
     * @var array
     */
    public $executors;

    /**
     * @psalm-param array<string, string> $executors
     */
    public function __construct(array $executors)
    {
        $this->executors = $executors;
    }
}
