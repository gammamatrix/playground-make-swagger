<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Configuration\Model;

// use Playground\Make\Configuration\Model\Create;
// use Playground\Make\Configuration\Model\CreateDate;
// use Playground\Make\Configuration\Model\CreateId;

/**
 * \Playground\Make\Swagger\Building\BuildModel
 */
trait BuildModel
{
    public function doc_model(): void
    {
        $this->build_model_properties = [];

        $name = $this->model?->name();
        $create = $this->model?->create();
        if (is_null($this->model) || ! $create || ! $name) {
            return;
        }

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();

        $path_docs_model = $this->laravel->storagePath().$this->folder().'/models';
        $this->makeDirectory($path_docs_model);
        $this->components->info(sprintf('Docs: [%s] exists.', $path_docs_model));

        $file = sprintf(
            'models/%1$s.yml',
            $model_route
        );

        $this->api->components()->addSchema($name, $file);

        $this->yaml_write($file, [
            'description' => __('playground-make-swagger::model.module.description', [
                'module' => $this->model->module(),
                'name' => $model_label_lower,
            ]),
            'type' => 'object',
            'properties' => $this->doc_model_columns($name, $create),
        ]);
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$name' => $name,
        //     '$model_label_lower' => $model_label_lower,
        //     '$model_route' => $model_route,
        // ]);
    }

    public function doc_model_revision(): void
    {
        $this->build_model_properties = [];

        $name = $this->modelRevision?->name();
        $create = $this->modelRevision?->create();
        if (is_null($this->modelRevision) || ! $create || ! $name) {
            return;
        }

        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();
        $model_route = Str::of($name)->kebab()->toString();

        $path_docs_model = $this->laravel->storagePath().$this->folder().'/models';
        $this->makeDirectory($path_docs_model);
        $this->components->info(sprintf('Docs: [%s] exists.', $path_docs_model));

        $file = sprintf(
            'models/%1$s.yml',
            $model_route
        );

        $this->api->components()->addSchema($name, $file);

        $this->yaml_write($file, [
            'description' => __('playground-make-swagger::model.module.description', [
                'module' => $this->modelRevision->module(),
                'name' => $model_label_lower,
            ]),
            'type' => 'object',
            'properties' => $this->doc_model_columns($name, $create),
        ]);
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     // '$this->build_model_properties' => $this->build_model_properties,
        //     '$columns' => $columns,
        // ]);
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$model_label_lower' => $model_label_lower,
        //     '$model_route' => $model_route,
        //     '$name' => $name,
        // ]);
    }
}
