<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger\Controller;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Methods;
use Playground\Make\Swagger\Configuration\Swagger\SwaggerConfiguration;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Controller\PathId
 */
class Path extends SwaggerConfiguration
{
    /**
     * @var array<int, Parameter>
     */
    protected array $parameters = [];

    protected ?Methods\GetMethod $getMethod = null;

    protected ?Methods\DeleteMethod $deleteMethod = null;

    protected ?Methods\PatchMethod $patchMethod = null;

    protected ?Methods\PostMethod $postMethod = null;

    protected ?Methods\PutMethod $putMethod = null;

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
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
                    $this->addParameter(
                        ! empty($options['name']) && is_string($options['name']) ? $options['name'] : '',
                        $parameter
                    );
                }
            }
        }

        return $this;
    }

    /**
     * @param array<string, mixed> $meta
     */
    public function addParameter(string $name, array $meta = []): self
    {
        if (! empty($meta['name']) && is_string($meta['name'])) {
            if (! empty($name) && $this->skeleton()) {
                if (empty($meta['description']) || ! is_string($meta['description'])) {
                    $meta['description'] = __('playground-stub::swagger.Controller.PathId.Parameter.description', [
                        'name' => Str::of($name)->lower()->singular()->toString(),
                    ]);
                }
            }

            $parameter = new Parameter($meta);
            $parameter->apply();
            $this->parameters[] = $parameter;
        }

        return $this;
    }

    /**
     * @return array<int, Parameter>
     */
    public function parameters(): array
    {
        return $this->parameters;
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
