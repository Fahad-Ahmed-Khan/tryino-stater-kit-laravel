<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new Repository and Interface if they do not exist';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        $repositoryPath = app_path("Repositories/{$name}Repository.php");
        $interfacePath  = app_path("Repositories/Interfaces/{$name}RepositoryInterface.php");

        // Check if files already exist
        if (File::exists($repositoryPath)) {
            $this->warn("Repository already exists: {$repositoryPath}");
        } else {
            $repoStub = File::get(base_path("app/Console/Commands/Stubs/repository.stub"));
            $repoStub = str_replace('{{model}}', $name, $repoStub);
            File::ensureDirectoryExists(app_path('Repositories'));
            File::put($repositoryPath, $repoStub);
            $this->info("Created: {$repositoryPath}");
        }

        if (File::exists($interfacePath)) {
            $this->warn("Interface already exists: {$interfacePath}");
        } else {
            $interfaceStub = File::get(base_path("app/Console/Commands/Stubs/interface.stub"));
            $interfaceStub = str_replace('{{model}}', $name, $interfaceStub);
            File::ensureDirectoryExists(app_path('Repositories/Interfaces'));
            File::put($interfacePath, $interfaceStub);
            $this->info("Created: {$interfacePath}");
        }
    }
}
