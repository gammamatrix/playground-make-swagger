<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration;

use Playground\Make\Configuration\PrimaryConfiguration;

/**
 * \Playground\Make\Swagger\Configuration\Swagger
 */
class Swagger extends PrimaryConfiguration
{
    protected string $controller_type = '';

    protected string $model_fqdn = '';

    protected string $model_column = '';

    protected string $model_label = '';

    protected string $model_slug_plural = '';

    /**
     * @var array<string, string>
     */
    protected array $models = [];

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        'class' => '',
        'config' => '',
        'fqdn' => '',
        'model' => '',
        'model_fqdn' => '',
        'model_column' => '',
        'model_label' => '',
        'model_slug_plural' => '',
        'module' => '',
        'module_slug' => '',
        'name' => '',
        'namespace' => '',
        'organization' => '',
        'package' => '',
        // properties
        'models' => [],
        'folder' => '',
        'type' => '',
        'controller_type' => '',
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        parent::setOptions($options);

        if (! empty($options['controller_type'])
            && is_string($options['controller_type'])
        ) {
            $this->controller_type = $options['controller_type'];
        }

        if (! empty($options['model_fqdn'])
            && is_string($options['model_fqdn'])
        ) {
            $this->model_fqdn = $options['model_fqdn'];
        }

        if (! empty($options['model_column'])
            && is_string($options['model_column'])
        ) {
            $this->model_column = $options['model_column'];
        }

        if (! empty($options['model_label'])
            && is_string($options['model_label'])
        ) {
            $this->model_label = $options['model_label'];
        }

        if (! empty($options['model_slug_plural'])
            && is_string($options['model_slug_plural'])
        ) {
            $this->model_slug_plural = $options['model_slug_plural'];
        }

        if (! empty($options['folder'])
            && is_string($options['folder'])
        ) {
            $this->folder = $options['folder'];
        }

        $this->addModels($options);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function addModels(array $options): self
    {
        if (! empty($options['models'])
            && is_array($options['models'])
        ) {
            foreach ($options['models'] as $key => $file) {
                $this->addMappedClassTo('models', $key, $file);
            }
        }

        return $this;
    }

    public function model_fqdn(): string
    {
        return $this->model_fqdn;
    }

    public function controller_type(): string
    {
        return $this->controller_type;
    }

    /**
     * @return array<string, string>
     */
    public function models(): array
    {
        return $this->models;
    }
}
