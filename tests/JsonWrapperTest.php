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

use PHPUnit\Framework\TestCase;
use ServiceBus\Common\Exceptions\JsonSerializationFailed;
use function ServiceBus\Common\jsonDecode;
use function ServiceBus\Common\jsonEncode;

/**
 *
 */
final class JsonWrapperTest extends TestCase
{
    /**
     * @test
     */
    public function decodeEmptyString(): void
    {
        static::assertEmpty(jsonDecode('{}'));
    }

    /**
     * @test
     */
    public function decodeWrongString(): void
    {
        $this->expectException(JsonSerializationFailed::class);

        jsonDecode('...');
    }

    /**
     * @test
     */
    public function encodeEmptyArray(): void
    {
        static::assertSame('[]', jsonEncode([]));
    }

    /**
     * @test
     */
    public function preserveZeroFractionWhenEncodeFloat(): void
    {
        static::assertSame('{"foo":10.0}', jsonEncode(['foo' => 10.0]));
    }

    /**
     * @test
     */
    public function encodeWithWrongCharset(): void
    {
        $this->expectException(JsonSerializationFailed::class);

        jsonEncode(['key' => \iconv('utf-8', 'windows-1251', 'дом')]);
    }
}
