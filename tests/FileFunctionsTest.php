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

use PHPUnit\Framework\TestCase;
use function ServiceBus\Common\canonicalizeFilesPath;
use function ServiceBus\Common\extractNamespaceFromFile;
use function ServiceBus\Common\searchFiles;

/**
 *
 */
final class FileFunctionsTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function searchFiles(): void
    {
        $files = \iterator_to_array(searchFiles(canonicalizeFilesPath([__DIR__]), '/\.php/i'));

        static::assertCount(7, $files);
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function extractNamespaceFromFile(): void
    {
        static::assertSame(__CLASS__, extractNamespaceFromFile(__FILE__));
        static::assertNull(extractNamespaceFromFile(__DIR__ . '/empty_php_file.php'));
    }
}
