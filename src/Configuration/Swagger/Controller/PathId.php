<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \ Playground\Make\Swagger\Configuration\Swagger\Controller\PathId
 */
class PathId extends Path
{
    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'parameters' => [],
        'getMethod' => null,
        'deleteMethod' => null,
        'patchMethod' => null,
        'putMethod' => null,
    ];
}
