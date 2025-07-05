<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $modulePath = app_path('Modules');

        foreach (File::directories($modulePath) as $moduleDir) {
            $module = basename($moduleDir);

            $contractsPath = "{$moduleDir}/Repositories/Contracts";
            $repositoryPath = "{$moduleDir}/Repositories";

            if (!File::isDirectory($contractsPath)) {
                continue;
            }

            foreach (File::files($contractsPath) as $contractFile) {
                $interfaceClass = "App\\Modules\\{$module}\\Repositories\\Contracts\\" . $contractFile->getFilenameWithoutExtension();
                $repoClass = "App\\Modules\\{$module}\\Repositories\\" . str_replace('Interface', '', $contractFile->getFilenameWithoutExtension());

                if (interface_exists($interfaceClass) && class_exists($repoClass)) {
                    // Bind specific interface to its implementation
                    $this->app->bind($interfaceClass, $repoClass);
                } elseif (interface_exists($interfaceClass) && class_exists('App\Core\Repositories\BaseRepository')) {
                    // Fallback to BaseRepository
                    $this->app->bind($interfaceClass, function () {
                        return new \App\Core\Repositories\BaseRepository;
                    });

                    Log::warning("⚠️ Fallback: Bound {$interfaceClass} to BaseRepository (specific repo not found)");
                }
            }
        }
    }
}
