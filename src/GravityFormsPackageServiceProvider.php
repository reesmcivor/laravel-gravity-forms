<?php

namespace ReesMcIvor\GravityForms;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use ReesMcIvor\Forms\Http\LiveWire\Question\Text;
use ReesMcIvor\Forms\View\Components\Stepped;

class GravityFormsPackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if($this->app->runningInConsole()) {
            $migrationPath = function_exists('tenancy') ? 'migrations/tenant' : 'migrations';
            $this->publishes([
                __DIR__ . '/../database/migrations/tenant' => database_path($migrationPath),
                __DIR__ . '/../publish/tests' => base_path('tests/GravityForms'),
                __DIR__ . '/../publish/config' => base_path('config'),
            ], 'reesmcivor-gravity-forms');
        }

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->commands([
            \ReesMcIvor\GravityForms\Console\Commands\GravityForms::class,
        ]);
    }

    private function modulePath($path)
    {
        return __DIR__ . '/../../' . $path;
    }
}
