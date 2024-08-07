<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Controller;

use Playground\Make\Configuration;
use Playground\Make\Swagger\Configuration\Swagger\Methods;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Controller\Path
 */
abstract class Path extends Configuration\Configuration
{
    /**
     * @var array<int, Parameter>
     */
    protected array $parameters = [];

    protected string $path = '';

    protected string $ref = '';

    protected ?Methods\GetMethod $getMethod = null;

    protected ?Methods\DeleteMethod $deleteMethod = null;

    protected ?Methods\PatchMethod $patchMethod = null;

    protected ?Methods\PostMethod $postMethod = null;

    protected ?Methods\PutMethod $putMethod = null;

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'path' => '',
        'ref' => '',
        'parameters' => [],
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['parameters'])
            && is_array($options['parameters'])
        ) {
            foreach ($options['parameters'] as $i => $parameter) {
                if (is_array($parameter)) {
                    $this->addParameter($parameter);
                }
            }
        }
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

    /**
     * @param array<string, mixed> $meta
     */
    public function addParameter(array $meta = []): self
    {
        // At least a name is required.
        if (! empty($meta['name']) && is_string($meta['name'])) {
            $parameter = new Parameter($meta);
            $parameter->apply();
            $this->parameters[] = $parameter;
        }

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $properties = [];

        $parameters = $this->parameters();
        if ($parameters) {
            $properties['parameters'] = [];
            foreach ($parameters as $i => $parameter) {
                $properties['parameters'][] = $parameter->toArray();
            }
        }

        $getMethod = $this->getMethod();
        if ($getMethod) {
            $properties['get'] = $getMethod->toArray();
        }

        $deleteMethod = $this->deleteMethod();
        if ($deleteMethod) {
            $properties['delete'] = $deleteMethod->toArray();
        }

        $patchMethod = $this->patchMethod();
        if ($patchMethod) {
            $properties['patch'] = $patchMethod->toArray();
        }

        $postMethod = $this->postMethod();
        if ($postMethod) {
            $properties['post'] = $postMethod->toArray();
        }

        $putMethod = $this->putMethod();
        if ($putMethod) {
            $properties['put'] = $putMethod->toArray();
        }

        // dd([
        //     '$properties' => $properties,
        // ]);

        return $properties;
    }

    /**
     * @return array<int, Parameter>
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function ref(): string
    {
        return $this->ref;
    }

    /**
     * @param ?array<string, mixed> $options
     */
    public function getMethod(array $options = null): ?Methods\GetMethod
    {
        if (is_array($options) && empty($this->getMethod)) {
            $this->getMethod = new Methods\GetMethod($options);
        }

        return $this->getMethod;
    }

    /**
     * @param ?array<string, mixed> $options
     */
    public function deleteMethod(array $options = null): ?Methods\DeleteMethod
    {
        if (is_array($options) && empty($this->deleteMethod)) {
            $this->deleteMethod = new Methods\DeleteMethod($options);
        }

        return $this->deleteMethod;
    }

    /**
     * @param ?array<string, mixed> $options
     */
    public function patchMethod(array $options = null): ?Methods\PatchMethod
    {
        if (is_array($options) && empty($this->patchMethod)) {
            $this->patchMethod = new Methods\PatchMethod($options);
        }

        return $this->patchMethod;
    }

    /**
     * @param ?array<string, mixed> $options
     */
    public function postMethod(array $options = null): ?Methods\PostMethod
    {
        if (is_array($options) && empty($this->postMethod)) {
            $this->postMethod = new Methods\PostMethod($options);
        }

        return $this->postMethod;
    }

    /**
     * @param ?array<string, mixed> $options
     */
    public function putMethod(array $options = null): ?Methods\PutMethod
    {
        if (is_array($options) && empty($this->putMethod)) {
            $this->putMethod = new Methods\PutMethod($options);
        }

        return $this->putMethod;
    }
}
