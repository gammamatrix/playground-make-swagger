<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\Controllers
 */
class Controllers extends SwaggerConfiguration
{
    protected ?Controller\PathCreate $pathCreate = null;

    protected ?Controller\PathEdit $pathEdit = null;

    protected ?Controller\PathId $pathId = null;

    protected ?Controller\PathIndex $pathIndex = null;

    protected ?Controller\PathIndexForm $pathIndexForm = null;

    protected ?Controller\PathLock $pathLock = null;

    protected ?Controller\PathRestore $pathRestore = null;

    protected ?Controller\PathRevision $pathRevision = null;

    protected ?Controller\PathRevisions $pathRevisions = null;

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
        if (! empty($options['pathCreate'])
            && is_array($options['pathCreate'])
        ) {
            $this->pathCreate = new Controller\PathCreate($options['pathCreate']);
        }

        if (! empty($options['pathEdit'])
            && is_array($options['pathEdit'])
        ) {
            $this->pathEdit = new Controller\PathEdit($options['pathEdit']);
        }

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

        if (! empty($options['pathLock'])
            && is_array($options['pathLock'])
        ) {
            $this->pathLock = new Controller\PathLock($options['pathLock']);
        }

        if (! empty($options['pathRestore'])
            && is_array($options['pathRestore'])
        ) {
            $this->pathRestore = new Controller\PathRestore($options['pathRestore']);
        }

        if (! empty($options['pathRevision'])
            && is_array($options['pathRevision'])
        ) {
            $this->pathRevision = new Controller\PathRevision($options['pathRevision']);
        }

        if (! empty($options['pathRevisions'])
            && is_array($options['pathRevisions'])
        ) {
            $this->pathRevisions = new Controller\PathRevisions($options['pathRevisions']);
        }

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function pathCreate(array $options = []): Controller\PathCreate
    {
        if (empty($this->pathCreate)) {
            $this->pathCreate = new Controller\PathCreate($options);
            $this->properties['pathCreate'] = $this->pathCreate->apply()->toArray();
        }

        return $this->pathCreate;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function pathEdit(array $options = []): Controller\PathEdit
    {
        if (empty($this->pathEdit)) {
            $this->pathEdit = new Controller\PathEdit($options);
            $this->properties['pathEdit'] = $this->pathEdit->apply()->toArray();
        }

        return $this->pathEdit;
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

    /**
     * @param array<string, mixed> $options
     */
    public function pathLock(array $options = []): Controller\PathLock
    {
        if (empty($this->pathLock)) {
            $this->pathLock = new Controller\PathLock($options);
            $this->properties['pathLock'] = $this->pathLock->apply()->toArray();
        }

        return $this->pathLock;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function pathRestore(array $options = []): Controller\PathRestore
    {
        if (empty($this->pathRestore)) {
            $this->pathRestore = new Controller\PathRestore($options);
            $this->properties['pathRestore'] = $this->pathRestore->apply()->toArray();
        }

        return $this->pathRestore;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function pathRevision(array $options = []): Controller\PathRevision
    {
        if (empty($this->pathRevision)) {
            $this->pathRevision = new Controller\PathRevision($options);
            $this->properties['pathRevision'] = $this->pathRevision->apply()->toArray();
        }

        return $this->pathRevision;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function pathRevisions(array $options = []): Controller\PathRevisions
    {
        if (empty($this->pathRevisions)) {
            $this->pathRevisions = new Controller\PathRevisions($options);
            $this->properties['pathRevisions'] = $this->pathRevisions->apply()->toArray();
        }

        return $this->pathRevisions;
    }
}
