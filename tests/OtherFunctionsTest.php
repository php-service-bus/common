<?php

/**
 * PHP Service Bus common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Tests;

use function ServiceBus\Common\formatBytes;
use function ServiceBus\Common\uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 *
 */
final class OtherFunctionsTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function uuid(): void
    {
        $uuid = uuid();

        static::assertNotEmpty($uuid);
        static::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @test
     * @dataProvider formatBytesDataProvider
     *
     * @param int    $bytes
     * @param string $expected
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function formatBytes(int $bytes, string $expected): void
    {
        static::assertSame($expected, formatBytes($bytes));
    }

    /**
     * @return array
     */
    public function formatBytesDataProvider(): array
    {
        return [
            [1, '1 b'],
            [10000, '9.77 kb'],
            [10000000, '9.54 mb']
        ];
    }
}
