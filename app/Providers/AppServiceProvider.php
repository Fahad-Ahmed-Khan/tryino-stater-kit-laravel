<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    // Register repositories from interfaces
    $this->registerModules();

    // Optionally, you can register other services or bindings here
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }

  private function registerModules()
  {
    $interfacePath = app_path('Repositories/Interfaces');

    // Scan all Interface files
    foreach (File::files($interfacePath) as $file) {
        $interfaceClass = 'App\\Repositories\\Interfaces\\' . $file->getFilenameWithoutExtension();

        // Get base name (e.g. User from UserRepositoryInterface)
        $baseName = str_replace('RepositoryInterface', '', class_basename($interfaceClass));
        $concreteClass = 'App\\Repositories\\' . $baseName . 'Repository';

        if (interface_exists($interfaceClass) && class_exists($concreteClass)) {
            $this->app->bind($interfaceClass, $concreteClass);
        }
    }
  }
}