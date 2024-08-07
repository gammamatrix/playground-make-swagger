<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

/**
 * \Playground\Make\Swagger\Building\BuildController
 */
trait BuildController
{
    protected string $build_controller_description = '';

    /**
     * @var array<string, mixed>
     */
    protected array $build_controller_properties = [];

    protected function doc_controller(): void
    {
        $this->build_controller_properties = [];

        $name = $this->c->name();
        if (empty($name)) {
            $this->components->error('Docs: The name must be set in the [controller] configuration');

            return;
        }
        $controller_type = $this->c->controller_type();
        if (in_array($controller_type, [
            'playground-api',
            'playground-resource',
            'resource',
            'api',
        ])) {
            // Add the tag for the model.
            $this->api->addTag($name);

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
