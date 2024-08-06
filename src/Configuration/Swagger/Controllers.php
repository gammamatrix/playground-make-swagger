<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

/**
 * \ Playground\Make\Swagger\Configuration\Swagger\Controllers
 */
class Controllers extends SwaggerConfiguration
{
    protected ?Controller\PathId $pathId = null;

    protected ?Controller\PathIndex $pathIndex = null;

    protected ?Controller\PathIndexForm $pathIndexForm = null;

    /**
     * @var array<string, mixed>
     */
    protected $properties = [
        // 'pathId' => null,
    ];

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options = []): self
    {
        if (! empty($options['pathId'])
            && is_array($options['pathId'])
        ) {
            $this->pathId = new Controller\PathId($options['pathId']);
        }

        if (! empty($options['pathIndex'])
            && is_array($options['pathIndex'])
        ) {
            $this->pathIndex = new Controller\PathIndex($options['pathIndex']);
        }

        if (! empty($options['pathIndexForm'])
            && is_array($options['pathIndexForm'])
        ) {
            $this->pathIndexForm = new Controller\PathIndexForm($options['pathIndexForm']);
        }

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function pathId(array $options = []): Controller\PathId
    {
        if (empty($this->pathId)) {
            $this->pathId = new Controller\PathId($options);
            $this->properties['pathId'] = $this->pathId->apply()->toArray();
        }

        return $this->pathId;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function pathIndex(array $options = []): Controller\PathIndex
    {
        if (empty($this->pathIndex)) {
            $this->pathIndex = new Controller\PathIndex($options);
            $this->properties['pathIndex'] = $this->pathIndex->apply()->toArray();
        }

        return $this->pathIndex;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function pathIndexForm(array $options = []): Controller\PathIndexForm
    {
        if (empty($this->pathIndexForm)) {
            $this->pathIndexForm = new Controller\PathIndexForm($options);
            $this->properties['pathIndexForm'] = $this->pathIndexForm->apply()->toArray();
        }

        return $this->pathIndexForm;
    }
}
