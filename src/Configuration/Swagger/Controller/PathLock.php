<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Controller;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Controller\PathLock
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
