<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\MessageExecutor\Exceptions;

/**
 * @psalm-immutable
 */
final class MessageExecutionFailed extends \RuntimeException
{
    /**
     * Message type.
     *
     * @var object
     */
    public $messageObject;

    /**
     * Trace identifier.
     *
     * @var string
     */
    public $traceId;

    public function __construct(
        string $throwableMessage,
        object $messageObject,
        string $traceId,
        ?\Throwable $previous = null
    ) {
        parent::__construct($throwableMessage, 0, $previous);

        $this->messageObject = $messageObject;
        $this->traceId       = $traceId;
    }
}
