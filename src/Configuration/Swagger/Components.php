<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Components
 */
class Components extends SwaggerConfiguration
{
    /**
     * @var array<string, Schema>
     */
    protected array $schemas = [];

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'schemas' => [],
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['schemas'])
            && is_array($options['schemas'])
        ) {
            $this->addSchemas($options['schemas']);
        }

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        //     '$this' => $this,
        // ]);

        return $this;
    }

    public function addSchema(string $name, string $ref): self
    {
        $this->schemas[$name] = new Schema([
            'name' => $name,
            'ref' => $ref,
        ]);
        $this->schemas[$name]->apply();

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function addSchemas(array $options): self
    {
        foreach ($options as $name => $meta) {
            // dd([
            //     '__METHOD__' => __METHOD__,
            //     '$options' => $options,
            //     '$name' => $name,
            //     '$meta' => $meta,
            // ]);
            if ($name
                && is_string($name)
                && is_array($meta)
                && ! empty($meta['$ref'])
                && is_string($meta['$ref'])
            ) {
                $this->addSchema($name, $meta['$ref']);
            }
        }

        return $this;
    }

    /**
     * @return array<string, Schema>
     */
    public function schemas(): array
    {
        return $this->schemas;
    }

    public function jsonSerialize(): mixed
    {
        $properties = [];

        $schemas = $this->schemas();
        if ($schemas) {
            $properties['schemas'] = [];
            foreach ($schemas as $name => $schema) {
                $properties['schemas'][$name] = [
                    '$ref' => $schema->ref(),
                ];
            }
        }
        // dd([
        //     '$properties' => $properties,
        // ]);

        return $properties;
    }
}
