<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) Common component
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace Desperado\ServiceBus\Common\Tests;

/**
 *
 */
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
