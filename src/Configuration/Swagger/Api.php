<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

use Playground\Make\Configuration;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Api
 */
class Api extends Configuration\Configuration implements Configuration\Contracts\WithSkeleton
{
    use Configuration\Concerns\WithSkeleton;

    protected string $openapi = '3.0.3';

    protected ?ExternalDocs $externalDocs = null;

    protected ?Info $info = null;

    protected Components $components;

    protected Controllers $controllers;

    /**
     * @var array<int, Server>
     */
    protected array $servers = [];

    /**
     * @var array<string, Path>
     */
    protected array $paths = [];

    /**
     * @var array<string, Tag>
     */
    protected array $tags = [];

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'openapi' => '3.0.3',
        'servers' => [],
        'info' => null,
        'externalDocs' => null,
        'tags' => [],
        'paths' => [],
        'components' => null,
        'controllers' => null,
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['openapi'])
            && is_string($options['openapi'])
        ) {
            $this->openapi = $options['openapi'];
        }

        if (! empty($options['externalDocs'])
            && is_array($options['externalDocs'])
        ) {
            $this->externalDocs = new ExternalDocs($options['externalDocs']);
        }

        if (! empty($options['info'])
            && is_array($options['info'])
        ) {
            $this->info = new Info($options['info']);
            $this->info->apply();
        }

        if (! empty($options['components'])
            && is_array($options['components'])
            && ! empty($options['components']['schemas'])
            && is_array($options['components']['schemas'])
        ) {
            $this->components = new Components($options['components']);
            $this->components->apply();
        }

        if (! empty($options['controllers']) && is_array($options['controllers'])) {
            $this->controllers = new Controllers($options['controllers']);
            $this->controllers->apply();
        }

        if (! empty($options['paths'])
            && is_array($options['paths'])
        ) {
            foreach ($options['paths'] as $path => $ref) {
                if ($path && is_string($path) && $ref && is_string($ref)) {
                    $this->addPath($path, $ref);
                }
            }
        }

        if (! empty($options['servers'])
            && is_array($options['servers'])
        ) {
            foreach ($options['servers'] as $i => $server) {
                if (is_array($server)) {
                    $this->addServer($server);
                }
            }
        }

        $this->addTags($options);

        return $this;
    }

    /**
     * @param array<string, string> $meta
     */
    public function addServer(array $meta): self
    {
        if (! empty($meta['url']) && is_string($meta['url'])) {
            $server = new Server($meta);
            $server->apply();
            $this->servers[] = $server;
        }

        return $this;
    }

    public function addPath(string $path, string $ref): self
    {
        $this->paths[strtolower($path)] = new Path([
            'path' => strtolower($path),
            'ref' => $ref,
        ]);
        $this->paths[strtolower($path)]->apply();

        return $this;
    }

    public function addTag(string $name): self
    {
        $this->tags[strtolower($name)] = new Tag([
            'name' => $name,
        ]);
        $this->tags[strtolower($name)]->apply();

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function addTags(array $options): self
    {
        if (! empty($options['tags'])
            && is_array($options['tags'])
        ) {
            foreach ($options['tags'] as $name => $meta) {
                if ($name && is_string($name)) {
                    $meta = is_array($meta) ? $meta : [];
                    if (empty($meta['name'])) {
                        $meta['name'] = $name;
                    }
                    $this->tags[strtolower($name)] = new Tag($meta);
                    $this->tags[strtolower($name)]->apply();
                }
            }
        }

        return $this;
    }

    public function openapi(): string
    {
        return $this->openapi;
    }

    public function info(): ?Info
    {
        return $this->info;
    }

    public function components(): Components
    {
        if (empty($this->components)) {
            $this->components = new Components;
            if ($this->skeleton()) {
                $this->components->withSkeleton();
            }
        }

        return $this->components;
    }

    public function controllers(): Controllers
    {
        if (empty($this->controllers)) {
            $this->controllers = new Controllers;
            if ($this->skeleton()) {
                $this->controllers->withSkeleton();
            }
        }

        return $this->controllers;
    }

    public function externalDocs(): ?ExternalDocs
    {
        return $this->externalDocs;
    }

    /**
     * @return array<string, Path>
     */
    public function paths(): array
    {
        return $this->paths;
    }

    /**
     * @return array<int, Server>
     */
    public function servers(): array
    {
        return $this->servers;
    }

    /**
     * @return array<string, Tag>
     */
    public function tags(): array
    {
        return $this->tags;
    }

    public function jsonSerialize(): mixed
    {
        $properties = [];

        $properties['openapi'] = $this->openapi();
        $properties['servers'] = $this->servers();
        $properties['info'] = $this->info();
        $properties['externalDocs'] = $this->externalDocs();

        $tags = $this->tags();
        if ($tags) {
            $properties['tags'] = [];
            foreach ($tags as $name => $tag) {
                $properties['tags'][] = $tag->toArray();
            }
        }

        $paths = $this->paths();
        if ($paths) {
            $properties['paths'] = [];
            foreach ($paths as $name => $path) {
                $properties['paths'][$path->path()] = [
                    '$ref' => $path->ref(),
                ];
            }
        }

        $properties['components'] = $this->components()->toArray();

        // dd([
        //     '$properties' => $properties,
        // ]);

        return $properties;
    }
}
