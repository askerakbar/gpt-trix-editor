<?php

 namespace AskerAkbar\GptTrixEditor;


use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;

class GptTrixEditorServiceProvider extends PackageServiceProvider
{

    public static string $name = 'gpt-trix-editor';

    public function configurePackage(Package $package): void
    {
        $package
        ->name(static::$name)
        ->hasViews()
        ->hasConfigFile()
        ->hasTranslations();
    }

    public function boot()
    {
        parent::boot();
    }

    public function packageBooted()
    {
        FilamentAsset::register([
            Js::make('gpt-trix-editor', __DIR__ . '/../dist/gpt-trix-editor.js'),
        ], package: 'askerakbar/gpt-trix-editor');
    }

}
