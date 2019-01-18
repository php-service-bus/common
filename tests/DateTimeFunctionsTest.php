<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) common component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Common\Tests;

use function ServiceBus\Common\datetimeInstantiator;
use function ServiceBus\Common\datetimeToString;
use ServiceBus\Common\Exceptions\DateTime\CreateDateTimeFailed;
use ServiceBus\Common\Exceptions\DateTime\InvalidDateTimeFormatSpecified;
use PHPUnit\Framework\TestCase;

/**
 *
 */
final class DateTimeFunctionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider datetimeInstantiatorDataProvider
     *
     * @param string      $date
     * @param string|null $timezone
     * @param string|null $expectedResult
     * @param bool        $expectException
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function datetimeInstantiator(
        string $date,
        ?string $timezone,
        ?string $expectedResult,
        bool $expectException = false
    ): void
    {
        if(true === $expectException)
        {
            $this->expectException(CreateDateTimeFailed::class);
        }

        $result = datetimeInstantiator($date, $timezone);

        if(null !== $expectedResult)
        {
            static::assertNotNull($result);

            /** @var \DateTimeImmutable $result */

            static::assertSame(
                \date('Y-m-d H:i:s', \strtotime($expectedResult)),
                $result->format('Y-m-d H:i:s')
            );

            return;
        }

        static::assertNull($result);
    }

    /**
     * @return array
     */
    public function datetimeInstantiatorDataProvider(): array
    {
        return [
            ['qwerty', null, null, true],
            ['', null, null, false],
            ['2019-01-01 12:00:00', null, '2019-01-01 12:00:00', false],
            ['2019-01-01 12:00:00', 'qwerty', null, true]
        ];
    }

    /**
     * @test
     * @dataProvider datetimeToStringDataProvider
     *
     * @param \DateTimeImmutable|null $dateTime
     * @param string                  $format
     * @param string|null             $expectedResult
     * @param bool                    $expectException
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function datetimeToString(
        ?\DateTimeImmutable $dateTime,
        string $format,
        ?string $expectedResult,
        bool $expectException = false
    ): void
    {
        if(true === $expectException)
        {
            $this->expectException(InvalidDateTimeFormatSpecified::class);
        }

        $result = datetimeToString($dateTime, $format);

        if(null !== $expectedResult)
        {
            static::assertNotNull($result);

            /** @var \DateTimeImmutable $result */

            static::assertSame($expectedResult, $result);

            return;
        }

        static::assertNull($result);
    }

    /**
     * @return array
     *
     * @throws \Throwable
     */
    public function datetimeToStringDataProvider(): array
    {
        return [
            [new \DateTimeImmutable('2019-01-01 12:00:00'), 'Y-m-d H:i:s', '2019-01-01 12:00:00'],
            [null, 'Y-m-d H:i:s', null],
            [new \DateTimeImmutable('2019-01-01 12:00:00'), '&', null, true]
        ];
    }
}
