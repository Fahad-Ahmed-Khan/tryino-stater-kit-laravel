<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}
                            {--controller : Generate a resource controller}
                            {--request : Generate a FormRequest class}
                            {--route= : Add route in api.php or web.php}
                            {--test : Generate a test file}
                            {--dto : Generate a DTO class}
                            {--all : Generate all optional files}';

    protected $description = 'Generate a full module with repository, service, controller, DTO, test, and more.';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $files_count = 0;

        // Detect --all to enable all other options
        $controller = $this->option('all') || $this->option('controller');
        $request = $this->option('all') || $this->option('request');
        $test = $this->option('all') || $this->option('test');
        $dto = $this->option('all') || $this->option('dto');
        $routeType = $this->option('route') ?: ($this->option('all') ? 'api' : null);

        // Root path for the module
        $moduleBase = app_path("Modules/{$name}");

        // Paths
        $repoPath = "{$moduleBase}/Repositories";
        $contractPath = "{$repoPath}/Contracts";
        $servicePath = "{$moduleBase}/Services";
        $controllerPath = "{$moduleBase}/Http/Controllers";
        $requestPath = "{$moduleBase}/Http/Requests";
        $dtoPath = "{$moduleBase}/DTOs";
        $testPath = "{$moduleBase}/Tests";

        // Create required directories
        foreach ([$repoPath, $contractPath, $servicePath, $controllerPath, $requestPath, $dtoPath, $testPath] as $path) {
            File::ensureDirectoryExists($path);
        }

        // Interface
        $interfaceFile = "{$contractPath}/{$name}RepositoryInterface.php";
        if (!File::exists($interfaceFile)) {
            File::put($interfaceFile,
                "<?php\n\nnamespace App\\Modules\\{$name}\\Repositories\\Contracts;\n\ninterface {$name}RepositoryInterface extends \\App\\Repositories\\Contracts\\BaseRepositoryInterface {}\n"
            );
            $this->info("✅ Interface created: {$interfaceFile}");
            $files_count++;
        } else {
            $this->warn("⚠️ Interface already exists: {$interfaceFile}");
        }

        // Repository
        $repoFile = "{$repoPath}/{$name}Repository.php";
        if (!File::exists($repoFile)) {
            File::put($repoFile,
                "<?php\n\nnamespace App\\Modules\\{$name}\\Repositories;\n\nuse App\\Repositories\\BaseRepository;\nuse App\\Modules\\{$name}\\Repositories\\Contracts\\{$name}RepositoryInterface;\n\nclass {$name}Repository extends BaseRepository implements {$name}RepositoryInterface {\n    // Add module-specific logic here\n}\n"
            );
            $this->info("✅ Repository created: {$repoFile}");
            $files_count++;
        } else {
            $this->warn("⚠️ Repository already exists: {$repoFile}");
        }

        // Service
        $serviceFile = "{$servicePath}/{$name}Service.php";
        if (!File::exists($serviceFile)) {
            File::put($serviceFile,
                "<?php\n\nnamespace App\\Modules\\{$name}\\Services;\n\nuse App\\Modules\\{$name}\\Repositories\\Contracts\\{$name}RepositoryInterface;\nuse App\\Services\\BaseService;\n\nclass {$name}Service extends BaseService {\n    public function __construct({$name}RepositoryInterface \$repo) {\n        parent::__construct(\$repo);\n    }\n}\n"
            );
            $this->info("✅ Service created: {$serviceFile}");
            $files_count++;
        } else {
            $this->warn("⚠️ Service already exists: {$serviceFile}");
        }

        // Controller
        if ($controller) {
            $controllerFile = "{$controllerPath}/{$name}Controller.php";
            if (!File::exists($controllerFile)) {
                File::put($controllerFile,
                    "<?php\n\nnamespace App\\Modules\\{$name}\\Http\\Controllers;\n\nuse App\\Http\\Controllers\\Controller;\nuse Illuminate\\Http\\Request;\nuse App\\Modules\\{$name}\\Services\\{$name}Service;\n\nclass {$name}Controller extends Controller {\n    protected \$service;\n\n    public function __construct({$name}Service \$service) {\n        \$this->service = \$service;\n    }\n\n    public function index() {\n        return \$this->service->getAll();\n    }\n\n    public function store(Request \$request) {\n        return \$this->service->store(\$request->all());\n    }\n\n    public function show(\$id) {\n        return \$this->service->getById(\$id);\n    }\n\n    public function update(Request \$request, \$id) {\n        return \$this->service->update(\$id, \$request->all());\n    }\n\n    public function destroy(\$id) {\n        return \$this->service->destroy(\$id);\n    }\n}\n"
                );
                $this->info("✅ Controller created: {$controllerFile}");
                $files_count++;
            } else {
                $this->warn("⚠️ Controller already exists: {$controllerFile}");
            }
        }

        // Request
        if ($request) {
            $requestFile = "{$requestPath}/{$name}Request.php";
            if (!File::exists($requestFile)) {
                File::put($requestFile,
                    "<?php\n\nnamespace App\\Modules\\{$name}\\Http\\Requests;\n\nuse Illuminate\\Foundation\\Http\\FormRequest;\n\nclass {$name}Request extends FormRequest {\n    public function authorize() {\n        return true;\n    }\n\n    public function rules() {\n        return [\n            // Define validation rules\n        ];\n    }\n}\n"
                );
                $this->info("✅ Request created: {$requestFile}");
                $files_count++;
            } else {
                $this->warn("⚠️ Request already exists: {$requestFile}");
            }
        }

        // DTO
        if ($dto) {
            $dtoFile = "{$dtoPath}/{$name}DTO.php";
            if (!File::exists($dtoFile)) {
                File::put($dtoFile,
                    "<?php\n\nnamespace App\\Modules\\{$name}\\DTOs;\n\nuse App\\DTOs\\BaseDTO;\n\nclass {$name}DTO extends BaseDTO {\n    public string \$example;\n\n    public function __construct(array \$data) {\n        parent::__construct(\$data);\n    }\n}\n"
                );
                $this->info("✅ DTO created: {$dtoFile}");
                $files_count++;
            } else {
                $this->warn("⚠️ DTO already exists: {$dtoFile}");
            }
        }

        // Route
        if ($routeType === 'api' || $routeType === 'web') {
            $routePath = base_path("routes/{$routeType}.php");
            $routeLine = "Route::apiResource('" . Str::kebab($name) . "', App\\Modules\\{$name}\\Http\\Controllers\\{$name}Controller::class);";
            if (!Str::contains(File::get($routePath), $routeLine)) {
                File::append($routePath, "\n" . $routeLine . "\n");
                $this->info("✅ Route added to routes/{$routeType}.php");
                $files_count++;
            } else {
                $this->warn("⚠️ Route already exists in routes/{$routeType}.php");
            }
        }

        // Test
        if ($test) {
            $testFile = "{$testPath}/{$name}Test.php";
            if (!File::exists($testFile)) {
                File::put($testFile,
                    "<?php\n\nnamespace App\\Modules\\{$name}\\Tests;\n\nuse Illuminate\\Foundation\\Testing\\RefreshDatabase;\nuse Tests\\TestCase;\n\nclass {$name}Test extends TestCase\n{\n    use RefreshDatabase;\n\n    public function test_{$name}_index_returns_success()\n    {\n        \$response = \$this->getJson('/api/" . Str::kebab($name) . "');\n        \$response->assertStatus(200);\n    }\n}\n"
                );
                $this->info("✅ Test created: {$testFile}");
                $files_count++;
            } else {
                $this->warn("⚠️ Test already exists: {$testFile}");
            }
        }

        if ($files_count === 0) {
            $this->error("❌ No new files were created. Please check if the files already exist.");
            return Command::FAILURE;
        }

        $this->info("ℹ️ {$files_count} file(s) were created successfully.");
        return Command::SUCCESS;
    }
}
