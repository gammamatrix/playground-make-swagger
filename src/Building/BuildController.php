<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Make\OpenAPI\Building;

use Illuminate\Support\Str;

/**
 * \Playground\Make\OpenAPI\Building\BuildController
 */
trait BuildController
{
    /**
     * @var array<string, mixed>
     */
    protected array $build_controller_properties = [];

    protected function doc_controller(): void
    {
        $this->build_controller_properties = [];

        $name = $this->c->name();

        $model_label_lower_plural = Str::of($name)->kebab()->replace('-', ' ')->lower()->plural()->toString();

        $hasRevision = $this->hasOption('revision') && $this->option('revision');

        if (empty($name)) {
            $this->components->error('Docs: The name must be set in the [controller] configuration');

            return;
        }
        $type = $this->c->type();

        if (in_array($type, [
            'playground-api',
            'playground-resource',
            'resource',
            'api',
        ])) {

            if (! $hasRevision) {
                $hasRevision = $this->c->name() === $this->model?->name()
                    && $this->model?->revision() === false
                    && $this->modelRevision?->revision() === true;
            }

            // Add the tag for the model.
            $this->api->addTag($name, __('playground-make-openapi::tag.description', [
                'names' => $model_label_lower_plural,
            ]));

            $this->doc_controller_id($name);
            $this->doc_controller_index($name);
            $this->doc_controller_index_form($name);
            $this->doc_controller_lock($name);
            $this->doc_controller_restore($name);
            if ($hasRevision) {
                $this->doc_controller_revision($name);
                $this->doc_controller_revisions($name);
            }
            $this->doc_controller_create($name);
            $this->doc_controller_edit($name);
        }
        //         dd([
        //             '__METHOD__' => __METHOD__,
        //             '$type' => $type,
        //             '$hasRevision' => $hasRevision,
        //             '$this->route_prefix' => $this->route_prefix,
        //             '$this->isApi' => $this->isApi,
        //             '$this->isResource' => $this->isResource,
        //             '$this->c->type()' => $this->c->type(),
        //             '$this->options()' => $this->options(),
        // //             '$this->model' => $this->model?->toArray(),
        //             '$this->c->name()' => $this->c->name(),
        //             '$this->model->name()' => $this->model?->name(),
        //             '$this->model->revision()' => $this->model?->revision(),
        //             '$this->model->revision()' => $this->model?->revision(),
        //             '$this->modelRevision' => $this->modelRevision?->revision(),
        //         ]);
    }
}
