<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\Schema
 */
class Schema extends OpenAPIConfiguration
{
    protected string $name = '';

    protected string $ref = '';

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'name' => '',
        'ref' => '',
    ];

    /**
     * @param  array<string, mixed>  $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['name'])
            && is_string($options['name'])
        ) {
            $this->name = $options['name'];
        }

        if (! empty($options['ref'])
            && is_string($options['ref'])
        ) {
            $this->ref = $options['ref'];
        }

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function ref(): string
    {
        return $this->ref;
    }
}
