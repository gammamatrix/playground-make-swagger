<?php
/**
 * Playground
 */
declare(strict_types=1);
namespace Playground\Make\Swagger\Configuration\Swagger;

use Playground\Make\Configuration;

/**
 * \Playground\Make\Swagger\Configuration\Swagger\SwaggerConfiguration
 */
class SwaggerConfiguration extends Configuration\Configuration implements Configuration\Contracts\WithSkeleton
{
    use Configuration\Concerns\WithSkeleton;

    private ?Api $_parent = null;

    public function getParent(): ?Api
    {
        return $this->_parent;
    }

    public function setParent(Api $parent = null): self
    {
        $this->_parent = $parent;

        return $this;
    }
}
