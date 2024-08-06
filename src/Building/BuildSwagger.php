<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Building;

use Playground\Make\Swagger\Configuration\Swagger\Api;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * \Playground\Make\Swagger\Building\BuildSwagger
 */
trait BuildSwagger
{
    protected Api $api;

    /**
     * @var array<string, array<string, string>>
     */
    protected array $tags = [];

    protected function init_swagger_api(): void
    {
        if (empty($this->api)) {
            $this->api = new Api;
            if ($this->option('skeleton')) {
                $this->api->withSkeleton();
            }
        }
    }

    // public function doc_add_schema($name, $path): void
    // {
    //     if (empty($this->api['components']) || ! is_array($this->api['components'])) {
    //         $this->api['components'] = [];
    //     }
    //     if (empty($this->api['components']['schemas']) || ! is_array($this->api['components']['schemas'])) {
    //         $this->api['components']['schemas'] = [];
    //     }
    //     $this->api['components']['schemas'][$name] = [
    //         '$ref' => $path,
    //     ];
    // }

    public function save_base_file(): ?string
    {
        return $this->yaml_write('api.yml', $this->api->apply()->toArray());
    }

    public function load_base_file(): Api
    {
        $file = $this->getStub();

        $reload = true;

        $path_docs_api = $this->laravel->storagePath().$file;
        $api = null;
        if (file_exists($path_docs_api)) {
            $api = $this->yaml_read($path_docs_api);
            if (is_array($api) && ! empty($api)) {
                $this->api = new Api;
                if ($this->option('skeleton')) {
                    $this->api->withSkeleton();
                }
                $this->api->setOptions($api);
                $reload = false;
            }
        }

        if ($reload) {
            $this->init_swagger_api();
        }
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$file' => $file,
        //     '$path_docs_api' => $path_docs_api,
        //     '$api' => $api,
        //     '$this->api' => $this->api,
        //     'file_exists($path_docs_api)' => file_exists($path_docs_api),
        //     // '$this->model' => $this->model,
        //     // '$this->configuration' => $this->configuration,
        //     // '$this->searches' => $this->searches,
        //     // '$this->arguments()' => $this->arguments(),
        //     // '$this->options()' => $this->options(),
        // ]);

        return $this->api;
    }

    // public function base_file(array $sections = []): ?array
    // {

    //     $this->api = config('playground-stub.api');

    //     return $this->api;
    // }

    /**
     * @return ?array<mixed>
     */
    public function yaml_read(string $file): ?array
    {
        $content = null;

        if (! file_exists($file)) {
            $this->components->error(sprintf('Docs: the YAML file [%1$s] does not exist', $file));

            return null;
        }

        try {
            $content = Yaml::parseFile($file);
            // dd([
            //     '__METHOD__' => __METHOD__,
            //     '$file' => $file,
            //     '$content' => $content,
            // ]);
        } catch (ParseException $exception) {
            \Log::error(__METHOD__, [
                '$file' => $file,
                '$exception' => $exception,
                'error' => $exception->getMessage(),
            ]);
        }

        return is_array($content) ? $content : null;
    }

    /**
     * @param array<mixed> $contents
     */
    public function yaml_write(string $file, array $contents = []): ?string
    {
        $content = null;

        $destination = sprintf(
            '%1$s/%2$s',
            $this->folder(),
            $file
        );
        $full_path = $this->laravel->storagePath().$destination;

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$file' => $file,
        //     '$this->folder()' => $this->folder(),
        //     '$destination' => $destination,
        // ]);

        try {
            // $content = Yaml::dump($contents, 10);
            $content = Yaml::dump($contents, 12, 2, Yaml::DUMP_OBJECT_AS_MAP | Yaml::DUMP_NULL_AS_TILDE);

            $full_path = $this->laravel->storagePath().$destination;

            $this->makeDirectory($full_path);

            $this->files->put($full_path, $content);

            $this->components->info(sprintf('Docs: %s [%s] created successfully.', $file, $full_path));

        } catch (\Exception $exception) {
            \Log::error(__METHOD__, [
                '$file' => $file,
                '$exception' => $exception,
                'error' => $exception->getMessage(),
            ]);
        }

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$content' => $content,
        // ]);

        return $content;
    }
}
