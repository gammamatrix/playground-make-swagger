<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI\Controller;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\Controller\PathCreate
 */
class PathCreate extends Path
{
    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'parameters' => [],
        'getMethod' => null,
    ];
}
