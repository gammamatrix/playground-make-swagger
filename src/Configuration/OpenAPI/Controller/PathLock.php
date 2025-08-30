<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI\Controller;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\Controller\PathLock
 */
class PathLock extends Path
{
    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'parameters' => [],
        'deleteMethod' => null,
        'putMethod' => null,
    ];
}
