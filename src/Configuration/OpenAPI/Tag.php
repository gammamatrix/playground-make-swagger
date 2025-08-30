<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI;

use Illuminate\Support\Str;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\Tag
 */
class Tag extends OpenAPIConfiguration
{
    protected string $name = '';

    protected string $title = '';

    protected string $description = '';

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'name' => '',
        'description' => '',
        // 'title' => '',
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

        if (! empty($options['title'])
            && is_string($options['title'])
        ) {
            $this->title = $options['title'];
        }

        if (! empty($options['description'])
            && is_string($options['description'])
        ) {
            $this->description = $options['description'];
        }

        if ($this->skeleton() && $this->name) {
            if (! $this->title) {
                $this->title = Str::of($this->name)->title()->toString();
            }
            if (! $this->description) {
                $this->description = __('playground-stub::openapi.Tags.description', [
                    'name' => Str::of($this->name)->lower()->plural()->toString(),
                ]);
            }
        }

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function title(): string
    {
        return $this->title;
    }
}
