<?php

declare(strict_types=1);

namespace Crunz\Tests\Unit\CacheDirectoryFactory;

use Crunz\CacheDirectoryFactory\CacheDirectoryFactory;
use Crunz\Exception\CrunzException;
use Crunz\Path\Path;
use PHPUnit\Framework\TestCase;

final class CacheDirectoryFactoryTest extends TestCase
{
    /** @test  */
    public function sys_temp_dir_is_default_directory(): void
    {
        $cacheDirectoryFactory = new CacheDirectoryFactory();

        $result = $cacheDirectoryFactory->generate();

        $expectedResult = Path::fromStrings(\sys_get_temp_dir(), '.crunz');
        self::assertEquals($expectedResult, $result);
    }

    /** @test */
    public function change_cache_directory_through_environment_variable(): void
    {
        $newDirectoryPath = '/new/directory/path';
        \putenv(CacheDirectoryFactory::CRUNZ_BASE_CACHE_DIR . "={$newDirectoryPath}");
        $cacheDirectoryFactory = new CacheDirectoryFactory();

        $result = $cacheDirectoryFactory->generate();

        $expectedResult = Path::fromStrings($newDirectoryPath, '.crunz');
        self::assertEquals($expectedResult, $result);
    }

    /** @test */
    public function throw_exception_when_environment_variable_is_empty(): void
    {
        $newDirectoryPath = '   ';
        \putenv(CacheDirectoryFactory::CRUNZ_BASE_CACHE_DIR . "={$newDirectoryPath}");
        $cacheDirectoryFactory = new CacheDirectoryFactory();

        self::expectException(CrunzException::class);

        $cacheDirectoryFactory->generate();
    }
}
