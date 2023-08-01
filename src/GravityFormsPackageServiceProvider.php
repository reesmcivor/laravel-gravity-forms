<?php

namespace ReesMcIvor\GravtyForms;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use ReesMcIvor\Forms\Http\LiveWire\Question\Text;
use ReesMcIvor\Forms\View\Components\Stepped;

class GravityFormsPackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../publish/tests' => base_path('tests/GravityForms'),
                __DIR__ . '/../publish/config' => base_path('config'),
            ], 'reesmcivor-gravity-forms');
        }
    }

    private function modulePath($path)
    {
        return __DIR__ . '/../../' . $path;
    }
}
