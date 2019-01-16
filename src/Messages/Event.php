<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) Common component
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace Desperado\ServiceBus\Common\Messages;

/**
 * Used to communicate that some action has taken place. An Event should be published
 */
interface Event extends Message
{

}
