<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Swagger\Console\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Str;
use Playground\Make\Building\Concerns;
use Playground\Make\Configuration\Contracts\PrimaryConfiguration as PrimaryConfigurationContract;
use Playground\Make\Configuration\Model;
use Playground\Make\Console\Commands\GeneratorCommand;
use Playground\Make\Swagger\Building;
use Playground\Make\Swagger\Configuration\Swagger as Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\multiselect;

/**
 * \Playground\Make\Swagger\Console\Commands\SwaggerMakeCommand
 */
#[AsCommand(name: 'playground:make:swagger')]
class SwaggerMakeCommand extends GeneratorCommand
{
    use Building\BuildController;
    use Building\BuildControllerForm;
    use Building\BuildControllerId;
    use Building\BuildControllerIndex;
    use Building\BuildControllerLock;
    use Building\BuildControllerRestore;
    use Building\BuildModel;
    use Building\BuildModelColumns;
    use Building\BuildRequest;
    use Building\BuildSwagger;
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
    protected $name = 'playground:make:swagger';

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
    protected $type = 'Swagger';

    protected bool $isApi = false;

    protected bool $isResource = false;

    protected bool $replace = false;

    protected ?Model $modelRevision = null;

    public function prepareOptions(): void
    {
        $this->modelRevision = null;

        $options = $this->options();

        // if ($this->hasOption('playground') && $this->option('playground')) {
        //     $this->c->setOptions([
        //         'playground' => true,
        //     ]);
        // }

        $type = $this->getConfigurationType();

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

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$this->options()' => $this->options(),
        //     // '$this->c' => $this->c,
        //     '$this->model' => $this->model?->toArray(),
        //     '$this->modelRevision' => $this->modelRevision?->toArray(),
        //     // '$this->c' => $this->c->toArray(),
        //     '$this->searches' => $this->searches,
        // ]);
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

            if ($this->hasOption('controller-type') && is_string($this->option('controller-type')) ) {
                $this->c->setOptions([
                    'controller_type' => $this->option('controller-type'),
                ]);
            }

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

    // public function finish(): ?bool
    // {
    //     $this->saveConfiguration();

    //     // if ($this->c->test()) {
    //     //     $this->createTest();
    //     // }

    //     // if ($this->c->transformers()) {
    //     //     $this->createTransformers();
    //     // }

    //     // $this->saveConfiguration();
    //     dd([
    //         '__METHOD__' => __METHOD__,
    //         '$this->c' => $this->c,
    //         // '$this->c' => $this->c->toArray(),
    //         '$this->searches' => $this->searches,
    //         // '$this->analyze' => $this->analyze,
    //     ]);

    //     return $this->return_status;
    // }

    // /**
    //  * Build the class with the given name.
    //  *
    //  * @param  string  $name
    //  *
    //  * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
    //  */
    // protected function buildClass($name): string
    // {
    //     // if (in_array($this->c->type(), [
    //     //     'abstract',
    //     //     'model',
    //     //     'morph-pivot',
    //     //     'pivot',
    //     //     'playground-abstract',
    //     //     'playground',
    //     // ])) {
    //     $this->searches['use'] = '';
    //     $this->searches['use_class'] = '';

    //     $this->buildClass_model_table();

    //     if ($this->c->skeleton()) {
    //         $this->buildClass_skeleton();
    //     }

    //     $this->buildClass_docblock();
    //     // dd([
    //     //     '__METHOD__' => __METHOD__,
    //     //     // '$this->c' => $this->c,
    //     //     '$this->c' => $this->c->toArray(),
    //     //     '$this->searches' => $this->searches,
    //     //     '$this->analyze' => $this->analyze,
    //     // ]);
    //     $this->buildClass_implements();
    //     $this->buildClass_table_property();
    //     $this->buildClass_perPage();
    //     $this->c->apply();

    //     $this->buildClass_attributes();
    //     $this->buildClass_fillable();
    //     $this->buildClass_casts();

    //     // // Relationships
    //     $this->buildClass_HasOne();
    //     $this->buildClass_HasMany();

    //     $this->buildClass_uses($name);

    //     // $this->c->apply();
    //     $this->applyConfigurationToSearch(true);

    //     // dd([
    //     //     '__METHOD__' => __METHOD__,
    //     //     // '$this->c' => $this->c,
    //     //     '$this->searches' => $this->searches,
    //     //     '$this->c->skeleton()' => $this->c->skeleton(),
    //     // ]);

    //     return parent::buildClass($name);
    // }

    protected function getStub()
    {
        return sprintf(
            '%1$s/docs/api.yml',
            $this->getPackageFolder()
            // $this->getResourcePackageFolder()
        );
    }

    // /**
    //  * Resolve the fully-qualified path to the stub.
    //  *
    //  * @param  string  $stub
    //  * @return string
    //  */
    // protected function resolveStubPath($stub)
    // {
    //     return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
    //                     ? $customPath
    //                     : __DIR__.$stub;
    // }

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
        $options[] = ['controller-type', null, InputOption::VALUE_OPTIONAL, 'The controller type for the docs.'];
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
        $type = $this->getConfigurationType();

        if ($type === 'api') {
            return 'api.json';
        }

        return sprintf(
            '%1$s/%2$s.%3$s.json',
            Str::of($this->c->name())->kebab(),
            Str::of($this->getType())->kebab(),
            Str::of($this->getConfigurationType())->kebab()
        );
    }
}
