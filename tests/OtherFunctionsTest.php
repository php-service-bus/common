<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Tests;

use Symfony\Component\Uid\Uuid;
use function ServiceBus\Common\throwableDetails;
use function ServiceBus\Common\formatBytes;
use function ServiceBus\Common\isUuid;
use function ServiceBus\Common\throwableMessage;
use function ServiceBus\Common\uuid;
use PHPUnit\Framework\TestCase;

final class OtherFunctionsTest extends TestCase
{
    /**
     * @test
     */
    public function uuid(): void
    {
        $uuid = uuid();

        self::assertNotEmpty($uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @test
     */
    public function isUUID(): void
    {
        self::assertTrue(isUuid(uuid()));
        self::assertFalse(isUuid('qwerty'));
    }

    /**
     * @test
     * @dataProvider formatBytesDataProvider
     */
    public function formatBytes(int $bytes, string $expected): void
    {
        self::assertSame($expected, formatBytes($bytes));
    }

    public function formatBytesDataProvider(): array
    {
        return [
            [1, '1 b'],
            [10000, '9.77 kb'],
            [10000000, '9.54 mb'],
        ];
    }

    /**
     * @test
     */
    public function collectThrowableDetails(): void
    {
        $data = throwableDetails(new \LogicException('message'));

        self::assertArrayHasKey('throwableMessage', $data);
        self::assertArrayHasKey('throwablePoint', $data);
        self::assertArrayHasKey('throwablePrevious', $data);

        self::assertEmpty($data['throwablePrevious']);
        self::assertSame('message', $data['throwableMessage']);
    }

    /**
     * @test
     */
    public function collectThrowableDetailsWithPrevious(): void
    {
        $data = throwableDetails(
            new \LogicException(
                'message',
                0,
                new \RuntimeException('runtime', 0, new \InvalidArgumentException('invalid'))
            )
        );

        self::assertNotEmpty($data['throwablePrevious']);
        self::assertCount(2, $data['throwablePrevious']);

        self::assertArrayHasKey('throwableMessage', $data['throwablePrevious'][0]);
        self::assertArrayHasKey('throwablePoint', $data['throwablePrevious'][0]);

        self::assertSame('runtime', $data['throwablePrevious'][0]['throwableMessage']);
    }

    /**
     * @test
     */
    public function throwableMessage(): void
    {
        $throwable = new \LogicException(
            'message',
            0,
            new \RuntimeException('runtime', 0, new \InvalidArgumentException('invalid'))
        );

        self::assertSame('message (Previous: runtime; invalid)', throwableMessage($throwable));
    }
}
