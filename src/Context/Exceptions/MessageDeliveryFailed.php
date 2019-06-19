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
 * @property-read object $messageObject
 * @property-read string $traceId
 */
final class MessageDeliveryFailed extends \RuntimeException
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

    /**
     * @param string          $throwableMessage
     * @param object          $messageObject
     * @param string          $traceId
     * @param \Throwable|null $previous
     */
    public function __construct(string $throwableMessage, object $messageObject, string $traceId, ?\Throwable $previous = null)
    {
        parent::__construct($throwableMessage, 0, $previous);

        $this->messageObject = $messageObject;
        $this->traceId       = $traceId;
    }
}
