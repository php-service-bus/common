<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Module;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Configure & enable module.
 */
interface ServiceBusModule
{
    /**
     * @throws \Throwable Boot module failed.
     */
    public function boot(ContainerBuilder $containerBuilder): void;
}
