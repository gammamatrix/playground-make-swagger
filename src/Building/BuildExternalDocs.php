<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;

/**
 * \Playground\Make\Swagger\Building\BuildExternalDocs
 */
trait BuildExternalDocs
{
    public function doc_external_docs(): void
    {
        /**
         * @var array<string, array<string, mixed>> $config
         */
        $config = config('playground-make-swagger.externalDocs');

        $externalDocs = [];
        $module_route = Str::of($this->c->module())->lower()->kebab()->toString();

        if (is_array($config)
            && ! empty($config['url'])
            && is_string($config['url'])
        ) {
            $externalDocs['url'] = sprintf(
                $config['url'],
                $module_route
            );
            if (! empty($config['description']) && is_string($config['description'])) {
                $externalDocs['description'] = sprintf(
                    $config['description'],
                    $this->c->module()
                );
            }
        }

        if ($externalDocs) {
            $this->api->setOptions([
                'externalDocs' => $externalDocs,
            ]);
            $this->api->externalDocs()?->apply();
            $this->api->apply();
        }

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$config' => $config,
        //     '$externalDocs' => $externalDocs,
        //     '$this->options()' => $this->options(),
        //     // '$this->c' => $this->c,
        //     // '$this->api' => $this->api,
        //     // '$this->model' => $this->model?->toArray(),
        //     // '$this->modelRevision' => $this->modelRevision?->toArray(),
        //     '$this->searches' => $this->searches,
        // ]);
    }
}
