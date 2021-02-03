<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\MessageExecutor\Exceptions;

/**
 * @psalm-readonly
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
        parent::__construct(\sprintf('Message execution failed: %s', $throwableMessage), 0, $previous);

        $this->messageObject = $messageObject;
        $this->traceId       = $traceId;
    }
}
