<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Make\OpenAPI\Console\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Str;
use Playground\Make\Building\Concerns;
use Playground\Make\Configuration\Contracts\PrimaryConfiguration as PrimaryConfigurationContract;
use Playground\Make\Configuration\Model;
use Playground\Make\Console\Commands\GeneratorCommand;
use Playground\Make\OpenAPI\Building;
use Playground\Make\OpenAPI\Configuration\OpenAPI as Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\multiselect;

/**
 * \Playground\Make\OpenAPI\Console\Commands\OpenAPIMakeCommand
 */
#[AsCommand(name: 'playground:make:openapi')]
class OpenAPIMakeCommand extends GeneratorCommand
{
    use Building\BuildController;
    use Building\BuildControllerForm;
    use Building\BuildControllerId;
    use Building\BuildControllerIndex;
    use Building\BuildControllerLock;
    use Building\BuildControllerRestore;
    use Building\BuildControllerRevision;
    use Building\BuildControllerRevisions;
    use Building\BuildExternalDocs;
    use Building\BuildInfo;
    use Building\BuildModel;
    use Building\BuildModelColumns;
    use Building\BuildOpenAPI;
    use Building\BuildRequest;
    use Building\BuildServers;
    use Concerns\BuildImplements;
    use Concerns\BuildUses;
    use CreatesMatchingTest;

    /**
     * @var class-string<Configuration>
     */
    public const CONF = Configuration::class;

    /**
     * @var PrimaryConfigurationContract&Configuration
     */
    protected PrimaryConfigurationContract $c;

    const SEARCH = [
        'docs' => '',
        // 'base_docs' => 'welcome',
        'extends' => '',
        'class' => '',
        'controller' => '',
        'folder' => '',
        'namespace' => '',
        'organization' => '',
        // 'namespacedModel' => '',
        // 'NamespacedDummyUserModel' => '',
        // 'namespacedUserModel' => '',
        // 'user' => '',
        // 'model' => '',
        // 'modelVariable' => '',
        // 'model_column' => '',
        // 'model_label' => '',
        // 'model_slug_plural' => '',
        'module' => '',
        'module_slug' => '',
        'title' => '',
        'package' => '',
        'config' => '',
        // 'docs_prefix' => '',
    ];

    protected string $path_destination_folder = 'docs';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'playground:make:openapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new docs group';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'OpenAPI';

    protected bool $isApi = false;

    protected bool $isResource = false;

    protected bool $replace = false;

    protected ?Model $modelRevision = null;

    protected string $route_prefix = '';

    public function prepareOptions(): void
    {
        $this->modelRevision = null;

        $options = $this->options();
        //        dump([
        //            '__METHOD__' => __METHOD__,
        //            '$this->options()' => $this->options(),
        //            '$this->c' => $this->c,
        //            // '$this->model' => $this->model?->toArray(),
        //            // '$this->modelRevision' => $this->modelRevision?->toArray(),
        //            // '$this->c' => $this->c->toArray(),
        //            '$this->searches' => $this->searches,
        //        ]);

        // if ($this->hasOption('playground') && $this->option('playground')) {
        //     $this->c->setOptions([
        //         'playground' => true,
        //     ]);
        // }

        $type = $this->c->type();

        if (in_array($type, [
            'resource',
            'playground-resource',
        ])) {
            $this->isApi = false;
            $this->isResource = true;
        } elseif (in_array($type, [
            'api',
            'playground-api',
        ])) {
            $this->isApi = true;
            $this->isResource = false;
        }

        $this->route_prefix = '';

        if ($this->isApi) {
            $this->route_prefix = '/api';
        } elseif ($this->isResource) {
            $this->route_prefix = '/resource';
        }
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$type' => $type,
        //     '$this->route_prefix' => $this->route_prefix,
        //     '$this->isApi' => $this->isApi,
        //     '$this->isResource' => $this->isResource,
        //     '$this->c->type()' => $this->c->type(),
        //     '$this->options()' => $this->options(),
        // ]);

        $this->initModel($this->c->skeleton());

        if ($this->hasOption('model-revision-file')
            && is_string($this->option('model-revision-file'))
        ) {
            $this->modelRevision = new Model(
                $this->readJsonFileAsArray($this->option('model-revision-file'), false, 'Model Revision File'),
            );
            $this->modelRevision->apply();
            // dd([
            //     '__METHOD__' => __METHOD__,
            //     // '$this->options()' => $this->options(),
            //     '$this->option(model-revision-file)' => $this->option('model-revision-file'),
            //     // '$this->c' => $this->c,
            //     // '$this->model' => $this->model?->toArray(),
            //     // '$this->c' => $this->c->toArray(),
            //     // '$this->searches' => $this->searches,
            //     // 'readJsonFileAsArray' => $this->readJsonFileAsArray($this->option('model-revision-file'), false, 'Model Revision File'),
            //     '$this->modelRevision' => $this->modelRevision?->toArray(),
            // ]);
        }

        // $this->saveConfiguration();

        //        dump([
        //            '__METHOD__' => __METHOD__,
        //            '$this->options()' => $this->options(),
        //            '$this->c' => $this->c,
        //            // '$this->model' => $this->model?->toArray(),
        //            // '$this->modelRevision' => $this->modelRevision?->toArray(),
        //            // '$this->c' => $this->c->toArray(),
        //            '$this->searches' => $this->searches,
        //        ]);
    }

