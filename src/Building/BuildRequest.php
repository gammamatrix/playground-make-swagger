<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;

/**
 * \Playground\Make\Swagger\Building\BuildRequest
 */
trait BuildRequest
{
    protected function doc_request_id(string $name): void
    {
        $model = $this->model;
        $create = $model?->create();

        if (! $model || ! $create) {
            return;
        }

        $module = $this->c->module();
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$model' => $model,
        //     '$create' => $create,
        //     '$module' => $module,
        //     // '$this->searches' => $this->searches,
        //     // '$this->modelRevision' => $this->modelRevision?->toArray(),
        // ]);

        $fillable = $model->fillable();

        $columns = $this->doc_model_columns(
            $name,
            $create
        );

        /**
         * @var array<string, mixed>
         */
        $patch = [];

        foreach ($columns as $column => $meta) {

            if (! is_array($meta)
                || ! empty($meta['readOnly'])
                || ! in_array($column, $fillable)
            ) {
                continue;
            }

            $patch[$column] = $meta;
        }

        $file = sprintf(
            'requests/%1$s/patch.yml',
            Str::of($name)->kebab()
        );

        $this->yaml_write($file, [
            'description' => __('playground-make-swagger::request.fillable.description', [
                'module' => $module,
                'name' => $name,
            ]),
            'type' => 'object',
            'properties' => $patch,
        ]);
    }

    protected function doc_request_index(string $name): void
    {
        $model = $this->model;
        $create = $model?->create();

        if (! $model || ! $create) {
            return;
        }

        $module = $this->c->module();
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$model' => $model,
        //     '$create' => $create,
        //     '$module' => $module,
        //     // '$this->searches' => $this->searches,
        //     // '$this->modelRevision' => $this->modelRevision?->toArray(),
        // ]);

        $fillable = $model->fillable();

        $columns = $this->doc_model_columns(
            $name,
            $create
        );

        /**
         * @var array<string, mixed>
         */
        $post = [];

        foreach ($columns as $column => $meta) {

            if (! is_array($meta)
                || ! empty($meta['readOnly'])
                || ! in_array($column, $fillable)
            ) {
                continue;
            }

            $post[$column] = $meta;
        }

        $file = sprintf(
            'requests/%1$s/post.yml',
            Str::of($name)->kebab()
        );

        $this->yaml_write($file, [
            'description' => __('playground-make-swagger::request.fillable.description', [
                'module' => $module,
                'name' => $name,
            ]),
            'type' => 'object',
            'properties' => $post,
        ]);
    }

    protected function doc_request_index_form(string $name): void
    {
        $model = $this->model;
        $create = $model?->create();

        if (! $model || ! $create) {
            return;
        }

        $module = $this->c->module();
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$model' => $model,
        //     '$create' => $create,
        //     '$module' => $module,
        //     // '$this->searches' => $this->searches,
        //     // '$this->modelRevision' => $this->modelRevision?->toArray(),
        // ]);

        $fillable = $model->fillable();

        $columns = $this->doc_model_columns(
            $name,
            $create
        );

        /**
         * @var array<string, mixed>
         */
        $properties = [
            'perPage' => [
                'description' => __('playground-make-swagger::request.perPage.description'),
                'type' => 'integer',
                'example' => 10,
            ],
            'page' => [
                'description' => __('playground-make-swagger::request.page.description'),
                'type' => 'integer',
                'example' => 1,
            ],
            'offset' => [
                'description' => __('playground-make-swagger::request.offset.description'),
                'type' => 'integer',
                'example' => 0,
            ],
            'filter' => [
                'description' => __('playground-make-swagger::request.filter.description', ['name' => $name]),
                'type' => 'object',
                'properties' => [
                    'trash' => [
                        'description' => __('playground-make-swagger::request.filter.trash.description'),
                        'type' => 'integer',
                        'example' => 1,
                        'enum' => ['with', 'only', ''],
                    ],
                    'page_type' => [
                        'description' => __('playground-make-swagger::request.filter.type.description', ['name' => $name]),
                        'type' => 'integer',
                        'example' => 1,
                    ],
                ],
            ],
        ];

        $file = sprintf(
            'requests/%1$s/form.yml',
            Str::of($name)->kebab()
        );

        $this->yaml_write($file, [
            'description' => __('playground-make-swagger::request.index.form.description', [
                'module' => $module,
                'name' => $name,
            ]),
            'type' => 'object',
            'properties' => $properties,
        ]);
    }
}
