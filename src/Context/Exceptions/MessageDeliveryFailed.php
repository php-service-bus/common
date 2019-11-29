<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Context\Exceptions;

/**
 * @psalm-readonly
 */
final class MessageDeliveryFailed extends \RuntimeException
{
    /**
     * Message type.
     */
    public object $messageObject;

    /**
     * Trace identifier.
     *
     * @var string
     */
    public string $traceId;

    /**
     * @param string          $throwableMessage
     * @param object          $messageObject
     * @param string          $traceId
     * @param \Throwable|null $previous
     */
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