    /**
     * Execute the console command.
     *
     * Types:
     * - model
     * - controller
     * - info
     * - request
     * - response
     * - security
     * - externalDocs
     * - servers
     * - paths
     * - component: securitySchemes, parameters, responses, schemas
     * - tags
     */
    public function handle()
    {
        $this->reset();

        $name = $this->getNameInput();

        $type = $this->getConfigurationType();

        $this->load_base_file();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$type' => $type,
        // ]);

        if ($type === 'api') {
            $this->save_base_file();
        } elseif (in_array($type, [
            'controller',
            'playground-api',
            'playground-resource',
        ])) {

            $this->doc_info();
            $this->doc_external_docs();
            $this->doc_servers();
            $this->doc_model();
            $this->doc_model_revision();

            $this->doc_controller();

            $this->save_base_file();

        } elseif ($type === 'model') {

            if (empty($this->model?->create())) {
                $this->components->error('Provide a [--model-file] with a [create] section.');
                $this->return_status = true;

                return $this->return_status;
            }

            $this->doc_model();
            $this->doc_model_revision();

            $this->save_base_file();
        }

        $this->saveConfiguration();

        return $this->return_status;
    }

    protected function getStub()
    {
        return sprintf(
            '%1$s/docs/api.yml',
            $this->getPackageFolder()
            // $this->getResourcePackageFolder()
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return Str::of(
            $this->parseClassInput($rootNamespace)
        )->finish('\\')->finish('Models')->toString();
    }

    /**
     * @var array<int, string>
     */
    protected array $options_type_suggested = [
        'abstract',
        'model',
        'morph-pivot',
        'pivot',
        'playground',
        'playground-model',
    ];

    /**
     * Get the console command options.
     *
     * @return array<int, mixed>
     */
    protected function getOptions(): array
    {
        $options = parent::getOptions();

        $options[] = ['title', null, InputOption::VALUE_OPTIONAL, 'The title of the docs'];
        $options[] = ['prefix', null, InputOption::VALUE_OPTIONAL, 'The prefix slug for the docs.'];
        // $options[] = ['controller-type', null, InputOption::VALUE_OPTIONAL, 'The controller type for the docs.'];
        $options[] = ['revision', null, InputOption::VALUE_NONE, 'The docs should document revision end points.'];
        $options[] = ['model-revision-file', null, InputOption::VALUE_OPTIONAL, 'The file for the revision model.'];

        return $options;
    }

    // /**
    //  * Interact further with the user if they were prompted for missing arguments.
    //  *
    //  * @return void
    //  */
    // protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    // {
    //     $name = $this->getNameInput();
    //     if (($name && $this->isReservedName($name)) || $this->didReceiveOptions($input)) {
    //         return;
    //     }

    //     collect(multiselect('Would you like any of the following?', [
    //         'seed' => 'Database Seeder',
    //         'factory' => 'Factory',
    //         'requests' => 'Form Requests',
    //         'migration' => 'Migration',
    //         'policy' => 'Policy',
    //         'resource' => 'Resource Controller',
    //     ]))->each(fn ($option) => $input->setOption(is_string($option) ? $option : '', true));
    // }

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     * @return bool
     */
    protected function handleTestCreation($path)
    {
        if (! $this->option('test') && ! $this->option('pest') && ! $this->option('phpunit')) {
            return false;
        }
        // dd([
        //     '__METHOD__' => __METHOD__,
        // ]);

        // $this->createTest();

        return true;
    }

    protected function getConfigurationFilename(): string
    {
        $type = $this->c->type();

        if ($type === 'api') {
            return 'api.json';
        }

        $file = sprintf(
            '%1$s/%2$s.json',
            Str::of($this->c->name())->kebab(),
            'openapi',
        );

        //         dd([
        //             '__METHOD__' => __METHOD__,
        //             '$file' => $file,
        //         ]);
        return $file;
    }
}
