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
    /**
     * @var string
     */
    private $secondClassValue = 'root';

    /**
     * @var string
     */
    private $secondClassPublicValue = 'abube';

    /**
     * @return string
     */
    public function secondClassValue(): string
    {
        return $this->secondClassValue;
    }

    /**
     * @return string
     */
    public function secondClassPublicValue(): string
    {
        return $this->secondClassPublicValue;
    }

    /**
     *  @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param string $text
     *
     * @return string
     */
    private function privateMethod(string $text): string
    {
        return $text;
    }
}
