<?php

declare(strict_types=1);

namespace Crunz\CacheDirectoryFactory;

use Crunz\Exception\CrunzException;
use Crunz\Path\Path;

final class CacheDirectoryFactory
{
    public const CRUNZ_BASE_CACHE_DIR = 'CRUNZ_BASE_CACHE_DIR';

    /**
     * @throws CrunzException
     */
    public function generate(): Path
    {
        $cacheDirectory = '.crunz';
        /** @var false|string $basePath */
        $basePath = \getenv(self::CRUNZ_BASE_CACHE_DIR, true);
        if (false === $basePath) {
            return Path::fromStrings(\sys_get_temp_dir(), $cacheDirectory);
        }

        $basePath = \ltrim($basePath);
        if ('' === $basePath) {
            throw new CrunzException('Crunz base cache directory path is empty.');
        }

        return Path::fromStrings($basePath, $cacheDirectory);
    }
}
