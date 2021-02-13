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

use function ServiceBus\Common\datetimeInstantiator;
use function ServiceBus\Common\datetimeToString;
use PHPUnit\Framework\TestCase;
use ServiceBus\Common\Exceptions\DateTimeException;
use function ServiceBus\Common\now;

final class DateTimeFunctionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider datetimeInstantiatorDataProvider
     */
    public function datetimeInstantiator(
        string $date,
        ?string $timezone,
        ?string $expectedResult,
        bool $expectException = false
    ): void {
        if ($expectException)
        {
            $this->expectException(DateTimeException::class);
        }

        $result = datetimeInstantiator($date, $timezone);

        if ($expectedResult !== null)
        {
            self::assertNotNull($result);

            /** @var \DateTimeImmutable $result */
            self::assertSame(
                \date('Y-m-d H:i:s', (int) \strtotime($expectedResult)),
                $result->format('Y-m-d H:i:s')
            );

            return;
        }

        self::assertNull($result);
    }

    public function datetimeInstantiatorDataProvider(): array
    {
        return [
            ['qwerty', null, null, true],
            ['', null, null, false],
            ['2019-01-01 12:00:00', null, '2019-01-01 12:00:00', false],
            ['2019-01-01 12:00:00', 'qwerty', null, true],
        ];
    }

    /**
     * @test
     * @dataProvider datetimeToStringDataProvider
     */
    public function datetimeToString(
        ?\DateTimeImmutable $dateTime,
        string $format,
        ?string $expectedResult,
        bool $expectException = false
    ): void {
        if ($expectException)
        {
            $this->expectException(DateTimeException::class);
        }

        $result = datetimeToString($dateTime, $format);

        if ($expectedResult !== null)
        {
            self::assertNotNull($result);

            /** @var \DateTimeImmutable $result */
            self::assertSame($expectedResult, $result);

            return;
        }

        self::assertNull($result);
    }

    public function datetimeToStringDataProvider(): array
    {
        return [
            [new \DateTimeImmutable('2019-01-01 12:00:00'), 'Y-m-d H:i:s', '2019-01-01 12:00:00'],
            [null, 'Y-m-d H:i:s', null],
            [new \DateTimeImmutable('2019-01-01 12:00:00'), '&', null, true],
        ];
    }

    /**
     * @test
     */
    public function now(): void
    {
        self::assertEquals(
            \date('Y-m-d H:i'),
            now('UTC')->format('Y-m-d H:i')
        );
    }

    /**
     * @test
     */
    public function withMicroseconds(): void
    {
        $now = now('UTC')->format('Y-m-d H:i:s.u');

        /** @var \DateTimeImmutable $fromInstantiator */
        $fromInstantiator = datetimeInstantiator($now);

        self::assertEquals($now, $fromInstantiator->format('Y-m-d H:i:s.u'));
    }
}
