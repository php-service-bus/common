<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace ServiceBus\Common\Module;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Configure & enable module.
 */
interface ServiceBusModule
{
    /**
     * @param ContainerBuilder $containerBuilder
     *
     * @throws \Throwable Boot module failed
     */
    public function boot(ContainerBuilder $containerBuilder): void;
}
