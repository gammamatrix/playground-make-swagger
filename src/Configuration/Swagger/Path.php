<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

/**
 * \ Playground\Make\Swagger\Configuration\Swagger\Path
 */
class Path extends SwaggerConfiguration
{
    protected string $path = '';

    protected string $ref = '';

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        // 'path' => '',
        'ref' => '',
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['path'])
            && is_string($options['path'])
        ) {
            $this->path = $options['path'];
        }

        if (! empty($options['ref'])
            && is_string($options['ref'])
        ) {
            $this->ref = $options['ref'];
        }

        return $this;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function ref(): string
    {
        return $this->ref;
    }
}
