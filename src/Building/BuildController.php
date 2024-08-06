<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Illuminate\Support\Str;
use Playground\Make\Swagger\Configuration\Swagger\Controller;

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

            $this->doc_controller_id($name, $controller_type);
            $this->doc_controller_index($name, $controller_type);
            $this->doc_controller_index_form($name, $controller_type);
            $this->doc_controller_lock($name, $controller_type);
            $this->doc_controller_restore($name, $controller_type);
            $this->doc_controller_create($name, $controller_type);
            $this->doc_controller_edit($name, $controller_type);

        }
    }

    protected function doc_controller_id(
        string $name,
        string $controller_type = ''
    ): void {

        $pathId = $this->api->controllers()->pathId();

        $this->doc_controller_id_config($name, $pathId);

        // $this->doc_request_id($name, $controller_type);

        $path = sprintf(
            '/api/%1$s/{id}',
            Str::of($name)->plural()->kebab()->toString()
        );
        $file = sprintf(
            'paths/%1$s/id.yml',
            Str::of($name)->plural()->kebab()->toString()
        );

        $this->api->addPath($path, $file);
        $this->api->apply();

        $pathId->apply();

        $this->yaml_write($file, $pathId->toArray());
    }

    protected function doc_controller_id_config(
        string $name,
        Controller\PathId $pathId
    ): void {

        $pathId->addParameter($name, [
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s id.', Str::of($name)->lower()->toString()),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $getMethod = $pathId->getMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Get a %1$s by id.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'get_%1$s',
                Str::of($name)->lower()->toString()
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The %1$s data.',
                        Str::of($name)->lower()->toString()
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s.yml',
                                        Str::of($name)->lower()->kebab()->toString()
                                    ),
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $getMethod?->apply();

        $deleteMethod = $pathId->deleteMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Delete a %1$s by id.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'delete_%1$s',
                Str::of($name)->lower()->toString()
            ),
            'responses' => [
                [
                    'code' => 204,
                    'description' => sprintf(
                        'The %1$s has been deleted.',
                        Str::of($name)->lower()->toString()
                    ),
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
                [
                    'code' => 423,
                    'description' => sprintf(
                        'The %1$s is locked. Unlock to delete.',
                        Str::of($name)->lower()->toString()
                    ),
                ],
            ],
        ]);

        $deleteMethod?->apply();

        $patchMethod = $pathId->patchMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Patch a %1$s by id.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'patch_%1$s',
                Str::of($name)->lower()->toString()
            ),

            // requestBody:
            // content:
            //   application/json:
            //     schema:
            //       $ref: ../../requests/page/patch.yml
            'requestBody' => [
                'content' => [
                    'type' => 'application/json',
                    'schema' => [
                        '$ref' => sprintf(
                            '../../requests/%s/patch.yml',
                            Str::of($name)->lower()->kebab()->toString()
                        ),
                    ],
                ],
            ],

            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The %1$s has been patched.',
                        Str::of($name)->lower()->toString()
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s.yml',
                                        Str::of($name)->lower()->kebab()->toString()
                                    ),
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
                [
                    'code' => 422,
                    'description' => 'Validation error',
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'errors' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'title' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string',
                                                'example' => 'The title field is required.',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 423,
                    'description' => sprintf(
                        'The %1$s is locked. Unlock to patch.',
                        Str::of($name)->lower()->toString()
                    ),
                ],
            ],
        ]);

        $patchMethod?->apply();

        //     if (! empty($config['get']['responses']) && ! empty($config['get']['responses'][200])) {
        //         $config['get']['responses'][200]['description'] = sprintf(
        //             $config['get']['responses'][200]['description'],
        //             Str::of($name)->lower()
        //         );
        //         $config['get']['responses'][200]['content']['application/json']['schema']['properties']['data']['$ref'] = sprintf(
        //             $config['get']['responses'][200]['content']['application/json']['schema']['properties']['data']['$ref'],
        //             Str::of($name)->kebab()
        //         );
        //     }

        // if (! empty($config['delete']) && ! empty($name)) {

        //     if (empty($config['delete']['tags']) || ! is_array($config['delete']['tags'])) {
        //         $config['delete']['tags'] = [];
        //     }

        //     $tag = Str::of($name)->title()->toString();

        //     if (! in_array($tag, $config['delete']['tags'])) {
        //         $config['delete']['tags'][] = $tag;
        //     }

        //     if (! empty($config['delete']['summary'])) {
        //         $config['delete']['summary'] = sprintf(
        //             $config['delete']['summary'],
        //             Str::of($name)->lower()->toString()
        //         );
        //     }

        //     if (! empty($config['delete']['operationId'])) {
        //         $config['delete']['operationId'] = sprintf(
        //             $config['delete']['operationId'],
        //             Str::of($name)->snake()->toString()
        //         );
        //     }

        //     if (! empty($config['delete']['responses']) && ! empty($config['delete']['responses'][204])) {
        //         $config['delete']['responses'][204]['description'] = sprintf(
        //             $config['delete']['responses'][204]['description'],
        //             Str::of($name)->lower()->toString()
        //         );
        //         $config['delete']['responses'][423]['description'] = sprintf(
        //             $config['delete']['responses'][423]['description'],
        //             Str::of($name)->lower()->toString()
        //         );
        //     }
        // }

        // if (! empty($config['patch']) && ! empty($name)) {

        //     if (empty($config['patch']['tags']) || ! is_array($config['patch']['tags'])) {
        //         $config['patch']['tags'] = [];
        //     }

        //     $tag = Str::of($name)->title()->toString();

        //     if (! in_array($tag, $config['patch']['tags'])) {
        //         $config['patch']['tags'][] = $tag;
        //     }

        //     if (! empty($config['patch']['summary'])) {
        //         $config['patch']['summary'] = sprintf(
        //             $config['patch']['summary'],
        //             Str::of($name)->lower()->toString()
        //         );
        //     }

        //     if (! empty($config['patch']['operationId'])) {
        //         $config['patch']['operationId'] = sprintf(
        //             $config['patch']['operationId'],
        //             Str::of($name)->snake()->toString()
        //         );
        //     }

        //     if (! empty($config['patch']['responses']) && ! empty($config['patch']['responses'][200])) {
        //         $config['patch']['responses'][200]['description'] = sprintf(
        //             $config['patch']['responses'][200]['description'],
        //             Str::of($name)->lower()->toString()
        //         );
        //         $config['patch']['responses'][200]['content']['application/json']['schema']['properties']['data']['$ref'] = sprintf(
        //             $config['patch']['responses'][200]['content']['application/json']['schema']['properties']['data']['$ref'],
        //             Str::of($name)->kebab()->toString()
        //         );
        //     }
        // }
    }

    protected function doc_controller_index(
        string $name,
        string $controller_type = ''
    ): void {

        $pathIndex = $this->api->controllers()->pathIndex();

        $this->doc_controller_index_config($name, $pathIndex);

        // $this->doc_request_index($name, $controller_type);

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$controller_type' => $controller_type,
        //     '$config' => $config,
        //     // '$this->model' => $this->model,
        //     // '$this->configuration' => $this->configuration,
        //     // '$this->searches' => $this->searches,
        //     // '$this->arguments()' => $this->arguments(),
        //     // '$this->options()' => $this->options(),
        // ]);

        $path = sprintf(
            '/api/%1$s',
            Str::of($name)->plural()->kebab()->toString()
        );
        $file = sprintf(
            'paths/%1$s/index.yml',
            Str::of($name)->plural()->kebab()->toString()
        );

        $this->api->addPath($path, $file);

        $this->yaml_write($file, $pathIndex->toArray());
    }

    protected function doc_controller_index_config(
        string $name,
        Controller\PathIndex $pathIndex
    ): void {

        $model_lower_plural = Str::of($name)->plural()->snake()->replace('_', ' ')->toString();
        $model_snake = Str::of($name)->plural()->snake()->toString();

        $getMethod = $pathIndex->getMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Get %1$s from the index.',
                $model_lower_plural
            ),
            'operationId' => sprintf(
                'get_%1$s_index',
                $model_snake
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'Get the %1$s from the index.',
                        $model_lower_plural
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => sprintf(
                                            '../../models/%s.yml',
                                            Str::of($name)->lower()->kebab()->toString()
                                        ),
                                    ],
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $getMethod?->apply();

        $postMethod = $pathIndex->postMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Create a %1$s.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'post_%1$s',
                Str::of($name)->lower()->toString()
            ),

            'requestBody' => [
                'content' => [
                    'type' => 'application/json',
                    'schema' => [
                        '$ref' => sprintf(
                            '../../requests/%s/post.yml',
                            Str::of($name)->lower()->kebab()->toString()
                        ),
                    ],
                ],
            ],

            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The created %1$s.',
                        Str::of($name)->lower()->toString()
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s.yml',
                                        Str::of($name)->lower()->kebab()->toString()
                                    ),
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
                [
                    'code' => 422,
                    'description' => 'Validation error',
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'errors' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'title' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'string',
                                                'example' => 'The title field is required.',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 423,
                    'description' => sprintf(
                        'The %1$s is locked. Unlock to patch.',
                        Str::of($name)->lower()->toString()
                    ),
                ],
            ],
        ]);

        $postMethod?->apply();
    }

    protected function doc_controller_index_form(
        string $name,
        string $controller_type = ''
    ): void {

        $pathIndexForm = $this->api->controllers()->pathIndexForm();

        $this->doc_controller_index_form_config($name, $pathIndexForm);

        $path = sprintf(
            '/api/%1$s',
            Str::of($name)->plural()->kebab()->toString()
        );
        $file = sprintf(
            'paths/%1$s/index-form.yml',
            Str::of($name)->plural()->kebab()->toString()
        );

        $this->api->addPath($path, $file);

        $this->yaml_write($file, $pathIndexForm->toArray());
    }

    protected function doc_controller_index_form_config(
        string $name,
        Controller\PathIndexForm $pathIndexForm
    ): void {

        $model_lower_plural = Str::of($name)->plural()->snake()->replace('_', ' ')->toString();
        $model_snake = Str::of($name)->plural()->snake()->toString();

        $postMethod = $pathIndexForm->postMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Get %1$s from the index using POST.',
                $model_lower_plural
            ),
            'operationId' => sprintf(
                'post_%1$s_index',
                $model_snake
            ),
            'requestBody' => [
                'content' => [
                    'type' => 'application/json',
                    'schema' => [
                        '$ref' => '../../requests/page/form.yml',
                    ],
                ],
            ],
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'Get the %1$s from the index.',
                        $model_lower_plural
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => sprintf(
                                            '../../models/%s.yml',
                                            Str::of($name)->lower()->kebab()->toString()
                                        ),
                                    ],
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $postMethod?->apply();
    }

    protected function doc_controller_lock(
        string $name,
        string $controller_type = ''
    ): void {

        $pathLock = $this->api->controllers()->pathLock();

        $this->doc_controller_lock_config($name, $pathLock);

        $path = sprintf(
            '/api/%1$s/lock/{id}',
            Str::of($name)->plural()->kebab()->toString()
        );
        $file = sprintf(
            'paths/%1$s/lock.yml',
            Str::of($name)->plural()->kebab()->toString()
        );

        $this->api->addPath($path, $file);
        $this->yaml_write($file, $pathLock->toArray());
    }

    protected function doc_controller_lock_config(
        string $name,
        Controller\PathLock $pathLock
    ): void {

        $pathLock->addParameter($name, [
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s id.', Str::of($name)->lower()->toString()),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $deleteMethod = $pathLock->deleteMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Delete a %1$s by id.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'unlock_%1$s',
                Str::of($name)->lower()->toString()
            ),
            'responses' => [
                [
                    'code' => 204,
                    'description' => sprintf(
                        'The %1$s has been unlocked.',
                        Str::of($name)->lower()->toString()
                    ),
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $deleteMethod?->apply();

        $putMethod = $pathLock->putMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Lock a %1$s by ID.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'lock_%1$s',
                Str::of($name)->lower()->toString()
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The unlocked %1$s.',
                        Str::of($name)->lower()->toString()
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s.yml',
                                        Str::of($name)->lower()->kebab()->toString()
                                    ),
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $putMethod?->apply();
    }

    protected function doc_controller_restore(
        string $name,
        string $controller_type = ''
    ): void {
        $pathRestore = $this->api->controllers()->pathRestore();

        $this->doc_controller_restore_config($name, $pathRestore);

        $path = sprintf(
            '/api/%1$s/restore/{id}',
            Str::of($name)->plural()->kebab()->toString()
        );
        $file = sprintf(
            'paths/%1$s/restore.yml',
            Str::of($name)->plural()->kebab()->toString()
        );

        $this->api->addPath($path, $file);

        $this->yaml_write($file, $pathRestore->toArray());
    }

    protected function doc_controller_restore_config(
        string $name,
        Controller\PathRestore $pathRestore
    ): void {

        $pathRestore->addParameter($name, [
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s id.', Str::of($name)->lower()->toString()),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $putMethod = $pathRestore->putMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Restore a %1$s from the trash by ID.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'restore_%1$s',
                Str::of($name)->lower()->toString()
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The restored %1$s.',
                        Str::of($name)->lower()->toString()
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s.yml',
                                        Str::of($name)->lower()->kebab()->toString()
                                    ),
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $putMethod?->apply();
    }

    protected function doc_controller_create(
        string $name,
        string $controller_type = ''
    ): void {
        $pathCreate = $this->api->controllers()->pathCreate();

        $this->doc_controller_create_config($name, $pathCreate);

        $path = sprintf(
            '/api/%1$s/create',
            Str::of($name)->plural()->kebab()->toString()
        );
        $file = sprintf(
            'paths/%1$s/create.yml',
            Str::of($name)->plural()->kebab()->toString()
        );

        $this->api->addPath($path, $file);

        $this->yaml_write($file, $pathCreate->toArray());
    }

    protected function doc_controller_create_config(
        string $name,
        Controller\PathCreate $pathCreate
    ): void {

        $getMethod = $pathCreate->getMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Create a %1$s form.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'create_%1$s',
                Str::of($name)->lower()->toString()
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The create %1$s information.',
                        Str::of($name)->lower()->toString()
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s.yml',
                                        Str::of($name)->lower()->kebab()->toString()
                                    ),
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $getMethod?->apply();
    }

    protected function doc_controller_edit(
        string $name,
        string $controller_type = ''
    ): void {
        $pathEdit = $this->api->controllers()->pathEdit();

        $this->doc_controller_edit_config($name, $pathEdit);

        $path = sprintf(
            '/api/%1$s/edit/{id}',
            Str::of($name)->plural()->kebab()->toString()
        );
        $file = sprintf(
            'paths/%1$s/edit.yml',
            Str::of($name)->plural()->kebab()->toString()
        );

        $this->api->addPath($path, $file);

        $this->yaml_write($file, $pathEdit->toArray());
    }

    protected function doc_controller_edit_config(
        string $name,
        Controller\PathEdit $pathEdit
    ): void {

        $pathEdit->addParameter($name, [
            'in' => 'path',
            'name' => 'id',
            'required' => true,
            'description' => sprintf('The %1$s id.', Str::of($name)->lower()->toString()),
            'schema' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
        ]);

        $getMethod = $pathEdit->getMethod([
            'tags' => [
                Str::of($name)->title()->toString(),
            ],
            'summary' => sprintf(
                'Edit a %1$s form.',
                Str::of($name)->lower()->toString()
            ),
            'operationId' => sprintf(
                'edit_%1$s',
                Str::of($name)->lower()->toString()
            ),
            'responses' => [
                [
                    'code' => 200,
                    'description' => sprintf(
                        'The edit %1$s information.',
                        Str::of($name)->lower()->toString()
                    ),
                    'content' => [
                        'type' => 'application/json',
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'data' => [
                                    '$ref' => sprintf(
                                        '../../models/%s.yml',
                                        Str::of($name)->lower()->kebab()->toString()
                                    ),
                                ],
                                'meta' => [
                                    'type' => 'object',

                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'code' => 401,
                    'description' => 'Unauthorized',
                ],
                [
                    'code' => 403,
                    'description' => 'Forbidden',
                ],
            ],
        ]);

        $getMethod?->apply();
    }
}
