<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 0);

namespace ServiceBus\Common\Context\Exceptions;

/**
 *
 */
final class MessageDeliveryFailed extends \RuntimeException
{
    /**
     * Message type.
     *
     * @psalm-readonly
     *
     * @var object
     */
    public $messageObject;

    /**
     * Message identifier.
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $messageId;

    /**
     * Trace identifier.
     *
     * @psalm-readonly
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
