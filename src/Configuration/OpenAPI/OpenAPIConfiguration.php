<?php

/**
 * Playground
 */
declare(strict_types=1);

namespace Playground\Make\OpenAPI\Configuration\OpenAPI;

use Playground\Make\Configuration;

/**
 * \Playground\Make\OpenAPI\Configuration\OpenAPI\OpenAPIConfiguration
 */
class OpenAPIConfiguration extends Configuration\Configuration implements Configuration\Contracts\WithSkeleton
{
    use Configuration\Concerns\WithSkeleton;

    private ?Api $_parent = null;

    public function getParent(): ?Api
    {
        return $this->_parent;
    }

    public function setParent(?Api $parent = null): self
    {
        $this->_parent = $parent;

        return $this;
    }
}
