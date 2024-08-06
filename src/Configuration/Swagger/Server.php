<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Server
 */
class Server extends SwaggerConfiguration
{
    protected string $url = '';

    protected string $description = '';

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'url' => '',
        'description' => '',
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['url'])
            && is_string($options['url'])
        ) {
            $this->url = $options['url'];
        }

        if (! empty($options['description'])
            && is_string($options['description'])
        ) {
            $this->description = $options['description'];
        }

        return $this;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function description(): string
    {
        return $this->description;
    }
}
