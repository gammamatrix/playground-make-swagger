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
 * \Playground\Make\Swagger\Building\BuildModelColumns
 */
trait BuildModelColumns
{
    protected string $build_model_description = '';

    /**
     * @var array<string, array<string, mixed>>
     */
    protected array $build_model_properties = [];

    /**
     * @return array<string, mixed>
     */
    public function doc_model_columns(string $name, Model\Create $create): array
    {
        $this->build_model_properties = [];

        if ($create->primary()) {
            $this->doc_model_primary($name, $create->primary());
        }

        if ($create->ids()) {
            $this->doc_model_ids($name, $create->ids());
        }

        if ($create->timestamps()) {
            $this->doc_model_timestamps($name);
        }

        if ($create->softDeletes()) {
            $this->doc_model_softDeletes($name);
        }

        if ($create->dates()) {
            $this->doc_model_dates($name, $create->dates());
        }

        if ($create->permissions()) {
            foreach ($create->permissions() as $column => $meta) {
                $this->doc_model_column($name, $column, $meta->properties(), 'permissions');
            }
        }

        if ($create->status()) {
            foreach ($create->status() as $column => $meta) {
                $this->doc_model_column($name, $column, $meta->properties(), 'status');
            }
        }

        if ($create->ui()) {
            foreach ($create->ui() as $column => $meta) {
                $this->doc_model_column($name, $column, $meta->properties(), 'ui');
            }
        }

        if ($create->flags()) {
            foreach ($create->flags() as $column => $meta) {
                $this->doc_model_column($name, $column, $meta->properties(), 'flags');
            }
        }

        if ($create->columns()) {
            foreach ($create->columns() as $column => $meta) {
                $this->doc_model_column($name, $column, $meta->properties(), 'columns');
            }
        }

        if ($create->json()) {
            foreach ($create->json() as $column => $meta) {
                $this->doc_model_column($name, $column, $meta->properties(), 'json');
            }
        }

        return $this->build_model_properties;
    }

    /**
     * @param array<string, mixed> $meta
     */
    public function doc_model_column(string $name, string $column, array $meta, string $section): void
    {
        $type = ! empty($meta['type']) && is_string($meta['type']) ? $meta['type'] : '';
        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();

        if ($type === 'uuid') {
            $this->build_model_properties[$column] = [
                'description' => '',
                'type' => 'string',
                'format' => 'uuid',
            ];
        } elseif (in_array($type, [
            'string',
            'tinyText',
            'longText',
            'mediumText',
        ])) {

            $this->build_model_properties[$column] = [
                'description' => '',
                'type' => 'string',
                // 'default' => '',
            ];

            if (! empty($meta['size']) && is_numeric($meta['size']) && $meta['size'] > 0) {
                $this->build_model_properties[$column]['maxLength'] = intval($meta['size']);
            }

        } elseif (in_array($type, [
            'JSON_OBJECT',
        ])) {
            $this->build_model_properties[$column] = [
                'description' => '',
                'type' => 'object',
            ];
        } elseif (in_array($type, [
            'JSON_ARRAY',
        ])) {
            $this->build_model_properties[$column] = [
                'description' => '',
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                ],
            ];
        } elseif (in_array($type, [
            'boolean',
        ])) {
            $this->build_model_properties[$column] = [
                'description' => '',
                'type' => 'boolean',
            ];
        } elseif (in_array($type, [
            'bigInteger',
        ])) {
            $this->build_model_properties[$column] = [
                'description' => '',
                'type' => 'integer',
                'format' => 'int64',
            ];
        } elseif (in_array($type, [
            'tinyInteger',
            'smallInteger',
            'mediumInteger',
            'integer',
        ])) {
            $this->build_model_properties[$column] = [
                'description' => '',
                'type' => 'integer',
                'format' => 'int32',
            ];
        } elseif (in_array($type, [
            'double',
            'float',
            'decimal',
        ])) {
            $this->build_model_properties[$column] = [
                'description' => '',
                'type' => 'number',
            ];
            if (in_array($type, ['float', 'double'])) {
                $this->build_model_properties[$column]['format'] = $type;
            }
        }

        if (! empty($meta['nullable'])) {
            $this->build_model_properties[$column]['nullable'] = true;
        }

        if (in_array($section, [
            'json',
        ])) {
            $section = strtoupper($section);
        } else {
            $section = Str::of($section)->title()->toString();
        }

        if (empty($section)) {
            $column_text = Str::of($column)->replace('_', ' ');
            $section_text = '';
        } elseif (strtolower($section) === 'columns') {
            $column_text = '';
            $section_text = sprintf(
                'The %1$s of the %2$s.',
                Str::of($column)->replace('_', ' ')->toString(),
                $model_label_lower,
            );
        } else {

            if (strtolower($section) === strtolower($column)) {
                $column_text = '';
                $section_text = sprintf(
                    'The %1$s of the %2$s.',
                    Str::of($column)->replace('_', ' ')->toString(),
                    $model_label_lower,
                );
            } else {
                $column_text = Str::of($column)->replace('_', ' ')->toString();
                $section_text = Str::of($section)->finish(': ')->toString();
            }
        }

