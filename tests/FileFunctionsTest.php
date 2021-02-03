<?php

/**
 * PHP Service Bus common component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace ServiceBus\Common\Tests;

use function ServiceBus\Common\canonicalizeFilesPath;
use function ServiceBus\Common\extractNamespaceFromFile;
use function ServiceBus\Common\searchFiles;
use PHPUnit\Framework\TestCase;
use ServiceBus\Common\Exceptions\FileSystemException;

final class FileFunctionsTest extends TestCase
{
    /** @test */
    public function searchFiles(): void
    {
        $files = \iterator_to_array(searchFiles(canonicalizeFilesPath([__DIR__]), '/\.php/i'));

        self::assertCount(10, $files);
    }

    /** @test */
    public function extractNamespaceFromFile(): void
    {
        self::assertSame(__CLASS__, extractNamespaceFromFile(__FILE__));
        self::assertNull(extractNamespaceFromFile(__DIR__ . '/empty_php_file.php'));
    }

    /** @test */
    public function extractFromNonexistentFile(): void
    {
        $this->expectException(FileSystemException::class);

        extractNamespaceFromFile('qwerty.exe');
    }
}
