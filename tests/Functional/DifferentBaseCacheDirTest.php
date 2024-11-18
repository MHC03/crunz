<?php

declare(strict_types=1);

namespace Crunz\Tests\Functional;

use Crunz\Application;
use Crunz\CacheDirectoryFactory\CacheDirectoryFactory;
use PHPUnit\Framework\TestCase;

final class DifferentBaseCacheDirTest extends TestCase
{
    /**
     * @test
     *
     * @runInSeparateProcess
     */
    public function different_base_cache_dir_is_used(): void
    {
        $newTmpDir = \sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'newBaseCacheDir';
        \putenv(CacheDirectoryFactory::CRUNZ_BASE_CACHE_DIR . "={$newTmpDir}");

        new Application('Crunz', '0.1.0-test.1');

        self::assertDirectoryExists($newTmpDir);
    }
}
