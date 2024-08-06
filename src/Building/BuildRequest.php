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
    protected function doc_request_id(
        string $name,
        string $controller_type = ''
    ): void {

        $model = $this->model;
        $create = $model?->create();
        if (! $model || ! $create) {
            return;
        }

        $module = $this->c->module();
        $description = sprintf(
            'The %1$s%2$s%3$s fillable properties.',
            $module,
            empty($module) ? '' : ' ',
            $name
        );

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
            'description' => $description,
            'type' => 'object',
            'properties' => $patch,
        ]);
    }

    // protected function doc_request_index(
    //     array &$config,
    //     string $name,
    //     string $controller_type = ''
    // ): void {

    //     $file_model = sprintf(
    //         '%1$s/docs/models/%2$s.yml',
    //         $this->getPackageFolder(),
    //         Str::of($name)->kebab()->toString()
    //     );
    //     $path_docs_model = $this->laravel->storagePath().$file_model;

    //     $model = $this->yaml_read($path_docs_model);
    //     $hasFillable = ! empty($this->model) && ! empty($this->model['fillable']) && is_array($this->model['fillable']);
    //     $hasProperties = ! empty($model['properties']) && is_array($model['properties']);

    //     if (! $hasFillable) {
    //         if (array_key_exists('requestBody', $config['post'])) {
    //             unset($config['post']['requestBody']);
    //         }
    //     }

    //     if (array_key_exists('requestBody', $config['post'])) {
    //         if (empty($config['post']['requestBody']['content'])
    //             || ! is_array($config['post']['requestBody']['content'])
    //         ) {
    //             $config['post']['requestBody']['content'] = [];
    //         }
    //         if (empty($config['post']['requestBody']['content']['application/json'])
    //             || ! is_array($config['post']['requestBody']['content']['application/json'])
    //         ) {
    //             $config['post']['requestBody']['content']['application/json'] = [];
    //         }
    //         if (empty($config['post']['requestBody']['content']['application/json']['schema'])
    //             || ! is_array($config['post']['requestBody']['content']['application/json']['schema'])
    //         ) {
    //             $config['post']['requestBody']['content']['application/json']['schema'] = [];
    //         }
    //         $config['post']['requestBody']['content']['application/json']['schema']['$ref'] = sprintf(
    //             $config['post']['requestBody']['content']['application/json']['schema']['$ref'],
    //             Str::of($name)->lower()->toString()
    //         );
    //     }

    // $this->build_request_description = sprintf(
    //     'The %1$s%2$s fillable properties.',
    //     $this->api->module(),
    //     $name
    // );
    //     $post = [
    //         'description' => sprintf(
    //             'The %1$s%2$s fillable properties.',
    //             empty($this->configuration['module']) ? '' : $this->configuration['module'].' ',
    //             Str::of($name)
    //         ),
    //         'type' => 'object',
    //         'properties' => [],
    //     ];

    //     if ($hasProperties && $hasFillable) {

    //         foreach ($model['properties'] as $column => $property) {
    //             if (in_array($column, $this->model['fillable'])) {

    //                 $column_meta = $this->buildClass_model_meta($column, $this->model);

    //                 if (! empty($column_meta['readOnly'])) {
    //                     continue;
    //                 }

    //                 $post['properties'][$column] = $property;
    //             }
    //         }
    //     }

    //     // dd([
    //     //     '__METHOD__' => __METHOD__,
    //     //     '$hasFillable' => $hasFillable,
    //     //     '$hasProperties' => $hasProperties,
    //     //     '$name' => $name,
    //     //     // '$file_model' => $file_model,
    //     //     // '$path_docs_model' => $path_docs_model,
    //     //     // '$model' => $model,
    //     //     '$controller_type' => $controller_type,
    //     //     // '$config' => $config,
    //     //     // '$this->model' => $this->model,
    //     //     // '$this->configuration' => $this->configuration,
    //     //     // '$this->searches' => $this->searches,
    //     //     // '$this->arguments()' => $this->arguments(),
    //     //     // '$this->options()' => $this->options(),
    //     //     '$post' => $post,
    //     // ]);

    //     $file = sprintf(
    //         'requests/%1$s/post.yml',
    //         Str::of($name)->kebab()->toString()
    //     );

    //     $this->yaml_write($file, $post);
    // }
}
