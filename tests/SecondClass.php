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

final class SecondClass extends FirstClass
{
    private string $secondClassValue = 'root';

    private string $secondClassPublicValue = 'abube';

    public function secondClassValue(): string
    {
        return $this->secondClassValue;
    }

    public function secondClassPublicValue(): string
    {
        return $this->secondClassPublicValue;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function privateMethod(string $text): string
    {
        return $text;
    }
}
