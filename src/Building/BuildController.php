<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;

/**
 * \Playground\Make\Swagger\Building\BuildController
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

        if (empty($name)) {
            $this->components->error('Docs: The name must be set in the [controller] configuration');

            return;
        }
        $type = $this->c->type();
        dump([
            '__METHOD__' => __METHOD__,
            '$type' => $type,
            '$this->c->type()' => $this->c->type(),
            '$this->options()' => $this->options(),
        ]);

        if (in_array($type, [
            'playground-api',
            'playground-resource',
            'resource',
            'api',
        ])) {
            // Add the tag for the model.
            $this->api->addTag($name, __('playground-make-swagger::tag.description', [
                'names' => $model_label_lower_plural,
            ]));

            $this->doc_controller_id($name);
            $this->doc_controller_index($name);
            $this->doc_controller_index_form($name);
            $this->doc_controller_lock($name);
            $this->doc_controller_restore($name);
            $this->doc_controller_revision($name);
            $this->doc_controller_revisions($name);
            $this->doc_controller_create($name);
            $this->doc_controller_edit($name);
        }
    }
}
