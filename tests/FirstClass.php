<?php /** @noinspection PhpMissingFieldTypeInspection */

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
    private string $firstClassValue = 'qwerty';

    public function firstClassValue(): string
    {
        return $this->firstClassValue;
    }
}
