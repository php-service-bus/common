<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Tests;

use function ServiceBus\Common\collectThrowableDetails;
use function ServiceBus\Common\formatBytes;
use function ServiceBus\Common\uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class OtherFunctionsTest extends TestCase
{
    /** @test */
    public function uuid(): void
    {
        $uuid = uuid();

        static::assertNotEmpty($uuid);
        static::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @test
     * @dataProvider formatBytesDataProvider
     */
    public function formatBytes(int $bytes, string $expected): void
    {
        static::assertSame($expected, formatBytes($bytes));
    }

    public function formatBytesDataProvider(): array
    {
        return [
            [1, '1 b'],
            [10000, '9.77 kb'],
            [10000000, '9.54 mb'],
        ];
    }

    /** @test */
    public function collectThrowableDetails(): void
    {
        $data = collectThrowableDetails(new \LogicException('message'));

        static::assertArrayHasKey('throwableMessage', $data);
        static::assertArrayHasKey('throwablePoint', $data);
        static::assertArrayHasKey('throwablePrevious', $data);

        static::assertEmpty($data['throwablePrevious']);
        static::assertSame('message', $data['throwableMessage']);
    }

    /** @test */
    public function collectThrowableDetailsWithPrevious(): void
    {
        $data = collectThrowableDetails(
            new \LogicException(
                'message',
                0,
                new \RuntimeException('runtime', 0, new \InvalidArgumentException('invalid'))
            )
        );

        static::assertNotEmpty($data['throwablePrevious']);
        static::assertCount(2, $data['throwablePrevious']);

        static::assertArrayHasKey('throwableMessage', $data['throwablePrevious'][0]);
        static::assertArrayHasKey('throwablePoint', $data['throwablePrevious'][0]);

        static::assertSame('runtime', $data['throwablePrevious'][0]['throwableMessage']);
    }
}
