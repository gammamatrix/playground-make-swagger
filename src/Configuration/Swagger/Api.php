<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

use Playground\Make\Configuration;

// use Playground\Make\Swagger\Configuration\Swagger\Controller\Path;

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

    /**
     * @var array<string, Controller>
     */
    protected array $controllers = [];

    /**
     * @var array<int, Server>
     */
    protected array $servers = [];

    /**
     * @var array<string, string>
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
        'controllers' => [],
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
        ) {
            $this->addComponents($options['components']);
        }

        if (! empty($options['controllers']) && is_array($options['controllers'])) {
            $this->addControllers($options['controllers']);
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

        if (! empty($options['tags'])
            && is_array($options['tags'])
        ) {
            $this->addTags($options['tags']);
        }

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        //     '$this' => $this,
        // ]);

        return $this;
    }

    /**
     * @param array<string, string> $meta
     */
    public function addComponents(array $meta): self
    {
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$meta' => $meta,
        //     '$this' => $this,
        // ]);

        if (empty($this->components)) {
            $this->components = new Components($meta);
        } else {
            if (! empty($meta['schemas'])
                && is_array($meta['schemas'])
            ) {
                $this->components->addSchemas($meta['schemas']);
            }
        }

        $this->components->apply();

        return $this;
    }

    /**
     * @param array<string, string> $meta
     */
    public function addControllers(array $meta): self
    {
        foreach ($meta as $key => $value) {
            if ($key && is_string($key)) {
                $this->addController(
                    $key,
                    is_array($value) ? $value : []
                );
            }
        }

        return $this;
    }

    /**
     * @param array<string, string> $meta
     */
    public function addController(string $controller, array $meta): self
    {
        if (empty($this->controllers[$controller])) {
            $this->controllers[$controller] = new Controller;
            if ($this->skeleton()) {
                $this->controllers[$controller]->withSkeleton();
            }
        }

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
        $this->paths[$path] = $ref;

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$path' => $path,
        //     // '$this' => $this,
        // ]);
        return $this;
    }

    public function addTag(string $name, string $description = ''): self
    {
        $this->tags[$name] = new Tag([
            'name' => $name,
            'description' => $description,
        ]);
        $this->tags[$name]->apply();

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function addTags(array $options): self
    {
        foreach ($options as $tag) {
            if (is_array($tag)
                && ! empty($tag['name'])
                && is_string($tag['name'])
            ) {
                $this->addTag(
                    $tag['name'],
                    ! empty($tag['description']) && is_string($tag['description']) ? $tag['description'] : ''
                );
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

    /**
     * @return array<string, Controller>
     */
    public function controllers(): array
    {
        return $this->controllers;
    }

    public function controller(string $controller): Controller
    {
        if (empty($this->controllers[$controller])) {
            $this->controllers[$controller] = new Controller;
            if ($this->skeleton()) {
                $this->controllers[$controller]->withSkeleton();
            }
        }

        return $this->controllers[$controller];
    }

    public function externalDocs(): ?ExternalDocs
    {
        return $this->externalDocs;
    }

    /**
     * @return array<string, string>
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
        $properties['info'] = $this->info()?->toArray();
        $properties['externalDocs'] = $this->externalDocs()?->toArray();

        $servers = $this->servers();
        if ($servers) {
            $properties['servers'] = [];
            foreach ($servers as $server) {
                $properties['servers'][] = $server->toArray();
            }
        }

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
            foreach ($paths as $path => $ref) {
                $properties['paths'][$path] = [
                    '$ref' => $ref,
                ];
            }
        }

        $properties['components'] = $this->components()->toArray();

        // $controllers = $this->controllers();

        // if ($controllers) {
        //     $properties['paths'] = [];
        //     foreach ($controllers as $name => $controller) {
        //         foreach ($controller->keys() as $method) {
        //             $properties['paths'][${$method}->path()] = [
        //                 '$ref' => ${$method}->ref(),
        //             ];
        //         }
        //     }
        // }

        // dump([
        //     '$properties' => $properties,
        //     '$this' => $this,
        // ]);

        return $properties;
    }
}
