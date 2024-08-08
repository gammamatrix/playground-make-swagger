<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

/**
 * \Playground\Make\Swagger\Building\BuildInfo
 */
trait BuildInfo
{
    public function doc_info(): void
    {
        $options = [];

        if ($this->c->skeleton()) {
            $options['title'] = __('playground-make-swagger::api.info.title', [
                'organization' => $this->c->organization(),
                'module' => $this->c->module(),
            ]);

            $options['description'] = __('playground-make-package::composer.model.description', [
                'organization' => $this->c->organization(),
                'module' => $this->c->module(),
            ]);

        }

        $version = config('playground-make-swagger.version');

        if ($version && is_string($version)) {
            $option['version'] = $version;
        }

        $this->api->setOptions([
            'info' => $options,
        ]);

        $this->api->info()?->apply();

        $this->api->apply();

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        //     '$this->options()' => $this->options(),
        //     '$this->c' => $this->c,
        //     '$this->api' => $this->api,
        //     // '$this->model' => $this->model?->toArray(),
        //     // '$this->modelRevision' => $this->modelRevision?->toArray(),
        //     '$this->searches' => $this->searches,
        // ]);
    }
}
