<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\Common\Metadata;

interface ServiceBusMetadata
{
    public const SERVICE_BUS_SERIALIZER_TYPE = 'x-encoder-type';
    public const SERVICE_BUS_MESSAGE_TYPE    = 'x-message-type';

    public const SERVICE_BUS_MESSAGE_FAILED_IN   = 'x-failed-in';
    public const SERVICE_BUS_MESSAGE_RETRY_COUNT = 'x-retry-count';

    public const INTERNAL_METADATA_KEYS = [
        self::SERVICE_BUS_SERIALIZER_TYPE,
        self::SERVICE_BUS_MESSAGE_TYPE,
        self::SERVICE_BUS_MESSAGE_RETRY_COUNT,
        self::SERVICE_BUS_MESSAGE_FAILED_IN,
    ];
}
