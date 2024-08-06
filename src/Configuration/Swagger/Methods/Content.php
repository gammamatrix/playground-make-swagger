<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Methods;

use Playground\Make\Configuration;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Methods\Content
 */
class Content extends Configuration\Configuration
{
    protected string $type = '';

    /**
     * @var array<string, mixed>
     */
    protected array $schema = [];

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'type' => '',
        'schema' => [],
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['type'])
            && is_string($options['type'])
        ) {
            $this->type = $options['type'];
        }

        if (! empty($options['schema'])
            && is_array($options['schema'])
        ) {
            $this->schema = $options['schema'];
        }

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        //     '$this' => $this,
        // ]);

        return $this;
    }

    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return array<string, mixed>
     */
    public function schema(): array
    {
        return $this->schema;
    }

    public function jsonSerialize(): mixed
    {
        $properties = [];

        $schema = $this->schema();
        $type = $this->type();

        if ($type) {
            $properties[$type] = [
                // 'schema' => [],
            ];

            if ($schema) {
                $properties[$type]['schema'] = $schema;
            }
        }

        return $properties;
    }
}
