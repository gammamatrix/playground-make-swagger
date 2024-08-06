<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Controller;

use Playground\Make\Configuration;

/**
 * \ Playground\Make\Swagger\Configuration\Swagger\Controller\PathId
 */
class Parameter extends Configuration\Configuration
{
    protected string $in = '';

    protected string $name = '';

    protected bool $required = false;

    protected string $description = '';

    /**
     * @var array<string, string>
     */
    protected array $schema = [
        'type' => '',
        'format' => '',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'in' => '',
        'name' => '',
        'required' => false,
        'description' => '',
        'schema' => [
            'type' => '',
            'format' => '',
        ],
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['in'])
            && is_string($options['in'])
        ) {
            $this->in = $options['in'];
        }

        if (! empty($options['name'])
            && is_string($options['name'])
        ) {
            $this->name = $options['name'];
        }

        if (array_key_exists('required', $options)) {
            $this->required = ! empty($options['required']);
        }

        if (! empty($options['description'])
            && is_string($options['description'])
        ) {
            $this->description = $options['description'];
        }

        if (! empty($options['schema'])
            && is_array($options['schema'])
        ) {
            $this->schema = [
                'type' => empty($options['schema']['type']) || ! is_string($options['schema']['type']) ? '' : $options['schema']['type'],
                'format' => empty($options['schema']['format']) || ! is_string($options['schema']['format']) ? '' : $options['schema']['format'],
            ];
        }

        return $this;
    }

    public function in(): string
    {
        return $this->in;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function required(): bool
    {
        return $this->required;
    }

    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return array<string, string>
     */
    public function schema(): array
    {
        return $this->schema;
    }
}
