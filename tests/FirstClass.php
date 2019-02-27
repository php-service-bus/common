<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace ServiceBus\Common\Tests;

abstract class FirstClass
{
    /**
     * @var string
     */
    private $firstClassValue = 'qwerty';

    /**
     * @return string
     */
    public function firstClassValue(): string
    {
        return $this->firstClassValue;
    }
}
