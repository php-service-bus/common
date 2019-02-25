<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Messages;

/**
 * Used to communicate that some action has taken place. An Event should be published
 *
 * @noinspection PhpDeprecationInspection
 * @deprecated Now interface is not required. Will be removed in 3.1 version
 */
interface Event extends Message
{

}