        $this->build_model_properties[$column]['description'] = sprintf(
            '%1$s%2$s',
            $section_text,
            $column_text
        );

        if (! empty($meta['html'])) {
            $this->build_model_properties[$column]['description'] .= ' Allows HTML.';
        }
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$column' => $column,
        //     '$meta' => $meta,
        // ]);
    }

    public function doc_model_primary(string $name, mixed $primary): void
    {
        if (is_string($primary) && $primary === 'uuid') {
            $this->build_model_properties['id'] = [
                'description' => 'The primary key.',
                'type' => 'string',
                'format' => 'uuid',
                'readOnly' => true,
            ];
        } elseif (is_string($primary) && $primary === 'integer') {
            $this->build_model_properties['id'] = [
                'description' => 'The primary key.',
                'type' => 'integer',
                'format' => 'int64',
                'readOnly' => true,
            ];
        }
    }

    /**
     * @param array<string, Model\CreateId> $ids
     */
    public function doc_model_ids(string $name, array $ids): void
    {
        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();

        foreach ($ids as $id => $createId) {

            $meta = $createId->properties();
            $type = ! empty($meta['type']) && is_string($meta['type']) ? $meta['type'] : '';

            if ($type === 'uuid') {
                $this->build_model_properties[$id] = [
                    'description' => '',
                    'type' => 'string',
                    'format' => 'uuid',
                ];
            } elseif ($type === 'integer') {
                $this->build_model_properties[$id] = [
                    'description' => '',
                    'type' => 'integer',
                    'format' => 'int64',
                ];
            } else {
                $this->build_model_properties[$id] = [
                    'description' => '',
                    'type' => 'string',
                ];
            }

            if (! empty($meta['nullable'])) {
                $this->build_model_properties[$id]['nullable'] = true;
            }

            if (Str::of($id)->endsWith('_by_id')) {
                $fk = Str::of($id)->beforeLast('_by_id')->replace('_', ' ')->lower()->finish(' by user')->toString();
            } else {
                $fk = Str::of($id)->beforeLast('_id')->replace('_', ' ')->lower()->toString();
            }

            if (preg_match('/^[aeiou]/', $fk)) {
                $article = 'an ';

            } else {
                $article = 'a ';
            }

            $preposition = ' of this ';
            if (! empty($meta['foreign'])) {
                $id_type = 'Foreign key: links ';
                $preposition = ' to this ';
            } elseif (Str::of($id)->endsWith('_type')) {
                $id_type = 'The';
                $article = ' ';
            } else {
                $id_type = 'ID: ';
            }

            $this->build_model_properties[$id]['description'] = sprintf(
                '%1$s%2$s%3$s%4$s%5$s.',
                $id_type,
                $article,
                $fk,
                $preposition,
                $model_label_lower,
            );
        }
    }

    public function doc_model_softDeletes(string $name): void
    {
        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();

        $this->build_model_properties['deleted_at'] = [
            'description' => sprintf(
                'Denotes the date and time, the %1$s was put in the trash.',
                $model_label_lower
            ),
            'type' => 'string',
            'format' => 'date-time',
            'nullable' => true,
        ];
    }

    public function doc_model_timestamps(string $name): void
    {
        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();

        $this->build_model_properties['created_at'] = [
            'description' => sprintf(
                'Denotes the date and time, the %1$s was created.',
                $model_label_lower
            ),
            'type' => 'string',
            'format' => 'date-time',
            'readOnly' => true,
        ];

        $this->build_model_properties['updated_at'] = [
            'description' => sprintf(
                'Denotes the date and time, the %1$s was last modified.',
                $model_label_lower
            ),
            'type' => 'string',
            'format' => 'date-time',
            'readOnly' => true,
        ];
    }

    /**
     * @param array<string, Model\CreateDate> $dates
     */
    public function doc_model_dates(string $name, array $dates): void
    {
        $model_label_lower = Str::of($name)->kebab()->replace('-', ' ')->lower()->toString();

        foreach ($dates as $date => $createDate) {
            $meta = $createDate->properties();

            $this->build_model_properties[$date] = [
                'description' => sprintf(
                    'The %2$s date for this %1$s.',
                    $model_label_lower,
                    Str::of($date)->replace('_', ' ')->lower()->toString()
                ),
                'type' => 'string',
                'format' => 'date-time',
            ];
            if (! empty($meta['nullable'])) {
                $this->build_model_properties[$date]['nullable'] = true;
            }
        }
    }
}
