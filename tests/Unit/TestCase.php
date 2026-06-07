<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Tests\Unit\Playground\Make\OpenAPI;

use Playground\ServiceProvider;
use Playground\Test\OrchestraTestCase;

/**
 * \Tests\Unit\Playground\Make\OpenAPI\TestCase
 */
class TestCase extends OrchestraTestCase
{
    use FileTrait;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            \Playground\Make\ServiceProvider::class,
            \Playground\Make\OpenAPI\ServiceProvider::class,
        ];
    }
}
